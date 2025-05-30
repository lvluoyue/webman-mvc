<?php

namespace app\process;

use DI\Attribute\Inject;
use Illuminate\Database\Eloquent\Model;
use Luoyue\WebmanMvcCore\annotation\core\parser\EventParser;
use think\Model as ThinkModel;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionFunctionAbstract;
use support\Container;
use support\exception\BusinessException;
use support\exception\InputTypeException;
use support\exception\InputValueException;
use support\exception\MissingInputException;
use Webman\App;
use Webman\Http\Request;
use Closure;
use Webman\Http\UploadFile;

class Http extends App
{
    /**
     * ResolveInject.
     * @param string $plugin
     * @param array|Closure $call
     * @param $args
     * @return Closure
     * @see Dependency injection through reflection information
     */
    protected static function resolveInject(string $plugin, $call, $args): Closure
    {
        return function (Request $request) use ($plugin, $call, $args) {
            $reflector = static::getReflector($call);
            $args = array_values(static::resolveMethodDependencies(static::container($plugin), $request,
                array_merge($request->all(), $request->file(), $args), $reflector, static::config($plugin, 'app.debug')));
            return $call(...$args);
        };
    }

    /**
     * Check whether inject is required.
     * @throws ReflectionException
     */
    protected static function isNeedInject($call, array &$args) : bool
    {
        if (is_array($call) && !method_exists($call[0], $call[1])) {
            return false;
        }
        $reflector = static::getReflector($call);
        $reflectionParameters = $reflector->getParameters();
        if (!$reflectionParameters) {
            return false;
        }
        $firstParameter = current($reflectionParameters);
        unset($reflectionParameters[key($reflectionParameters)]);
        $adaptersList = ['int', 'string', 'bool', 'array', 'object', 'float', 'mixed', 'resource'];
        $keys = [];
        $needInject = false;
        foreach ($reflectionParameters as $parameter) {
            foreach ($parameter->getAttributes() as $attribute) {
                if ($attribute->getName() === Inject::class) {
                    $needInject = true;
                }
            }
            $parameterName = $parameter->name;
            $keys[] = $parameterName;
            if ($parameter->hasType()) {
                $typeName = $parameter->getType()->getName();
                if (!in_array($typeName, $adaptersList, true)) {
                    $needInject = true;
                    continue;
                }
                if (!array_key_exists($parameterName, $args)) {
                    $needInject = true;
                    continue;
                }
                switch ($typeName) {
                    case 'int':
                    case 'float':
                        if (!is_numeric($args[$parameterName])) {
                            return true;
                        }
                        $args[$parameterName] = $typeName === 'int' ? (int) $args[$parameterName] : (float) $args[$parameterName];
                        break;
                    case 'bool':
                        $args[$parameterName] = (bool) $args[$parameterName];
                        break;
                    case 'array':
                    case 'object':
                        if (!is_array($args[$parameterName])) {
                            return true;
                        }
                        $args[$parameterName] = $typeName === 'array' ? $args[$parameterName] : (object) $args[$parameterName];
                        break;
                    case 'string':
                    case 'mixed':
                    case 'resource':
                        break;
                }
            }
        }
        if (array_keys($args) !== $keys) {
            return true;
        }
        if (!$firstParameter->hasType()) {
            return $firstParameter->getName() !== 'request';
        }
        if (!is_a(static::$requestClass, $firstParameter->getType()->getName(), true)) {
            return true;
        }

        return $needInject;
    }

    /**
     * Return dependent parameters.
     * @throws BusinessException
     * @throws ReflectionException
     */
    protected static function resolveMethodDependencies(ContainerInterface $container, Request $request, array $inputs, ReflectionFunctionAbstract $reflector, bool $debug) : array
    {
        $parameters = [];
        foreach ($reflector->getParameters() as $parameter) {
            $parameterName = $parameter->name;
            $type = $parameter->getType();
            $typeName = $type?->getName();

            // 注入
            foreach ($parameter->getAttributes() as $attribute) {
                if ($attribute->getName() === Inject::class) {
                    if (empty($attribute->getArguments())) {
                        throw (new MissingInputException())->data([
                            'parameter' => $parameterName,
                        ])->debug($debug);
                    }
                    $parameters[$parameterName] = Container::get($attribute->getArguments()[0]);
                    continue 2;
                }
            }

            if ($typeName && is_a($request, $typeName)) {
                $parameters[$parameterName] = $request;
                continue;
            }

            if (!array_key_exists($parameterName, $inputs)) {
                if (!$parameter->isDefaultValueAvailable()) {
                    if (!$typeName || (!class_exists($typeName) && !enum_exists($typeName)) || enum_exists($typeName)) {
                        throw (new MissingInputException())->data([
                            'parameter' => $parameterName,
                        ])->debug($debug);
                    }
                } else {
                    $parameters[$parameterName] = $parameter->getDefaultValue();
                    continue;
                }
            }

            $parameterValue = $inputs[$parameterName] ?? null;

            switch ($typeName) {
                case 'int':
                case 'float':
                    if (!is_numeric($parameterValue)) {
                        throw (new InputTypeException())->data([
                            'parameter' => $parameterName,
                            'exceptType' => $typeName,
                            'actualType' => gettype($parameterValue),
                        ])->debug($debug);
                    }
                    $parameters[$parameterName] = $typeName === 'float' ? (float) $parameterValue : (int) $parameterValue;
                    break;
                case 'bool':
                    $parameters[$parameterName] = (bool) $parameterValue;
                    break;
                case 'array':
                case 'object':
                    if (!is_array($parameterValue)) {
                        throw (new InputTypeException())->data([
                            'parameter' => $parameterName,
                            'exceptType' => $typeName,
                            'actualType' => gettype($parameterValue),
                        ])->debug($debug);
                    }
                    $parameters[$parameterName] = $typeName === 'object' ? (object) $parameterValue : $parameterValue;
                    break;
                case 'string':
                case 'mixed':
                case 'resource':
                case null:
                    $parameters[$parameterName] = $parameterValue;
                    break;
                default:
                    $subInputs = is_array($parameterValue) ? $parameterValue : [];
                    if (is_a($typeName, Model::class, true) || is_a($typeName, ThinkModel::class, true)) {
                        $parameters[$parameterName] = $container->make($typeName, [
                            'attributes' => $subInputs,
                            'data' => $subInputs,
                        ]);
                        break;
                    }
                    if (enum_exists($typeName)) {
                        $reflection = new ReflectionEnum($typeName);
                        if ($reflection->hasCase($parameterValue)) {
                            $parameters[$parameterName] = $reflection->getCase($parameterValue)->getValue();
                            break;
                        } elseif ($reflection->isBacked()) {
                            foreach ($reflection->getCases() as $case) {
                                if ($case->getValue()->value === $parameterValue) {
                                    $parameters[$parameterName] = $case->getValue();
                                    break;
                                }
                            }
                        }
                        if (!array_key_exists($parameterName, $parameters)) {
                            throw (new InputValueException())->data([
                                'parameter' => $parameterName,
                                'enum' => $typeName,
                            ])->debug($debug);
                        }
                        break;
                    }
                    if ($typeName == UploadFile::class) {
                        $parameters[$parameterName] = $request->file($parameterName);
                    } else if (is_array($subInputs) && $constructor = (new ReflectionClass($typeName))->getConstructor()) {
                        $parameters[$parameterName] = $container->make($typeName, static::resolveMethodDependencies($container, $request, $subInputs, $constructor, $debug));
                    } else {
                        $parameters[$parameterName] = $container->make($typeName);
                    }
                    break;
            }
        }

        return $parameters;
    }
}

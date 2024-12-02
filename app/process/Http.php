<?php

namespace app\process;

use DI\Attribute\Inject;
use Illuminate\Database\Eloquent\Model;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunctionAbstract;
use support\Container;
use support\exception\BusinessException;
use support\exception\InputTypeException;
use support\exception\MissingInputException;
use Webman\App;
use Webman\Http\Request;

class Http extends App
{
    /**
     * Check whether inject is required.
     * @param $call
     * @param array $args
     * @return bool
     * @throws ReflectionException
     */
    protected static function isNeedInject($call, array &$args): bool
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
                if($attribute->getName() == Inject::class) {
                    $needInject = true;
                }
            }
            $parameterName = $parameter->name;
            $keys[] = $parameterName;
            if ($parameter->hasType()) {
                $typeName = $parameter->getType()->getName();
                if (!in_array($typeName, $adaptersList)) {
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
                        $args[$parameterName] = $typeName === 'int' ? (int)$args[$parameterName]: (float)$args[$parameterName];
                        break;
                    case 'bool':
                        $args[$parameterName] = (bool)$args[$parameterName];
                        break;
                    case 'array':
                    case 'object':
                        if (!is_array($args[$parameterName])) {
                            return true;
                        }
                        $args[$parameterName] = $typeName === 'array' ? $args[$parameterName] : (object)$args[$parameterName];
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
     * Return dependent parameters
     * @param ContainerInterface $container
     * @param Request $request
     * @param array $inputs
     * @param ReflectionFunctionAbstract $reflector
     * @return array
     * @throws BusinessException
     * @throws ReflectionException
     */
    protected static function resolveMethodDependencies(ContainerInterface $container, Request $request, array $inputs, ReflectionFunctionAbstract $reflector): array
    {
        $parameters = [];
        foreach ($reflector->getParameters() as $parameter) {
            $parameterName = $parameter->name;
            foreach ($parameter->getAttributes() as $attribute) {
                if($attribute->getName() == Inject::class) {
                    if(empty($attribute->getArguments())) {
                        throw (new MissingInputException())->setData([
                            'parameter' => $parameterName,
                        ]);
                    }
                    $parameters[$parameterName] = Container::get($attribute->getArguments()[0]);
                    continue 2;
                }
            }
            $type = $parameter->getType();
            $typeName = $type ? $type->getName() : null;

            if ($typeName && is_a($request, $typeName)) {
                $parameters[$parameterName] = $request;
                continue;
            }

            if (!array_key_exists($parameterName, $inputs)) {
                if (!$parameter->isDefaultValueAvailable()) {
                    if (!$typeName || !class_exists($typeName)) {
                        throw (new MissingInputException())->setData([
                            'parameter' => $parameterName,
                        ]);
                    }
                } else {
                    $parameters[$parameterName] = $parameter->getDefaultValue();
                    continue;
                }
            }

            switch ($typeName) {
                case 'int':
                case 'float':
                    if (!is_numeric($inputs[$parameterName])) {
                        throw (new InputTypeException())->setData([
                            'parameter' => $parameterName,
                            'exceptType' => $typeName,
                            'actualType' => gettype($inputs[$parameterName]),
                        ]);
                    }
                    $parameters[$parameterName] = $typeName === 'float' ? (float)$inputs[$parameterName] :  (int)$inputs[$parameterName];
                    break;
                case 'bool':
                    $parameters[$parameterName] = (bool)$inputs[$parameterName];
                    break;
                case 'array':
                case 'object':
                    if (!is_array($inputs[$parameterName])) {
                        throw (new InputTypeException())->setData([
                            'parameter' => $parameterName,
                            'exceptType' => $typeName,
                            'actualType' => gettype($inputs[$parameterName]),
                        ]);
                    }
                    $parameters[$parameterName] = $typeName === 'object' ? (object)$inputs[$parameterName] : $inputs[$parameterName];
                    break;
                case 'string':
                case 'mixed':
                case 'resource':
                case null:
                    $parameters[$parameterName] = $inputs[$parameterName];
                    break;
                default:
                    $subInputs = isset($inputs[$parameterName]) && is_array($inputs[$parameterName]) ? $inputs[$parameterName] : [];
                    if (is_a($typeName, Model::class, true) || is_a($typeName, ThinkModel::class, true)) {
                        $parameters[$parameterName] = $container->make($typeName, [
                            'attributes' => $subInputs,
                            'data' => $subInputs
                        ]);
                        break;
                    }
                    if (is_array($subInputs) && $constructor = (new ReflectionClass($typeName))->getConstructor()) {
                        $parameters[$parameterName] = $container->make($typeName, static::resolveMethodDependencies($container, $request, $subInputs, $constructor));
                    } else {
                        $parameters[$parameterName] = $container->make($typeName);
                    }
                    break;
            }
        }

        return $parameters;
    }

}
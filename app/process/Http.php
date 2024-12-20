<?php

namespace app\process;

use DI\Attribute\Inject;
use Illuminate\Database\Eloquent\Model;
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
use support\Response;
use Throwable;
use Webman\App;
use Webman\Http\Request;
use Workerman\Connection\TcpConnection;

class Http extends App
{

    /**
     * OnWorkerStart.
     * @param $worker
     * @return void
     */
    public function onWorkerStart($worker)
    {
        $appName = env("SERVER_APP_NAME", "webman");
        $lockFile = runtime_path("windows/start_$appName.php");
        if(env('SERVER_OPEN_BROWSER', false) && DIRECTORY_SEPARATOR !== '/' && time() - filemtime($lockFile) <= 3) {
            exec('start http://127.0.0.1:' .  env('SERVER_APP_PROT', 8787));
        }
        parent::onWorkerStart($worker);
    }

    /**
     * OnMessage.
     * @param TcpConnection|mixed $connection
     * @param Request|mixed $request
     * @return null
     * @throws Throwable
     */
    public function onMessage($connection, $request)
    {
        parent::onMessage($connection, $request);
    }
    /**
     * Send.
     * @param TcpConnection|mixed $connection
     * @param mixed|Response $response
     * @param Request|mixed $request
     * @return void
     */
    protected static function send($connection, $response, $request)
    {
//        print_r(Context::get());
        parent::send($connection, $response, $request);
    }

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
    protected static function resolveMethodDependencies(ContainerInterface $container, Request $request, array $inputs, ReflectionFunctionAbstract $reflector, bool $debug): array
    {
        $parameters = [];
        foreach ($reflector->getParameters() as $parameter) {
            $parameterName = $parameter->name;
            $type = $parameter->getType();
            $typeName = $type?->getName();

            // 注入
            foreach ($parameter->getAttributes() as $attribute) {
                if($attribute->getName() == Inject::class) {
                    if(empty($attribute->getArguments())) {
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
                    $parameters[$parameterName] = $typeName === 'float' ? (float)$parameterValue :  (int)$parameterValue;
                    break;
                case 'bool':
                    $parameters[$parameterName] = (bool)$parameterValue;
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
                    $parameters[$parameterName] = $typeName === 'object' ? (object)$parameterValue : $parameterValue;
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
                            'data' => $subInputs
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
                                if ($case->getValue()->value == $parameterValue) {
                                    $parameters[$parameterName] = $case->getValue();
                                    break;
                                }
                            }
                        }
                        if (!array_key_exists($parameterName, $parameters)) {
                            throw (new InputValueException())->data([
                                'parameter' => $parameterName,
                                'enum' => $typeName
                            ])->debug($debug);
                        }
                        break;
                    }
                    if (is_array($subInputs) && $constructor = (new ReflectionClass($typeName))->getConstructor()) {
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
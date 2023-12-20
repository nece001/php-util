<?php

namespace Common\Library;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use Throwable;

/**
 * 动态调用类方法
 *
 * @author gjw
 * @created 2023-11-30 15:46:52
 */
class Invoke
{

    private static $provider;
    private $instance;

    /**
     * 设置自动注入类
     *
     * @author gjw
     * @created 2023-11-30 16:00:42
     *
     * @param array $provider
     * @return void
     */
    public static function setProvider(array $provider)
    {
        /**
         * $provider = ['类型(接口)命名空间'=>'类命名空间']
         */
        self::$provider = $provider;
    }

    /**
     * 实例化
     *
     * @param object|string $class 类名
     * @param array $params 参数
     * @return void
     */
    public function __construct($class, array $params = [])
    {
        $reflect = new ReflectionClass($class);
        $args = $this->buildMethodArgs($reflect->getConstructor(), $params);

        $this->instance = $reflect->newInstanceArgs($args);
    }

    /**
     * 获取实例
     *
     * @author gjw
     * @created 2023-11-30 15:34:51
     *
     * @return object
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * 调用方法
     *
     * @author gjw
     * @created 2023-11-30 15:44:59
     *
     * @param string $method_name
     * @param array $params
     * @return mixed
     */
    public function call(string $method_name, array $params = [])
    {
        $method = new ReflectionMethod($this->instance, $method_name);
        return $method->invokeArgs($this->instance, $this->buildMethodArgs($method, $params));
    }

    /**
     * 构造方法参数
     *
     * @author gjw
     * @created 2023-11-30 15:37:35
     *
     * @param ReflectionMethod $method
     * @param array $params
     * @return array
     */
    private function buildMethodArgs(ReflectionMethod $method, array $params)
    {
        $args = array();
        foreach ($method->getParameters() as $param) {
            $var_name = $param->getName();
            $var_type = $param->getType();
            $var_value = null;
            if (isset($params[$var_name])) {
                $var_value = $params[$var_name];
            } elseif ($param->isDefaultValueAvailable()) {
                $var_value = $param->getDefaultValue();
            } else {
                if ($var_type) {
                    if (isset(self::$provider[$var_type->getName()])) {
                        $var_value = new self::$provider[$var_type->getName()]();
                    } else {
                        $var_value = (new Invoke($var_type->getName()))->getInstance();
                    }
                }

                if (!$var_value) {
                    throw new Exception($var_name . ' 参数缺失');
                }
            }

            if ($var_type && !$var_type->isBuiltin() && !is_object($var_value)) {
                $args[] = (new Invoke($var_type->getName(), $var_value))->getInstance();
            } else {
                $args[] = $var_value;
            }
        }

        return $args;
    }
}

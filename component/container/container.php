<?php

namespace rephp\framework\component\container;

use \Error;
use \Exception;
use \ReflectionClass;
use Psr\Container\ContainerInterface;
use rephp\framework\component\container\exceptions\notFoundException;
use rephp\framework\component\container\exceptions\containerException;
use rephp\framework\component\container\bootstrap\rephpContainerBootstrap;

/**
 * 容器
 * @package xy\framework\component\container
 */
class container implements ContainerInterface, rephpContainerBootstrap
{
    /**
     * @var 容器实例化本身
     */
    private static $container;
    /**
     * @var array 容器内管理的对象
     */
    public static $instance = [];

    /**
     * 获取容器本身实例
     * @return mixed
     */
    public static function getContainer()
    {
        is_object(self::$container) || self::$container =  new self();
        return self::$container;
    }

    /**
     * 获取容器中的对象实例
     * @param string $name 对象注册名字
     * @return object
     */
    public function get($name)
    {
        try {
            $has = $this->has($name);
            if ($has) {
                return self::$instance[$name];
            }
        } catch (Error $e) {
            throw new containerException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        throw new notFoundException('class not exists: ' . $name);
    }

    /**
     * 获取容器中的对象实例
     * @param string $name 对象注册名字
     * @return boolean
     */
    public function has($name)
    {
        return isset(self::$instance[$name]);
    }

    /**
     * 获得类的对象实例
     * @param string  $name       要注册的实例名字
     * @param string  $className  类名
     * @param array   $userParams 用户自定义参数（类对象参数除外的普通参数）
     * @param boolean $rebind     是否强制绑定
     * @return ReflectionClass|boolean
     */
    public function bind($name, $className, $userParams = [], $rebind = false)
    {
        try {
            $has = $this->has($name);
            if ($rebind || !$has) {
                $paramArr              = $this->getMethodParams($className, '__construct', $userParams);
                self::$instance[$name] = (new ReflectionClass($className))->newInstanceArgs($paramArr);
            }
        } catch (Error $e) {
            throw new containerException($e->getMessage(), $e->getCode(), $e->getPrevious());
        } catch (Exception $e) {
            throw new containerException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        return isset(self::$instance[$name]) ? self::$instance[$name] : false;
    }

    /**
     * 执行类的方法
     * @param string $className  [类名]
     * @param string $methodName [方法名称]
     * @param array  $params     [额外的参数]
     * @return [type]             [description]
     */
    public function call($className, $methodName, $params = [])
    {
        // 获取类的实例
        empty($methodName) && $methodName = '__construct';
        // 获取类的实例
        $constructParams = ($methodName == '__construct') ? $params : [];
        $instance        = $this->bind($className, $className, $constructParams);
        try {
            if ($instance->hasMethod($methodName)) {
                // 获取该方法所需要依赖注入的参数
                $paramArr = $this->getMethodParams($className, $methodName, $params);
                return $instance->{$methodName}(...$paramArr);
            } else {
                throw new notFoundException($className . '中不存在方法' . $methodName);
            }
        } catch (Error $e) {
            throw new containerException($e->getMessage(), $e->getCode(), $e->getPrevious());
        } catch (Exception $e) {
            throw new containerException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

    }

    /**
     * 获得类的方法参数，只获得有类型的参数
     * @param  [type] $className   [description]
     * @param  [type] $methodsName [description]
     * @return [type]              [description]
     */
    protected function getMethodParams($className, $methodsName = '__construct', $userParams = [])
    {
        // 通过反射获得该类
        $class    = new ReflectionClass($className);
        $paramArr = []; // 记录参数，和参数类型

        // 判断该类是否有构造函数
        if ($class->hasMethod($methodsName)) {
            // 获得构造函数
            $construct = $class->getMethod($methodsName);
            // 判断构造函数是否有参数
            $params = $construct->getParameters();
            $params = (array)$params;
            // 判断参数类型
            foreach ($params as $key => $param) {
                //如果参数是类对象，则可以获取
                if ($paramClass = $param->getClass()) {
                    $paramClassName = $paramClass->getName();
                    $paramArr[]     = $this->bind($paramClassName, $paramClassName);
                } else {
                    $paramArr[] = array_shift($userParams);
                }
            }

        }

        return $paramArr;
    }
}
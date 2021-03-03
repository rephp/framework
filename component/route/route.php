<?php

namespace rephp\component\route;

use rephp\component\container\container;

/**
 * 路由层
 * @method static get(string $env, Callable $callback)
 * @method static post(string $env, Callable $callback)
 * @method static put(string $env, Callable $callback)
 * @method static delete(string $env, Callable $callback)
 * @method static options(string $env, Callable $callback)
 * @method static head(string $env, Callable $callback)
 */
class route
{
    /**
     * @var 路由文件存放路径
     */
    private $routePath;
    /**
     * @var string[] 路由允许请求的方法
     */
    private $allowMethod = ['get', 'post', 'put', 'delete', 'options', 'head'];

    /**
     * 根据当前url动态生成路由
     * @param string $routePath
     * @return bool
     */
    public function start($routePath)
    {
        //1.分析url
        $params     = $this->getUrlRouteInfo();
        $module     = $params['module'];
        $controller = $params['controller'];
        $action     = $params['action'];
        //2.根据现有路由，获得路由配置表配置的方法。如没设置则默认为get方法才可以请求当前路由。
        //todo:路由表以后缓存到内存中
        //优先加载自定义路由
        file_exists($routePath . 'env.php') && require $routePath . 'env.php';
        //默认加载机制start
        //3.获取路由自定义类型
        $method = $this->getRouteMethod($module, $controller, $action, $routePath);

        //4.计算路由地址
        $module2     = strtolower($module);
        $controller2 = strtolower($controller);
        $action2     = strtolower($action);
        $routeUri    = '/';
        if (!($module2 == 'index' && ($controller2 == 'index' && $action2 == 'index'))) {
            $routeUri = $module . '/';
            if (!($controller2 == 'index' && $action2 == 'index')) {
                $routeUri .= $controller . '/';
                $action2 == 'index' || $routeUri .= $action . '/';
            }
            in_array(substr($routeUri, -1), ['/', '\\']) && $routeUri = substr($routeUri, 0, strlen($routeUri) - 1);
        }

        //5.挂载路由
        return container::getContainer()->get('coreRoute')->run($routeUri, $module, $controller, $action, $method);
    }

    /**
     * 根据路由判断配置信息中设定的支持请求方式
     * @param string $module     模块名字
     * @param string $controller 控制器名字
     * @param string $action     方法名字
     * @param string $routePath  路由目录
     * @return mixed|string
     */
    public function getRouteMethod($module, $controller, $action, $routePath)
    {
        $module     = strtolower($module);
        $controller = strtolower($controller);
        $action     = strtolower($action);
        file_exists($routePath . 'config.php') && $routeMap = require $routePath . 'config.php';
        $routeMap = (array)$routeMap;
        $routeMap = array_change_key_case($routeMap, CASE_LOWER);

        //1.完整匹配
        $baseUriArr   = [];
        $baseUriArr[] = '/' . $module . '/' . $controller . '/' . $action . '/';
        $baseUriArr[] = '/' . $module . '/' . $controller . '/' . $action;
        $baseUriArr[] = $module . '/' . $controller . '/' . $action;
        $baseUriArr[] = $module . '/' . $controller . '/' . $action . '/';

        //2.半匹配
        if ($action == 'index') {
            $baseUriArr[] = $module . '/' . $controller . '/';
            $baseUriArr[] = $module . '/' . $controller;
        }
        if ($controller == 'index' && $action == 'index') {
            $baseUriArr[] = $module . '/';
            $baseUriArr[] = $module;
        }

        if ($module == 'index' && ($controller == 'index' && $action == 'index')) {
            $baseUriArr[] = '/';
        }

        $method = '';
        foreach ($baseUriArr as $baseUri) {
            if (key_exists($baseUri, $routeMap)) {
                $method = $routeMap[$baseUri];
                break;
            }
        }
        in_array($method, $this->allowMethod) || $method = 'any';

        return $method;
    }

    /**
     * 解析uri
     * @return string[]
     */
    public function getUrlRouteInfo()
    {
        $request = container::getContainer()->get('request');
        return $request->getRouteInfo();
    }

    /**
     * 调用未定义的方法
     * @param string $name   方法名
     * @param array  $params 参数
     * @return mixed
     * @throws \rephp\component\container\exceptions\notFoundException
     */
    public function __call($name, $params)
    {

        return container::getContainer()->get('coreRoute')->__call($name, $params);
    }

    /**
     * 调用未定义的静态方法
     * @param string $name   方法名
     * @param array  $params 参数
     * @return mixed
     * @throws \rephp\component\container\exceptions\notFoundException
     */
    public static function __callStatic($name, $params)
    {
        $obj   = container::getContainer()->get('coreRoute');
        $class = get_class($obj);
        return $class::__callStatic($name, $params);
    }
}
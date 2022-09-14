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
        //2.获取路由自定义类型
        $routeConfig = $this->getRouteConfig($module, $controller, $action, $routePath);

        //3.计算路由地址
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
        return container::getContainer()->get('coreRoute')->run($routeUri, $module, $controller, $action, $routeConfig);
    }

    /**
     * 根据路由判断配置信息中设定的支持请求方式
     * @param string $module     模块名字
     * @param string $controller 控制器名字
     * @param string $action     方法名字
     * @param string $routePath  路由目录
     * @return mixed|string
     */
    public function getRouteConfig($module, $controller, $action, $routePath)
    {
        $module              = strtolower($module);
        $controller          = strtolower($controller);
        $action              = strtolower($action);
        $isCli               = defined('CLI_URI');
        $routeConfigFileName = $routePath . ($isCli ? 'console' : 'web') . '.php';
        file_exists($routeConfigFileName) && $routeMap = require $routeConfigFileName;
        $routeMap = (array)$routeMap;
        //追加内置命令行路由
        if ($isCli) {
            $cliSystemRouteList = container::getContainer()->get('config')->get('console.cli_system_route_list');
            $cliSystemRouteList = empty($cliSystemRouteList) ? [] : (array)$cliSystemRouteList;
            $routeMap           = array_merge($routeMap, $cliSystemRouteList);
        }
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

        $routeConfig = ['method' => 'any', 'class' => ''];
        foreach ($baseUriArr as $baseUri) {
            if (key_exists($baseUri, $routeMap)) {
                $routeConfig = $routeMap[$baseUri];
                break;
            }
        }
        //整理校验
        isset($routeConfig['class']) || $routeConfig['class'] = '';
        if ($isCli) {
            $routeConfig['method'] = 'get';
        } else {
            isset($routeConfig['method']) || $routeConfig['method'] = 'any';
            in_array($routeConfig['method'], $this->allowMethod) || $routeConfig['method'] = 'any';
        }

        return $routeConfig;
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

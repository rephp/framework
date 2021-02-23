<?php

namespace rephp\framework\component\route;

use rephp\framework\component\container\container;
use rephp\framework\component\route\interfaces\routeInterface;

/**
 * 路由中间层
 * @package rephp\framework\component\route
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
     * @param string         $routePath
     * @param routeInterface $bootstrap
     * @return bool
     */
    public function run($routePath, routeInterface $bootstrap)
    {
        //1.分析url
        $params     = $this->getUrlRouteInfo();
        $module     = $params['module'];
        $controller = $params['controller'];
        $action     = $params['action'];
        //2.生成路由字符串
        $baseUri  = $module . '/' . $controller . '/' . $action;
        $baseUri2 = $baseUri . '/';
        $baseUri3 = str_replace('index/', '', $baseUri2);
        //3.根据现有路由，获得路由配置表配置的方法。如没设置则默认为get方法才可以请求当前路由。
        //todo:路由表以后缓存到内存中
        file_exists($routePath . 'route.php') && $routeMap = require $routePath . 'route.php';
        $routeMap = (array)$routeMap;
        empty($routeMap[$baseUri3]) || $method = $routeMap[$baseUri3];
        empty($routeMap[$baseUri]) || $method = $routeMap[$baseUri];
        empty($routeMap[$baseUri2]) || $method = $routeMap[$baseUri2];
        $method = strtolower($method);
        in_array($method, $this->allowMethod) || $method = 'get';

        //4.计算路由地址
        $module2     = strtolower($module);
        $controller2 = strtolower($controller);
        $action2     = strtolower($action);
        $routeUri = '/';
        if( !($module2 == 'index' && ($controller2 == 'index' && $action2 == 'index')) ){
            $routeUri = $module.'/';
            if(!($controller2 == 'index' && $action2 == 'index')){
                $routeUri .= $controller.'/';
                $action2 == 'index' || $routeUri .= $action.'/';
            }
        }

        //5.挂载路由
        return $bootstrap->run($routeUri, $module, $controller, $action, $method);
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
}
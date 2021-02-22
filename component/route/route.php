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
        $params     = $this->parseUrlParams();
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

        //4.挂载路由
        $bootstrap->run($baseUri, $module, $controller, $action, $method);
    }

    /**
     * 过滤路由地址,只保留数字、字母、下划线
     * @param string $name 节点名字
     * @return string
     */
    public function filter($name)
    {
        $pattern = '/[a-zA-Z0-9_]/u';
        preg_match_all($pattern, $name, $result);
        $res = implode('', $result[0]);

        return $res;
    }

    /**
     * 解析uri
     * @return string[]
     */
    public function parseUrlParams()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        //判断basename
        $baseName   = basename($uri);
        $staticFix  = config('web.page_fix', '.html');
        $currentFix = strtolower(strrchr($baseName, '.'));
        $isStaticMode = ($currentFix == $staticFix) ? true : false;
        $isStaticMode && $uri = dirname($uri);
        $arr        = explode('/', $uri);
        $module     = empty($arr[1]) ? 'index' : $this->filter($arr[1]);
        $controller = empty($arr[2]) ? 'index' : $this->filter($arr[2]);
        $action     = empty($arr[3]) ? 'index' : $this->filter($arr[3]);
        //加载配置项
        $config = container::getContainer()->get('config');
        $config->set('route', 'module', $module);
        $config->set('route', 'controller', $controller);
        $config->set('route', 'action', $action);
        $isStaticMode && container::getContainer()->get('request')->parseStaticParams($baseName);

        $res = [
            'module'     => $module,
            'controller' => $controller,
            'action'     => $action,
        ];

        return $res;
    }
}
<?php

namespace rephp\component\route\interfaces;

/**
 * 路由接口类
 */
interface routeInterface
{

    /**
     * 根据当前url动态生成路由
     * @param string $routeUri   基本uri，如{$modeule}/{$controller}/{$action}
     * @param string $module     模块名
     * @param string $controller 控制器名字
     * @param string $action     方法名
     * @param string $method     请求方式
     * @return boolean
     */
    public function run($routeUri, $module, $controller, $action, $method = 'get');

    /**
     * 调用未定义的方法
     * @param string $name   方法名
     * @param array  $params 参数
     * @return mixed
     * @throws \rephp\framework\component\container\exceptions\notFoundException
     */
    public function __call($name, $params);

    /**
     * 调用未定义的静态方法
     * @param string $name   方法名
     * @param array  $params 参数
     * @return mixed
     * @throws \rephp\framework\component\container\exceptions\notFoundException
     */
    public static function __callStatic($name, $params);

}
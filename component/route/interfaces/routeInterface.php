<?php

namespace rephp\framework\component\route\interfaces;

/**
 * 路由驱动类
 */
interface routeInterface
{

    /**
     * 根据当前url动态生成路由
     * @param string $baseUri    基本uri，如{$modeule}/{$controller}/{$action}
     * @param string $module     模块名
     * @param string $controller 控制器名字
     * @param string $action     方法名
     * @param string $method     请求方式
     * @return boolean
     */
    public function run($baseUri, $module, $controller, $action, $method='get');

}
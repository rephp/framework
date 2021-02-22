<?php

namespace rephp\framework\core\bootstrap;

use rephp\framework\component\config\interfaces\configInterface;;
use rephp\framework\component\route\interfaces\routeInterface;

/**
 * app驱动类
 * @package rephp\framework\components\interfaces
 */
interface appBootstrap
{
    /**
     * 开始加载环境所需资源
     * @param string          $appPath 系统默认app路径
     * @param configInterface $config  配置对象
     * @return boolean
     */
    public function init($appPath);

    /**
     * 执行
     * @param routeInterface $route  路由
     * @return boolean
     */
    public function run(routeInterface $route);

    /**
     * 设置app路径
     * @param string $appPath 系统默认app路径
     * @return boolean
     */
    public function setAppPath($appPath);

    /**
     * 获取app路径
     * @return string
     */
    public function getAppPath();

}
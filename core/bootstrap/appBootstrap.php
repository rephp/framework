<?php

namespace rephp\framework\core\bootstrap;

use rephp\framework\component\config\bootstrap\configBootstrap;

/**
 * app驱动类
 * @package rephp\framework\components\bootstrap
 */
interface appBootstrap
{

    /**
     * 开始加载环境所需资源
     * @param string          $appPath 系统默认app路径
     * @param configBootstrap $config  配置文件
     * @return boolean
     */
    public function init($appPath, configBootstrap $config);

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
<?php

namespace rephp\framework\core;

use rephp\framework\core\bootstrap\appBootstrap;

/**
 * app核心驱动类，负责调度系统所需基本资源
 * @package rephp\framework\components\bootstrap
 */
class appCore implements appBootstrap
{
    /**
     * app路径
     */
    public static $appPath;

    /**
     * 开始驱动
     * @param string $appPath 系统默认app路径
     * @return boolean
     */
    public function init($appPath = '')
    {

        //todo: all of app bootstrap etc.
        $this->setAppPath($appPath);
        $this->loadConfig();
        //self::initDebug();
        //self::handleSystemLog();

        return true;
    }

    /**
     * 设置app路径
     * @param string $appPath 系统默认app路径
     * @return boolean
     */
    public function setAppPath($appPath = '')
    {
        $envAppPath    = env('APP_PATH');
        $appPath       = empty($envAppPath) ? $appPath : $envAppPath;
        defined('APP_PATH')  || define('APP_PATH', $appPath);
        defined('ROOT_PATH') || define('ROOT_PATH', dirname($appPath) . '/');
        self::$appPath = $appPath;

        return true;
    }

    /**
     * 获取app路径
     * @return string
     */
    public function getAppPath()
    {
        return self::$appPath;
    }

}
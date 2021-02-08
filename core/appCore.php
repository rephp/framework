<?php
namespace rephp\framework\core;

use rephp\framework\components\bootstrap\appBootstrap;

/**
 * app驱动类
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
     * @param  string  $appPath 系统默认app路径
     * @return boolean
     */
    public function init($appPath='')
    {
        //todo: all of app bootstrap etc.
        self::setAppPath($appPath);
        //self::loadConfig();
        //self::initDebug();
        //self::handleSystemLog();

        return true;
    }

    /**
     * 设置app路径
     * @param string $appPath  系统默认app路径
     * @return boolean
     */
    public function setAppPath($appPath)
    {
        //$appPath = empty($envAppPath) ? $appPath : env('APP_PATH');
        defined('APP_PATH')  || define('APP_PATH', $appPath);
        defined('ROOT_PATH') || define('ROOT_PATH', dirname($appPath).'/');
echo APP_PATH;
var_dump(ROOT_PATH);
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
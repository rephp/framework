<?php
namespace rephp\framework\components\bootstrap;

/**
 * app驱动类
 * @package rephp\framework\components\bootstrap
 */
class appBootstrap
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
    public static function start($appPath='')
    {
        self::setAppPath($appPath);
        //todo: all of app bootstrap etc.
        return true;
    }

    /**
     * 设置app路径
     * @param string $appPath  系统默认app路径
     * @return boolean
     */
    private static function setAppPath($appPath)
    {
        self::$appPath = empty($envAppPath) ? $appPath : env('APP_PATH');
        return true;
    }

    /**
     * 获取app路径
     * @return string
     */
    public static function getAppPath()
    {
        return self::$appPath;
    }

}
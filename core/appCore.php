<?php

namespace rephp\core;

use rephp\component\container\container;
use rephp\component\debug\debug;
use rephp\component\route\route;
use rephp\core\interfaces\appCoreInterface;

/**
 * app核心驱动类，负责调度系统所需基本资源
 * @package rephp\components\interfaces
 */
class appCore implements appCoreInterface
{
    /**
     * @var string app路径
     */
    public static $appPath;

    /**
     * 开始驱动
     * @param string $appPath 系统默认app路径
     * @return boolean
     */
    public function __construct($appPath = '')
    {
        //配置app路径
        $this->setAppPath($appPath);
        //define
        $this->definePath();
        //初始化时区
        $this->setTimeZone();

        return true;
    }

    /**
     * 执行
     */
    public function run()
    {
        //加载debug
        container::getContainer()->bind('debug', debug::class);
        //加载路由
        $routePath = ROOT_PATH . 'route/';
        container::getContainer()->bind('route', route::class)->start($routePath);
    }


    /**
     * 初始化时区
     * 时区设定工作责任人就绪
     */
    public function setTimeZone()
    {
        $timeZone = config('config.time_zone', 'PRC');
        date_default_timezone_set($timeZone);
    }

    /**
     * 定义路径常量
     * 系统常量责任人就绪
     * @return void
     */
    public function definePath()
    {
        $appPath = $this->getAppPath();
        defined('APP_PATH')  || define('APP_PATH', $appPath);
        defined('ROOT_PATH') || define('ROOT_PATH', dirname($appPath) . '/');
    }

    /**
     * 设置app路径
     * @param string $appPath 系统默认app路径
     * @return boolean
     */
    public function setAppPath($appPath = '')
    {
        $envAppPath    = env('APP_PATH');
        self::$appPath = empty($envAppPath) ? $appPath : $envAppPath;

        return self::$appPath;
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
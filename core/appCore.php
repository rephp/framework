<?php

namespace rephp\framework\core;

use rephp\framework\component\config\config;
use rephp\framework\component\container\container;
use rephp\framework\component\debug\debug;
use rephp\framework\component\route\route;
use rephp\framework\core\interfaces\appCoreInterface;

/**
 * app核心驱动类，负责调度系统所需基本资源
 * 核心环境初始化任务承包团队
 * @package rephp\framework\components\interfaces
 */
class appCore implements appCoreInterface
{
    /**
     * @var string app路径
     */
    public static $appPath;
    /**
     * @var string 配置文件所在目录
     */
    public static $configPath;

    /**
     * 开始驱动
     * 核心工作承包团队队长，职责：本团队任务调度
     * 空降不定职位的后勤人员，内驻插入到团队内接管所有后勤工作
     * @param string $appPath 系统默认app路径
     * @return boolean
     */
    public function __construct($appPath = '')
    {
        //配置config路径
        $this->setConfigPath(dirname($appPath).'/config/');
        //配置app路径
        $this->setAppPath($appPath);


        return true;
    }

    /**
     * 执行
     */
    public function run()
    {
        //define
        $this->definePath();
        //初始化时区
        $this->setTimeZone();
        //加载debug
        container::getContainer()->bind('debug', debug::class);
        //加载路由
        $routePath = ROOT_PATH . 'route/';
        container::getContainer()->bind('route', route::class)->run($routePath);
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
     * 设置config目录
     * @return boolean
     */
    public function setConfigPath($configPath)
    {
        //读取ini配置，如果ini没配置则设置为默认路径
        $iniConfigPath = env('CONFIG.CONFIG_PATH');
        empty($iniConfigPath) || $configPath = $iniConfigPath;
        //判断末尾是否含有/
        $checkStr = substr($configPath, -1);
        in_array($checkStr, ['/', '\\']) || $configPath .= '/';
        //设置config所在目录
        self::$configPath = $configPath;

        return  true;
    }

    /**
     * 获取config目录
     * @return string
     */
    public function getConfigPath()
    {
        return self::$configPath;
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
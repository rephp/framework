<?php

namespace rephp\framework\core;

use rephp\framework\component\container\container;
use rephp\framework\component\debug\debug;
use rephp\framework\component\route\env;
use rephp\framework\core\interfaces\appCoreInterface;

/**
 * app核心驱动类，负责调度系统所需基本资源
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
        //define
        $this->definePath();
        //初始化时区
        $this->setTimeZone();
        //加载debug
        container::getContainer()->bind('debug', debug::class);

        return true;
    }

    /**
     * 执行
     */
    public function run()
    {
        //加载路由
        $routePath = ROOT_PATH . 'env/';
        container::getContainer()->bind('env', env::class)->run($routePath);
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
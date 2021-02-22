<?php

namespace rephp\framework\core;

use rephp\framework\component\config\config;
use rephp\framework\component\config\interfaces\configInterface;
use rephp\framework\component\container\container;
use rephp\framework\component\route\interfaces\routeInterface;
use rephp\framework\component\route\route;
use rephp\framework\core\bootstrap\appBootstrap;

/**
 * app核心驱动类，负责调度系统所需基本资源
 * 核心环境初始化任务承包团队
 * @package rephp\framework\components\interfaces
 */
class appCore implements appBootstrap
{
    /**
     * app路径
     * 团队临时仓库
     */
    public static $appPath;

    /**
     * 开始驱动
     * 核心工作承包团队队长，职责：本团队任务调度
     * 空降不定职位的后勤人员，内驻插入到团队内接管所有后勤工作
     * @param string $appPath 系统默认app路径
     * @return boolean
     */
    public function init($appPath = '')
    {
        $this->loadConfig();
        //todo: all of app interfaces etc.
        //配置app路径
        $this->setAppPath($appPath);
        //定义路径常量
        $this->definePath();
        //设置配置项所在目录
        $this->setConfigPath();
        //初始化时区
        $this->setTimeZone();
        //debug
        $this->initDebug();

        return true;
    }

    /**
     * 加载配置对象
     * @param configInterface $config 具体配置对象
     */
    public function loadConfig()
    {
        $this->config = container::getContainer()->bind('config', config::class);
        $this->config->init(container::getContainer()->get('coreConfig'));
    }

    /**
     * 执行
     */
    public function run(routeInterface $route)
    {
        //加载路由
        $routePath = $this->getAppPath() . 'route/';
        $coreRoute = container::getContainer()->bind('route', route::class);
        $coreRoute->run($routePath, container::getContainer()->get('coreRoute'));
    }

    /**
     * 初始化bug设置
     * @return boolean
     */
    public function initDebug()
    {
        $isDebug = $this->config->get('config.debug.is_debug', false);
        if ($isDebug) {
            $res = $this->setOpenDebug();
        } else {
            $res = $this->setCloseDebug();
        }

        return $res;
    }

    /**
     * 开启debug模式配置
     * @return bool
     */
    private function setOpenDebug()
    {
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 'On');
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        $whoops->register();

        return true;
    }

    /**
     * 关闭debug模式配置
     * @return bool
     */
    private function setCloseDebug()
    {
        error_reporting(0);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('log_errors_max_len', 1024);
        //日志位置
        $logPath = $this->config->get('config.debug.log_path', $this->getAppPath() . 'runtime/log/');
        in_array(substr($logPath, -1), ['/', '\\']) || $logPath .= '/';
        $logFileName = $logPath . date('Y/m/d', time()) . '.log';
        mkdir($logPath . date('Y/m/', time()), 0777, true);
        ini_set('error_log', $logFileName);

        return true;
    }

    /**
     * 初始化时区
     * 时区设定工作责任人就绪
     */
    public function setTimeZone()
    {
        $timeZone = $this->config->get('config.time_zone', 'PRC');
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
        defined('APP_PATH') || define('APP_PATH', $appPath);
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

    /**
     * 设置config目录
     * @return boolean
     */
    public function setConfigPath()
    {
        //读取ini配置，如果ini没配置则设置为默认路径
        $iniConfigPath = env('CONFIG.CONFIG_PATH');
        $configPath    = empty($iniConfigPath) ? ($this->getAppPath() . 'config/') : $iniConfigPath;
        //判断末尾是否含有/
        $checkStr = substr($configPath, -1);
        in_array($checkStr, ['/', '\\']) || $configPath .= '/';

        //设置config所在目录
        return $this->config->setConfigPath($configPath);
    }

}
<?php

namespace rephp\framework\core;

use rephp\framework\core\bootstrap\appBootstrap;
use rephp\framework\component\config\bootstrap\configBootstrap;

/**
 * app核心驱动类，负责调度系统所需基本资源
 * 核心环境初始化任务承包团队
 * @package rephp\framework\components\bootstrap
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
    public function init($appPath = '', configBootstrap $config)
    {
        empty($config) || $this->config = $config;
        //todo: all of app bootstrap etc.
        //配置app路径
        $newAppPath = $this->setAppPath($appPath);
        //定义路径常量
        $this->definePath($newAppPath);
        //加载核心配置项
        $this->config->load($newAppPath . 'config/config.php');
        //初始化时区
        $this->setTimeZone();

        return true;
    }

    /**
     * 初始化bug设置
     * bug工作责任人就绪
     */
    public function initDebug()
    {

    }

    /**
     * 初始化时区
     * 时区设定工作责任人就绪
     */
    public function setTimeZone()
    {
        $timeZone = $this->config->get('time_zone', 'PRC');
        date_default_timezone_set($timeZone);
    }

    /**
     * 定义路径常量
     * 系统常量责任人就绪
     * @param string $appPath app运行目录
     * @return void
     */
    public function definePath($appPath)
    {
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

}
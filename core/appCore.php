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
        $this->setAppPath($appPath);
        //定义路径常量
        $this->definePath();
        //设置配置项所在目录
        $this->setConfigPath();
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

    /**
     * 设置config目录
     * @return boolean
     */
    public function setConfigPath()
    {
        //读取ini配置，如果ini没配置则设置为默认路径
        $iniConfigPath  = env('CONFIG_PATH');
        $configPath  = empty($iniConfigPath) ? ($this->getAppPath().'config/') : $iniConfigPath;
        //判断末尾是否含有/
        $checkStr = substr($configPath, -1);
        in_array($checkStr, ['/', '\\']) || $configPath .= '/';

        //设置config所在目录
        return $this->config->setConfigPath($configPath);
    }

}
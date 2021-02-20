<?php

namespace rephp\framework\component\config;

use rephp\framework\component\config\bootstrap\configBootstrap;

/**
 * 配置管理类
 * @package rephp\framework\component\config
 */
class config implements configBootstrap
{
    public $configer;

    /**
     * 初始化运行环境
     */
    public function __construct(configBootstrap $configer)
    {
        $this->configer = $configer;
    }

    /**
     * 设置配置目录
     * @param  string  $configPath  配置目录
     * @return mixed
     */
    public function setConfigPath($configPath)
    {
        return $this->configer->setConfigPath($configPath);
    }
    /**
     * 获取配置目录
     * @return string
     */
    public function getConfigPath()
    {
        return $this->configer->getConfigPath();
    }

    /**
     * 加载一个配置文件到config对象中
     * @param  string $baseName 配置文件基本名字(不含路径),如config.php或者config
     * @return boolean
     */
    public function load($baseName)
    {
        return $this->configer->load($baseName);
    }

    /**
     * 获取一个配置项内容，可动态加载文件
     * @param string $name    配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public function get($name, $default = '')
    {
        return $this->configer->get($name, $default = '');
    }


}
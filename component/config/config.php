<?php

namespace rephp\framework\component\config;

use rephp\framework\component\config\interfaces\configInterface;
use rephp\framework\component\container\container;

/**
 * 配置管理类
 * @package rephp\framework\component\config
 */
class config implements configInterface
{
    /**
     * @var 配置目录
     */
    public $configPath;

    /**
     * 获取核心配置对象
     * @return object
     * @throws \rephp\framework\component\container\exceptions\notFoundException
     */
    public function getCoreConfig()
    {
        return container::getContainer()->get('coreConfig');
    }

    /**
     * 设置配置目录
     * @param  string  $configPath  配置目录
     * @return mixed
     */
    public function setConfigPath($configPath)
    {
        return $this->configPath = $configPath;
    }
    /**
     * 获取配置目录
     * @return string
     */
    public function getConfigPath()
    {
        return $this->configPath;
    }

    /**
     * 加载一个配置文件到config对象中
     * @param  string $baseName 配置文件基本名字(不含路径),如config.php或者config
     * @return boolean
     */
    public function load($baseName)
    {
        return $this->getCoreConfig()->load($baseName);
    }

    /**
     * 获取一个配置项内容，可动态加载文件
     * @param string $name    配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public function get($name, $default = '')
    {
        return $this->getCoreConfig()->get($name, $default);
    }

    /**
     * 动态将一个配置信息加载到config对象中
     * @param string $baseName 基本文件名
     * @param string $name     配置项key
     * @param string $value    配置项对应值
     * @return boolean
     */
    public function set($baseName, $name, $value = '')
    {
        return $this->getCoreConfig()->set($baseName, $name, $value);
    }

}
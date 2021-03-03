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
     * @var string 配置文件后缀名(带.)
     */
    private $fix = '.php';

    /**
     * 初始化
     * @param $configPath
     */
    public function __construct($configPath)
    {
        $this->setConfigPath($configPath);
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
        $this->configPath = $configPath;

        return  true;
    }

    /**
     * 加载一个配置文件到config对象中
     * @param string $baseName 配置文件名字
     * @return boolean
     */
    public function load($baseName)
    {
        try {
            $baseName       = basename($baseName, $this->fix);
            $configFullName = $this->configPath . $baseName . $this->fix;
            $config         = include $configFullName;

            return $this->getCoreConfig()->batchSet($baseName, (array)$config);
        } catch (\Error $e) {
            throw new \ErrorException('加载配置文件:' . $configFullName . '错误,原因:' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('加载配置文件:' . $configFullName . '失败,原因:' . $e->getMessage());
        }
    }

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
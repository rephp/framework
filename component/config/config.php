<?php

namespace rephp\framework\component\config;

use rephp\framework\component\config\bootstrap\configBootstrap;

/**
 * 配置管理类
 * @package rephp\framework\component\config
 */
class config implements configBootstrap
{
    /**
     * @var array 配置项
     */
    private $config = [];
    /**
     * @var string  配置文件所在目录
     */
    private $configPath;
    /**
     * @var string 配置文件后缀名(带.)
     */
    private $fix = '.php';

    /**
     * 设置配置目录
     * @param string $configPath 配置目录
     * @return mixed
     */
    public function setConfigPath($configPath)
    {
        if (empty($this->configPath)) {
            return $this->configPath = $configPath;
        }
        return true;
    }

    /**
     * 获取配置目录
     * @return string
     * @throws \Exception
     */
    public function getConfigPath()
    {
        if (empty($this->configPath)) {
            throw new \Exception('配置所在目录未设定');
        }
        return $this->configPath;
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
            $configFullName = $this->getConfigPath() . $baseName . $this->fix;
            $config         = include $configFullName;

            return $this->batchSet($baseName, (array)$config);
        } catch (\Error $e) {
            throw new \ErrorException('加载配置文件:' . $configFullName . '错误,原因:' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('加载配置文件:' . $configFullName . '失败,原因:' . $e->getMessage());
        }
    }

    /**
     * 获取一个配置项内容，可动态加载文件
     * @param string $name    配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public function get($name, $default = '', $reload = false)
    {
        $params = explode('.', $name);
        if (empty($params)) {
            return $this->config;
        }
        //获取配置信息
        $baseName = array_shift($params);
        //从未加载过的需要动态加载
        isset($this->config[$baseName]) || $reload = true;
        //强制重新加载
        $reload && $this->load($baseName);
        //获取配置结果
        $config = (array)$this->config[$baseName];
        foreach ($params as $val) {
            if (isset($config[$val])) {
                $config = $config[$val];
            } else {
                $config = $default;
                break;
            }
        }

        return $config;
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
        $this->config[$baseName][$name] = $value;

        return true;
    }

    /**
     * 动态将一维数组配置项批量加载到config对象中
     * @param string $baseName   基本文件名
     * @param array  $configList 一维数组,配置项
     * @return boolean
     */
    public function batchSet($baseName, array $configList)
    {
        foreach ($configList as $name => $value) {
            $this->set($baseName, $name, $value);
        }

        return true;
    }

    /**
     * 判断配置项是否存在
     * @param string $name 配置参数名（支持多级配置 .号分割）
     * @return bool
     */
    public function has(string $name)
    {
        $params   = explode('.', $name);
        $baseName = array_shift($params);
        $config   = (array)$this->config[$baseName];
        //判断配置是否存在
        $result = true;
        foreach ($params as $value) {
            if (isset($config[$value])) {
                $config = $config[$value];
            } else {
                $result = false;
                break;
            }
        }

        return $result;
    }

}
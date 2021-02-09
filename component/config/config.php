<?php
namespace rephp\framework\component\config;

use rephp\framework\component\config\bootstrap\configBootstrap;

/**
 * 配置管理类
 * config分为两种： 常驻内存的配置和动态加载的配置
 * 常驻内存的配置的本质是预动态加载核心配置文件
 * @package rephp\framework\component\config
 */
class config implements configBootstrap
{
    /**
     * 加载一个配置文件到config对象中
     * @param  string $fileName 配置文件名字
     * @return boolean
     */
    public static function load($fileName)
    {

    }

    /**
     * 获取一个配置项内容，可动态加载文件
     * @param string $name    配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public static function get($name, $default = '')
    {

    }

    /**
     * 动态将一个配置信息加载到config对象中
     * @param string $name  配置项key
     * @param string $value 配置项对应值
     * @return boolean
     */
    public static function set($name, $value = '')
    {

    }

    /**
     * 动态将一维数组配置项批量加载到config对象中
     * @param array $configList 一维数组,配置项
     * @return boolean
     */
    public static function batchSet(array $configList)
    {

    }

    /**
     * 判断配置项是否存在
     * @param string $name 配置参数名（支持多级配置 .号分割）
     * @return bool
     */
    public static function has(string $name)
    {

    }

}
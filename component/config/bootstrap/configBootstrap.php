<?php

namespace rephp\framework\component\config\bootstrap;

/**
 * 配置驱动类
 * @package rephp\framework\component\config\bootstrap
 */
interface configBootstrap
{
    /**
     * 加载一个配置文件到config对象中
     * @param  string $fileName 配置文件名字
     * @return boolean
     */
    public static function load($fileName);

    /**
     * 获取一个配置项内容，可动态加载文件
     * @param string $name    配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public static function get($name, $default = '');

}
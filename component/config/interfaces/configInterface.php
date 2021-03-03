<?php

namespace rephp\framework\component\config\interfaces;

/**
 * 配置接口
 * @package rephp\framework\component\config\interfaces
 */
interface configInterface
{
    /**
     * 获取一个配置项内容，可动态加载文件
     * @param string $name    配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public function get($name, $default = '');

    /**
     * 动态将一个配置信息加载到config对象中
     * @param string $baseName 基本文件名
     * @param string $name     配置项key
     * @param string $value    配置项对应值
     * @return boolean
     */
    public function set($baseName, $name, $value = '');

}
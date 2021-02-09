<?php

namespace rephp\framework\component\config\bootstrap;

/**
 * 配置驱动类
 * @package rephp\framework\component\config\bootstrap
 */
interface configBootstrap
{
    public function load($fileName);
    public static function get($name, $default = '')

}
<?php

namespace rephp\component\env\bootstrap;

use MillionMile\GetEnv\Env;
use rephp\component\env\interfaces\envInterface;

/**
 *  millionMile的env类
 * 依赖环境为：php 7.1
 * @package rephp\component\env\bootstrap
 */
final class millionMileEnv implements envInterface
{
    /**
     * 获取env配置信息
     * @param string $name    env配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public function get($name, $default = '')
    {
        return Env::get($name, $default);
    }

    /**
     * 加载配置文件
     * @param  string $fileName env文件绝对路径及名字
     * @return boolean
     * @throws \Exception
     */
    public function loadFile($fileName)
    {
        return Env::loadFile($fileName);
    }

}
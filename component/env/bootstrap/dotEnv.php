<?php

namespace rephp\framework\component\env\bootstrap;

use rephp\framework\component\container\container;
use rephp\framework\component\env\interfaces\envInterface;

/**
 *  dotenv的封装类
 * 依赖环境为：php 7.0
 * @package rephp\framework\component\env\bootstrap
 */
final class dotEnv implements envInterface
{
    /**
     * 获取env配置信息
     * @param string $name    env配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public function get($name, $default = '')
    {
        $params = explode('.', $name);
        if (empty($params)) {
            return $_ENV;
        }
        //获取配置结果
        $params = (array)$params;
        $env = $_ENV;
        foreach ($params as $val) {
            if (isset($env[$val])) {
                $env = $env[$val];
            } else {
                $env = $default;
                break;
            }
        }
        //处理兼容0却default替换空
        if (empty($env)) {
            $length = strlen($env);
            $length > 0 || $config = $default;
        }

        return $env;
    }

    /**
     * 加载配置文件
     * @param  string $filePath 配置文件路径
     * @return boolean
     * @throws \Exception
     */
    public function loadFile($filePath)
    {
        return \Dotenv\Dotenv::createImmutable(dirname($filePath))->load();;
    }

}
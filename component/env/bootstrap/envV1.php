<?php

namespace rephp\component\env\bootstrap;

use rephp\component\container\container;
use rephp\component\env\interfaces\envInterface;

/**
 *  v1的env封装类
 * 依赖环境为：php 7.0
 * @package rephp\component\env\bootstrap
 */
final class envV1 implements envInterface
{
    /**
     * @var array env配置信息
     */
    private $config = [];

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
            return $this->config;
        }
        //获取配置结果
        $params = (array)$params;
        $env    = $this->config;
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
     * @param string $fileName env文件完整路径
     * @return boolean
     * @throws \Exception
     */
    public function loadFile($fileName)
    {
        $res       = false;
        $fileExist = file_exists($fileName);
        if ($fileExist) {
            $this->config = (array)parse_ini_file($fileName, true);
            $res          = true;
        }

        return $res;
    }

}
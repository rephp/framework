<?php

use rephp\framework\component\container\container;

/**
 * 便捷打印输出
 * @param mixed $params 要打印的数据
 * @return void
 */
function dump(...$params)
{
    var_dump($params);
}

/**
 * 获取env配置信息
 * @param string $name    env配置项名字
 * @param string $default 默认值
 * @return mixed
 */
function env($name, $default='')
{
    return MillionMile\GetEnv\Env::get($name, $default);
}

/**
 * 获取一个配置项内容，可动态加载文件
 * @param string $name    配置项名字
 * @param string $default 默认值
 * @return mixed
 */
function config($params, $default=null)
{
    try{
        return container::getContainer()->get('config')->get($params, $default);
    }catch (\Error $e) {
        throw  new \ErrorException('获取配置出错:'.$e->getMessage());
    }catch (\Exception $e){
        throw  new \Exception('获取配置失败:'.$e->getMessage());
    }
}


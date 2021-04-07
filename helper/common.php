<?php

use rephp\component\container\container;

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
    return container::getContainer()->get('env')->get($name, $default);
}

/**
 * 获取一个配置项内容，可动态加载文件
 * @param string $name    配置项名字
 * @param string $default 默认值
 * @return mixed
 */
function config($params, $default=null)
{
    return container::getContainer()->get('config')->get($params, $default);
}

/**
 * 判断字符串是否为正确的邮箱格式
 * @param  string $email  需要判断的邮箱地址字符串
 * @return boolean(其实正确是返回邮箱地址，不正确时返回false,我们可以认为它是boolean)
 */
function isEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

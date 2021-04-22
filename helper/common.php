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

/**
 * 生成url
 * @param string $url    url地址，如/test/tedd/?id=5,也可以带域名
 * @param array  $params 附加参数如['cate_id'=>5, 'type'=>'test']
 * @param string $domain 要替换的主域名
 * @return string
 */
function makeUrl($url, $params = [], $domain='')
{
    //计算要替换的主域名
    if(!empty($domain)){
        substr($domain, -1)=='/' ||  $domain .= '/';
        //解析原域名
        $parseUrlResult = parse_url($url);
        substr($parseUrlResult['path'], 0, 1)=='/' && $parseUrlResult['path'] = substr($parseUrlResult['path'], 1);
        $url = $domain.$parseUrlResult['path'];
        empty($parseUrlResult['query']) || $url .= '?'.$parseUrlResult['query'];
    }

    //获取baseurl,不含主文件名（如test.php）
    $parseUrlResult = pathinfo($url);
    $baseUrl        = $parseUrlResult['dirname'];

    //获取url中的get参数
    $query = parse_url($url, PHP_URL_QUERY);
    parse_str($query, $query);

    //合并数据
    if (empty($query)) {
        $param_list = $params;
    } else {
        $param_list = empty($params) ? $query : array_merge($query, $params);
    }
    //组装文件名
    $fileName = http_build_query($param_list, null, '-');
    $fileName = str_replace('=', '-', $fileName);
    empty($fileName) || $fileName .= '.html';

    return $baseUrl . '/' . $fileName;
}
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
function env($name, $default = '')
{
    return container::getContainer()->get('env')->get($name, $default);
}

/**
 * 获取一个配置项内容，可动态加载文件
 * @param string $name    配置项名字
 * @param string $default 默认值
 * @return mixed
 */
function config($params, $default = null)
{
    return container::getContainer()->get('config')->get($params, $default);
}

/**
 * 获取当前请求的参数
 * @param string $name    变量名
 * @param mixed  $default 默认值
 * @return mixed
 */
function param($name = '', $default = null)
{
    return container::getContainer()->get('request')->param($name, $default);
}

/**
 * 获取GET参数
 * @param string $name    变量名
 * @param mixed  $default 默认值
 * @return mixed
 */
function get($name = '', $default = null)
{
    return container::getContainer()->get('request')->get($name, $default);
}

/**
 * 获取POST参数
 * @param string $name    变量名
 * @param mixed  $default 默认值
 * @return mixed
 */
function post($name = '', $default = null)
{
    return container::getContainer()->get('request')->post($name, $default);
}

/**
 * 判断字符串是否为正确的邮箱格式
 * @param string $email 需要判断的邮箱地址字符串
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
 * @example makeUrl('http://www.test.com/ddd/index.php?id=55&ite=ddd', ['test' => 'xxx', 'YYY'],
 *                       'http://www.baidu.com');
 */
function makeUrl($url, $params = [], $domain = '')
{
    //计算要替换的主域名
    if (!empty($domain)) {
        substr($domain, -1) == '/' || $domain .= '/';
        //解析原域名
        $parseUrlResult = parse_url($url);
        substr($parseUrlResult['path'], 0, 1) == '/' && $parseUrlResult['path'] = substr($parseUrlResult['path'], 1);
        $url = $domain . $parseUrlResult['path'];
        empty($parseUrlResult['query']) || $url .= '?' . $parseUrlResult['query'];
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

/**
 * 获取html内容中的所有图片路径
 * @param string $html 要匹配的html内容
 * @return string[]
 */
function getImagelist($html)
{
    $match_str = '/<img.+src=(.+)(\>|\'.*\>|\".*\>|\s+\/>)/imUs';
    preg_match_all($match_str, $html, $out, PREG_PATTERN_ORDER);
    $result = str_replace(['"', '\''], '', $out[1]);

    return array_unique($result);
}

/**
 * 将html内容中的所有图片路径替换domain
 * @param string $html      要替换图片域名的html内容
 * @param string $newDomain 新域名
 * @return string
 */
function replaceDomain($html, $newDomain = '')
{
    //移除域名最末尾巴/
    substr($newDomain, -1) == '/' && $newDomain = substr($newDomain, 0, -1);
    $urlList = getimages($html);
    foreach ($urlList as $index => $oldUrl) {
        if (empty($oldUrl)) {
            continue;
        }
        $url     = $oldUrl;
        $tempArr = parse_url($url);
        if (empty($tempArr['host'])) {
            substr($url, 0, 1) == '/' && $url = substr($url, 1);
            $url = $newDomain . '/' . $url;
        } else {
            $url = str_replace($tempArr['scheme'] . '://' . $tempArr['host'], $newDomain, $url);
        }
        $html = str_replace($oldUrl, $url, $html);
    }

    return $html;
}

/**
 * 过滤最右侧指定字符
 * @param string $sourceStr 源字符串
 * @param string $filterFix 要过滤的最右侧字符串
 * @return false|string
 */
function replaceRightStr($sourceStr, $filterFix = '')
{
    if (empty($filterFix)) {
        return $sourceStr;
    }
    $cuteStr = substr($sourceStr, -strlen($filterFix));
    ($filterFix == $cuteStr) && $sourceStr = substr($sourceStr, 0, -strlen($filterFix));
    return $sourceStr;
}

/**
 * 创建日志目录返回文件名
 * @param string $logType  日志类型
 * @return bool|string
 */
function getLogFileName($logType='php')
{
    $logPath = config('config.debug.log_path', ROOT_PATH . 'runtime/log/');
    in_array(substr($logPath, -1), ['/', '\\']) || $logPath .= '/';
    $logFileName = $logPath . $logType . '/' . date('Y/m/d', time()) . '.log';
    $res = is_dir(dirname($logFileName)) ? true : mkdir(dirname($logFileName), 0777, true);

    return $res ? $logFileName : false;
}
<?php

namespace rephp\framework\component\request;

use rephp\framework\component\request\interfaces\requestInterface;

/**
 * 请求类
 * @package rephp\framework\component\request
 */
class request implements requestInterface
{
    /**
     * @var array 头信息
     */
    public $headers = [];
    /**
     * @var array get参数
     */
    public $get = [];
    /**
     * @var array post参数
     */
    public $post = [];
    /**
     * @var array 合并参数
     */
    public $param = [];
    /**
     * @var array server信息
     */
    public $server = [];
    /**
     * @var array 用户put方式输入
     */
    public $input = [];
    /**
     * @var array 文件
     */
    public $file = [];
    /**
     * @var string 模块名字
     */
    public $module = '';
    /**
     * @var string 控制器名字
     */
    public $controller = '';
    /**
     * @var string 方法名
     */
    public $action = '';

    /**
     * 初始化reqeust
     */
    public function init()
    {
        //设置头信息
        $this->headers = $this->getHeader();
        $this->get     = $_GET;
        $this->server  = $_SERVER;
        $this->post    = $_POST;
        $this->input   = file_get_contents('php://input');
        //$this->cookie  = $_COOKIE;
        $this->file = (array)$_FILES;
        //解析路由参数
        $this->parseUrl();

        return true;
    }

    /**
     * 获取请求头信息
     * @return array|false
     */
    public function getHeader()
    {
        if (function_exists('apache_request_headers')) {
            return array_change_key_case(apache_request_headers());
        }
        if (function_exists('getallheaders')) {
            return array_change_key_case(getallheaders());
        }
        if (function_exists('http_get_request_headers')) {
            return array_change_key_case(http_get_request_headers());
        }
        $header = [];
        foreach ($_SERVER as $key => $val) {
            if (strpos($key, 'HTTP_') == 0) {
                $tempStr                      = str_replace('_', '-', substr($key, 5));
                $header[strtolower($tempStr)] = $val;
            }
        }
        empty($_SERVER['CONTENT_LENGTH']) || $header['content-length'] = $_SERVER['CONTENT_LENGTH'];
        empty($_SERVER['CONTENT_TYPE']) || $header['content-type'] = $_SERVER['CONTENT_TYPE'];
        return $header;
    }

    /**
     * 获取当前请求的参数
     * @param string $name    变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function param($name = '', $default = null)
    {
        $tempRes = '_null_';
        $postRes = $this->post($name, $tempRes);

        return ($postRes == $tempRes) ? $this->get($name, $default) : $postRes;
    }

    /**
     * 获取GET参数
     * @param string $name    变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get($name = '', $default = null)
    {
        return isset($this->get[$name]) ? $this->get[$name] : $default;
    }

    /**
     * 获取POST参数
     * @param string $name    变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function post($name = '', $default = null)
    {
        return isset($this->post[$name]) ? $this->post[$name] : $default;
    }

    /**
     * 解析url参数及信息
     * @throws \ErrorException
     * @return boolean
     */
    public function parseUrl()
    {
        $uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        //判断basename
        $baseName   = basename($uri);
        $staticFix  = config('web.page_fix', '.html');
        $currentFix = strtolower(strrchr($baseName, '.'));
        $isStaticMode = ($currentFix == $staticFix) ? true : false;
        $isStaticMode && $uri = dirname($uri);
        $arr        = explode('/', $uri);
        $module     = empty($arr[1]) ? 'index' : $this->filter($arr[1]);
        $controller = empty($arr[2]) ? 'index' : $this->filter($arr[2]);
        $action     = empty($arr[3]) ? 'index' : $this->filter($arr[3]);
        //设置路由信息
        $this->setRouteInfo($module, $controller, $action);
        //解析静态参数
        $isStaticMode && $this->parseStaticParams($baseName);

        return true;
    }

    /**
     * 从请求的uri中解析静态参数
     * @param string $baseName 请求uri中的文件名
     * @return boolean
     */
    public function parseStaticParams($baseName)
    {
        $arr  = pathinfo($baseName);
        $str  = $arr['filename'];
        $arr2 = explode('-', $str);
        $key  = true;
        while (isset($key)) {
            $key = array_shift($arr2);
            $val = array_shift($arr2);
            if (!empty($key)) {
                $this->get[$key] = $val;
            }
        }

        return true;
    }

    /**
     * 设置路由参数
     * @param string $module     模块名
     * @param string $controller 控制器名
     * @param string $action     方法名
     */
    public function setRouteInfo($module = 'index', $controller = 'index', $action = 'index')
    {
        $this->module     = $module;
        $this->controller = $controller;
        $this->action     = $action;
    }

    /**
     * 获取路由信息
     * @return array
     */
    public function getRouteInfo()
    {
        return [
            'module'     => $this->module,
            'controller' => $this->controller,
            'action'     => $this->action,
        ];
    }

}
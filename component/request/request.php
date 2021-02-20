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
    public $get     = [];
    /**
     * @var array post参数
     */
    public $post    = [];
    /**
     * @var array 合并参数
     */
    public $param   = [];
    /**
     * @var array server信息
     */
    public $server  = [];
    /**
     * @var array 用户put方式输入
     */
    public $input   = [];
    /**
     * @var array 文件
     */
    public $file    = [];

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
        $this->file    = (array)$_FILES;
        //todo:解析路由参数

        //todo:合并请求参数到param
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
        return isset($this->param[$name]) ? $this->param[$name] : $default;
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

    public function parseUrl()
    {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

}
<?php

namespace rephp\core;

use rephp\component\container\container;

/**
 * 视图核心类
 * @package rephp\ext
 */
class view
{
    /**
     * @var array 加载到视图层的变量列表
     */
    public static $vars = [];
    /**
     * @var string 布局文件
     */
    public static $layout = 'null';
    /**
     * @var string 视图文件
     */
    public static $forward = '';

    /**
     * 将数据或对象推送到视图层
     * @param string $name
     * @param mixd   $value
     * @return boolean
     */
    public static function set($name, $value = '')
    {
        return self::$vars[$name] = $value;
    }


    /**
     * 完成视图布局
     * 作用：加载变量，加载视图
     * @param string $viewPath       视图文件路径，仅限当前模版内。如index/index.php
     * @param string $layoutFileName 布局文件名
     * @param string $template       模版名称
     * @param array  $realRouteInfo  真正路由信息
     * @return string
     */
    public static function display($layout = '', $forward = '', $realRouteInfo = [])
    {
        $request = container::getContainer()->get('request');
        $isJson  = $request->get('_json');
        if (!empty($isJson)) {
            exit(json_encode(self::$vars));
        }
        //设置视图路由
        $request->setViewRouteInfo($realRouteInfo);
        //更新视图
        empty($forward) || self::$forward = $forward;
        empty($layout) || self::$layout = $layout;
        //加载视图文件
        return self::loadLayout($layout);
    }

    /**
     * 加载布局视图
     * @param string $layout 要加载的基本文件名,如abc.php
     * @throws  rephpException
     */
    public static function loadLayout($layout = '')
    {
        //获取布局视图文件名字
        empty($layout) && $layout = self::$layout;
        substr($layout, -4) == '.php' || $layout .= '.php';
        self::load($layout, 'layout');
    }

    /**
     * 加载主体视图文件
     * @param string $forward 要加载的基本文件名,如abc.php或者  index/index.php
     * @throws  rephpException
     */
    public static function loadTemplate($forward = '')
    {
        $request    = container::getContainer()->get('request');
        $routeInfo  = $request->getViewRouteInfo();
        $module     = $routeInfo['module'];
        $controller = $routeInfo['controller'];
        $action     = $routeInfo['action'];
        //获取主视图文件名字
        if (empty($forward)) {
            $forward = empty(self::$forward) ? $controller . '/' . $action : self::$forward;
        }
        strpos($forward, '/') === false && $forward = $controller . '/' . $forward;
        substr($forward, -4) == '.php' || $forward .= '.php';
        self::load($forward, 'template');
    }

    /**
     * 加载部件视图
     * @param string $fileName 要加载的基本文件名,如abc.php或者  index/index.php
     * @throws  rephpException
     */
    public static function loadPart($fileName = '')
    {
        self::load($fileName, 'part');
    }

    /**
     * 加载视图文件
     * @param string $fileName 要加载的基本文件名,如abc.php或者  index/index.php
     * @param string $loadType 加载的文件类型，如 layout, part, template
     * @throws  rephpException
     */
    public static function load($fileName, $loadType = 'template')
    {
        $request   = container::getContainer()->get('request');
        $routeInfo = $request->getViewRouteInfo();
        $module    = $routeInfo['module'];
        //加载视图变量到布局,组合布局地址
        $fullFileName = APP_PATH . 'modules/' . $module . '/view/' . $loadType . '/' . $fileName;
        file_exists($fullFileName) || $fullFileName = APP_PATH . 'common/view/' . $loadType . '/' . $fileName;
        //如果还不存在则直接抛出错误
        if (!file_exists($fullFileName)) {
            throw new \Exception('视图文件:' . fullFileName . '不存在');
        }
        //加载数据
        extract(self::$vars, EXTR_REFS);
        include($fullFileName);
    }
}

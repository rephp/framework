<?php

namespace rephp\core;

use rephp\component\container\container;

/**
 * controller抽象类
 * @package rephp\ext
 */
abstract class controller
{
    use \rephp\traits\publicTrait;
    /**
     * @var string 布局名字
     */
    public $layout = 'null';
    /**
     * @var string 视图模板名字
     */
    public $forward = '';


    /**
     * 将数据或对象推送到视图层
     * @param string $param 参数名
     * @param mixd   $value 参数值
     * @return boolean
     */
    public function set($param, $value = '')
    {
        return view::set($param, $value);
    }

    /**
     * 输出json
     * @param mixed $data 要输出的数据
     * @return void
     */
    public function json($data)
    {
        exit(json_encode($data, 448));
    }

    /**
     * 输出json结果
     * @param int    $code    状态编码
     * @param string $msg     结果提示语
     * @param array  $extData 扩展数据
     */
    public function result($code = 200, $msg = 'success', $extData = [])
    {
        $this->json(['code' => $code, 'msg' => $msg, 'data' => $extData]);
    }

    /**
     * 加载视图
     * @param string $viewPath       视图文件路径，仅限当前模版内。如index/index.php
     * @param string $layoutFileName 布局文件名 如
     * @return string
     */
    public function display()
    {
        $className = get_called_class();
        //计算模块
        $isCli = defined('CLI_URI');
        if ($isCli) {
            preg_match_all("/\\\\console\\\\(.*)\\\\/iU", $className, $result);
        } else {
            preg_match_all("/\\\\modules\\\\(.*)\\\\controller/i", $className, $result);
        }
        $module = $result[1][0];

        //计算控制器
        $controller = str_replace('\\', '', strrchr($className, '\\controller\\'));
        $controller = replaceRightStr($controller, 'Controller');
        //计算方法名
        $dbt    = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $action = isset($dbt[1]['function']) ? $dbt[1]['function'] : null;
        $action = replaceRightStr($action, 'Action');

        $realRouteInfo = [
            'module'     => $module,
            'controller' => $controller,
            'action'     => $action,
        ];

        return view::display($this->layout, $this->forward, $realRouteInfo);
    }

    /**
     * 跳转
     * @param string $url    要跳转的url
     * @param string $module 模块名如admin或者为空代表前台
     * @return void
     */
    public function redirect($url)
    {
        //如果不是以http开头的，则自动调用重写类,保证是http开头的，避免死循环重定向
        strstr($url, 'http:') || $url = makeUrl($url);
        header('Location:' . $url);
        exit(0);
    }
}

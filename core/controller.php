<?php
namespace rephp\core;

/**
 * controller抽象类
 * @package rephp\ext
 */
abstract class controller
{
    /**
     * @var string 布局名字
     */
    public $layout      = 'null';
    /**
     * @var string 视图模板名字
     */
    public $forward     = '';

    use \rephp\traits\publicTrait;


    /**
     * 将数据或对象推送到视图层
     * @param  string $param   参数名
     * @param  mixd   $value   参数值
     * @return boolean
     */
    public function set($param, $value='')
    {
        //解析module名字
        return view::set($param, $value);
    }

    /**
     * 加载视图
     * @param  string  $viewPath       视图文件路径，仅限当前模版内。如index/index.php
     * @param  string  $layoutFileName 布局文件名 如
     * @return string
     */
    public function display()
    {
        //当前调用的类(具体控制器名字,含命名空间)
        $className = get_called_class();
        //$module = $this->parseModule($className);
        //解析module名字
        return view::display($this->layout, $this->forward, $this->template);
    }

    /**
     * 跳转
     * @param  string $url    要跳转的url
     * @param  string $module 模块名如admin或者为空代表前台
     * @return void
     */
    public function redirect($url)
    {
        $className = get_called_class();
        //如果不是以http开头的，则自动调用重写类,保证是http开头的，避免死循环重定向
        strstr($url, 'http:') || $url = makeUrl($url);
        header('Location:'.$url);
        exit(0);
    }

    /**
     * 从含有命名空间的controller类名里分析module
     * @param  string  $className  有命名空间的controller类名
     * @return string
     */
    private function parseModule($className)
    {
        return $className;
    }

}
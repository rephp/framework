<?php
/**
 * app核心模块
 */

namespace rephp;

use rephp\component\container\container;
use rephp\component\debug\debug;

/**
 * app核心类
 * @package framework
 */
class app
{
  
    /**
     * 运行
     * @param string $appPath app路径
     * @return string
     */
    public function run($appPath)
    {
		//定义路径常量
		$this->definePath($appPath);
		//加载驱动
        require_once 'bootstrap/app.php';
        //初始化时区
        $this->setTimeZone();
        //挂载路由
        container::getContainer()->get('route')->start(ROOT_PATH . 'route/');
    }
	
	/**
     * 定义路径常量
     * 系统常量责任人就绪
	 * @param string $appPath app路径 
     * @return void
     */
    public function definePath($appPath)
    {
        define('APP_PATH', $appPath);
		define('ROOT_PATH', dirname($appPath).'/');
    }

    /**
     * 初始化时区
     * 时区设定工作责任人就绪
     */
    public function setTimeZone()
    {
        $timeZone = config('config.time_zone', 'PRC');
        date_default_timezone_set($timeZone);
    }
	
}

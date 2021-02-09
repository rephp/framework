<?php
/**
 * app核心模块
 */
namespace rephp\framework;

use rephp\framework\component\container\container;

/**
 * app核心类
 * @package framework
 */
class app
{
    public  $container;
    private $rephpConfig = [
        'appCore' => core\appCore::class,
        'reponse' => component\response\response::class,
        'request' => component\request\request::class,
        'config'  => component\config\config::class,
    ];

    /**
     * 初始化预备环境
     * @param string $appPath
     */
    public function __construct()
    {
        $this->container = new container();
    }

    /**
     * 运行
     * @param string $appPath  app路径
     * @return string
     */
    public function run($appPath='')
    {
        //初始化系统运行环境
        $config = $this->container->bind('config', $this->rephpConfig['config']);
        $this->container->bind('appCore', $this->rephpConfig['appCore'])->init($appPath, $config);
        //输入输出
        //执行
        $this->container->bind('reponse', $this->rephpConfig['reponse'])->output();
    }

}
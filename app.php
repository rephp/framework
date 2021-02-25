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
    /**
     * 初始化默认绑定的对象
     * @var string[]
     */
    private $rephpConfig = [
        'appCore' => core\appCore::class,
        'reponse' => component\response\response::class,
        'request' => component\request\request::class,
        'coreConfig'  => component\config\bootstrap\configV1::class,
        'coreRoute'   => component\route\bootstrap\macawRoute::class,
        'coreEvent'   => component\event\bootstrap\eventV1::class,
    ];
    /**
     * @var 容器对象
     * 可以简易理解为：本app类为总工程负责人,调度所有工作。容器是一个包工头。
     */
    private $container;

    /**
     * 初始化预备环境
     * @param string $appPath
     */
    public function __construct()
    {
        $this->container = container::getContainer();
    }

    /**
     * 运行
     * @param string $appPath app路径
     * @return string
     */
    public function run($appPath = '')
    {
        //todo:核心模块注册提取出去
        //初始化系统运行环境
        $this->container->bind('coreConfig', $this->rephpConfig['coreConfig']);
        //运行
        $this->container->bind('appCore', $this->rephpConfig['appCore'], [$appPath]);
        //绑定接受参数对象
        $this->container->bind('request', $this->rephpConfig['request']);
        //绑定输出对象
        $this->container->bind('reponse', $this->rephpConfig['reponse']);
        //绑定路由对象
        $this->container->bind('coreRoute', $this->rephpConfig['coreRoute']);
        //绑定事件
        $this->container->bind('coreEvent', $this->rephpConfig['coreEvent']);
        //运行
        $this->container->get('appCore')->run();
    }

}
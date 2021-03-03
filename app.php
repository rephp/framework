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
     * @var string 配置驱动类
     */
    private $configBootstrap = component\config\bootstrap\configV1::class;

    /**
     * 初始化默认绑定的对象
     * @var string[]
     */
    private $rephpConfig = [
        'reponse'     => component\response\response::class,
        'request'     => component\request\request::class,
        'coreRoute'   => component\route\bootstrap\macawEnv::class,
        'coreEvent'   => component\event\bootstrap\eventV1::class,
        'coreDebug'   => component\debug\bootstrap\debugV1::class,
    ];

    /**
     * 运行
     * @param string $appPath app路径
     * @return string
     */
    public function run($appPath)
    {
        //预加载资源环境
        $this->loadEnv();
        $this->loadConfig(dirname($appPath).'/config/');
        //预先绑定具体组件
        $this->preBindCoreComponent();
        //加载核心类 & 运行
        container::getContainer()->bind('appCore', core\appCore::class, [$appPath])->run();
    }

    public function loadEnv($envPath)
    {
        container::getContainer()->bind('coreEnv', $this->envBootstrap);
        container::getContainer()->bind('env', component\env\env::class, [$envPath]);
    }

    /**
     * 首先需要加载的配置文件
     */
    public function loadConfig($configPath)
    {
        container::getContainer()->bind('coreConfig', $this->configBootstrap);
        container::getContainer()->bind('config', component\config\config::class, [$configPath]);
    }

    /**
     * 预先注册组件
     * 优先级依赖按照从上往下顺序来加载
     * @return bool
     */
    public function preBindCoreComponent()
    {
        foreach($this->rephpConfig as $bindName=>$class){
            container::getContainer()->bind($bindName, $class);
        }

        return true;
    }


}
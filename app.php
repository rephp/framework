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
        'coreConfig'  => component\config\bootstrap\configV1::class,
        'appCore'     => core\appCore::class,
        'reponse'     => component\response\response::class,
        'request'     => component\request\request::class,
        'coreRoute'   => component\route\bootstrap\macawRoute::class,
        'coreEvent'   => component\event\bootstrap\eventV1::class,
    ];
    /**
     * @var 容器对象
     * 可以简易理解为：本app类为总工程负责人,调度所有工作。容器是一个包工头。
     */
    private $container;

    /**
     * 运行
     * @param string $appPath app路径
     * @return string
     */
    public function run($appPath = '')
    {
        //初始化系统运行环境
        $this->container = container::getContainer();
        //设置配置路径
        $this->setConfigPath(dirname($appPath));
        //预先绑定组件
        $this->preBindCoreComponent();
        //运行
        $this->container->get('appCore')->run();
    }

    /**
     * 设置config目录
     * @return boolean
     */
    public function setConfigPath($rootPath)
    {
        //读取ini配置，如果ini没配置则设置为默认路径
        $iniConfigPath = env('CONFIG.CONFIG_PATH');
        $configPath    = empty($iniConfigPath) ? ($rootPath . '/config/') : $iniConfigPath;
        //判断末尾是否含有/
        $checkStr = substr($configPath, -1);
        in_array($checkStr, ['/', '\\']) || $configPath .= '/';

        //设置config所在目录
        return  $this->container->bind('config', component\config\config::class)->setConfigPath($configPath);
    }

    /**
     * 预先注册组件
     * 优先级依赖按照从上往下顺序来加载
     * @return bool
     */
    public function preBindCoreComponent()
    {
        foreach($this->rephpConfig as $bindName=>$class){
            $this->container->bind($bindName, $class);
        }

        return true;
    }


}
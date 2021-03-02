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
        'reponse'     => component\response\response::class,
        'request'     => component\request\request::class,
        'coreRoute'   => component\route\bootstrap\macawRoute::class,
        'coreEvent'   => component\event\bootstrap\eventV1::class,
        'coreDebug'   => component\debug\bootstrap\debugV1::class,
    ];

    /**
     * 运行
     * @param string $appPath app路径
     * @return string
     */
    public function run($appPath = '')
    {
        //加载核心类
        $core  = container::getContainer()->bind('appCore', core\appCore::class, [$appPath]);
        //预先绑定组件
        $this->preBindCoreComponent();
        //运行
        return $core->run();
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
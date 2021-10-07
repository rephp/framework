<?php

namespace rephp\component\debug;

use rephp\component\container\container;
use rephp\component\debug\interfaces\debugInterface;

/**
 * debug类
 */
class debug implements debugInterface
{
    /**
     * 初始化bug设置
     * @return boolean
     */
    public function __construct()
    {
        $isDebug = config('config.debug.is_debug', false);
        $res     = $isDebug ? $this->openDebug() : $this->closeDebug();

        return $res;
    }

    /**
     * 开启debug模式配置
     * @return bool
     */
    public function openDebug()
    {
        if(class_exists('\\rephp\\debugbar\\debugbar')){
            container::getContainer()->bind('debugbar', '\\rephp\\debugbar\\debugbar');
        }

        return container::getContainer()->get('coreDebug')->openDebug();
    }

    /**
     * 关闭debug模式配置
     * @return bool
     */
    public function closeDebug()
    {
        return container::getContainer()->get('coreDebug')->closeDebug();
    }


    /**
     * 输出调试信息
     */
    public function __destruct()
    {
        $isDebug = config('config.debug.is_debug', false);
        if ($isDebug) {
            //1.输出加载信息
            echo container::getContainer()->get('debugbar')->run();
        }
        return true;
    }

}
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
        if ($isDebug) {
            $res = $this->setOpenDebug();
        } else {
            $res = $this->setCloseDebug();
        }

        return $res;
    }

    /**
     * 开启debug模式配置
     * @return bool
     */
    public function setOpenDebug()
    {
        return container::getContainer()->get('coreDebug')->setOpenDebug();
    }

    /**
     * 关闭debug模式配置
     * @return bool
     */
    public function setCloseDebug()
    {
        return container::getContainer()->get('coreDebug')->setOpenDebug();
    }

}
<?php
namespace rephp\framework\component\debug\interfaces;

/**
 * debug接口
 */
interface debugInterface
{
    /**
     * 开启debug模式配置
     * @return bool
     */
    public function setOpenDebug();

    /**
     * 关闭debug模式配置
     * @return bool
     */
    public function setCloseDebug();

}
<?php

namespace rephp\component\debug\interfaces;

/**
 * debug接口
 */
interface debugInterface
{
    /**
     * 开启debug模式配置
     * @return bool
     */
    public function openDebug();

    /**
     * 关闭debug模式配置
     * @return bool
     */
    public function closeDebug();
}

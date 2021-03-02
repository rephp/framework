<?php

namespace rephp\framework\component\config\abstracts;

use rephp\framework\component\container\container;

/**
 * 配置抽象类
 * @package rephp\framework\component\config\abstracts
 */
abstract class configAbstract
{

    /**
     *  获取配置目录
     * @return mixed
     */
    public function getConfigPath()
    {
        return container::getContainer()->get('appCore')->getConfigPath();
    }

}
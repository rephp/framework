<?php

namespace rephp\framework\core\interfaces;

use rephp\framework\component\config\interfaces\configInterface;

/**
 * appCore接口
 * @package rephp\framework\components\interfaces
 */
interface appCoreInterface
{
    /**
     * 执行
     * @return boolean
     */
    public function run();

    /**
     * 设置app路径
     * @param string $appPath 系统默认app路径
     * @return boolean
     */
    public function setAppPath($appPath);

    /**
     * 获取app路径
     * @return string
     */
    public function getAppPath();

}
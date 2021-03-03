<?php

namespace rephp\framework\component\env;

use rephp\framework\component\container\container;
use rephp\framework\component\env\interfaces\envInterface;

/**
 * env类
 * @package rephp\framework\component\env
 */
class env implements envInterface
{
    /**
     * 获取env配置信息
     * @param string $name    env配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public function get($name, $default = '')
    {
        return container::getContainer()->get('coreEnv')->get($name, $default);
    }
}
<?php

namespace rephp\component\env;

use rephp\component\container\container;
use rephp\component\env\interfaces\envInterface;

/**
 * env类
 * @package rephp\component\env
 */
class env implements envInterface
{
    /**
     * 自动加载env资源
     * @param string $envPath  env所在路径
     * @throws \Exception
     */
    public function __construct($envPath)
    {
        //判断末尾是否含有/
        $checkStr = substr($envPath, -1);
        in_array($checkStr, ['/', '\\']) || $envPath .= '/';
        $this->setPath($envPath);
        $this->loadFile($envPath.'.env');
    }

    /**
     * 设置env所在路径
     * @param string $envPath env所在目录
     * @return boolean
     */
    public function setPath($envPath)
    {
        $this->envPath = $envPath;
    }

    /**
     * 加载配置文件
     * @param  string $fileName env文件绝对路径及名字
     * @return boolean
     * @throws \Exception
     */
    public function loadFile($fileName)
    {
        return container::getContainer()->get('coreEnv')->loadFile($fileName);
    }

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

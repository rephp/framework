<?php

namespace rephp\component\env\interfaces;

/**
 * env接口类
 */
interface envInterface
{
    /**
     * 获取env配置信息
     * @param string $name    env配置项名字
     * @param string $default 默认值
     * @return mixed
     */
    public function get($name, $default = '');

    /**
     * 加载配置文件
     * @param  string $fileName env文件绝对路径及名字
     * @return boolean
     * @throws \Exception
     */
    public function loadFile($fileName);
}

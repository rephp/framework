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

    /**
     * 获取php加载的文件
     * @return string[]
     */
    public function getFiles()
    {
        return get_included_files();
    }

    /**
     * 获取执行的sql命令
     * @return string[]
     */
    public function getSql()
    {
        return [];
    }

    /**
     * 输出调试信息
     */
    public function debug()
    {
        $isDebug = config('config.debug.is_debug', false);
        if ($isDebug) {
            //1.输出加载信息
            echo '<pre>';
            print_r($this->getFiles());
            //2.输出sql信息
            print_r($this->getSql());
            echo '</pre>';
        }
        return true;
    }

}
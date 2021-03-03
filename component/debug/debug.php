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
     * @var 开始执行时间
     */
    private $startTime;
    /**
     * @var int 内存占用
     */
    private $startMemory;

    /**
     * 初始化bug设置
     * @return boolean
     */
    public function __construct()
    {
        $isDebug = config('config.debug.is_debug', false);
        if ($isDebug) {
            $this->startTime   = microtime(TRUE);
            $this->startMemory = memory_get_usage();
            $res               = $this->setOpenDebug();
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
    public function __destruct()
    {
        $isDebug = config('config.debug.is_debug', false);
        if ($isDebug) {
            //1.输出加载信息
            echo '<pre>';
            print_r($this->getFiles());
            //2.输出sql信息
            print_r($this->getSql());
            //3.计算执行总时间
            echo '运行时间:' . round(microtime(TRUE) - $this->startTime, 6) . 's<br>' . "\n";
            //4.计算执行消耗内存
            echo '内存开销:' . round((memory_get_usage() - $this->startMemory) / 1024 / 1024, 6) . 'MB<br>' . "\n";
            echo '</pre>';
        }
        return true;
    }

}
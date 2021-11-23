<?php
namespace rephp\component\debug\com;

use rephp\component\debug\interfaces\debugInterface;

/**
 * debug  V1 版本
 * @package rephp\component\debug\com
 */
final class debugV1 implements debugInterface
{

    /**
     * 开启debug模式配置
     * todo:扩展sql及文件加载展示
     * @return bool
     */
    public function openDebug()
    {
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 'Off');
        $isCli = defined('CLI_URI');
        if($isCli){
            return true;
        }
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        $whoops->register();

        return true;
    }

    /**
     * 关闭debug模式配置
     * @return bool
     */
    public function closeDebug()
    {
        error_reporting(0);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('log_errors_max_len', 1024);
        //日志位置
        $logFileName = createLogPath('php');
        if(empty($logFileName)){
            throw new \Exception('创建日志目录失败');
        }
        ini_set('error_log', $logFileName);

        return true;
    }

}
<?php
namespace rephp\component\debug\bootstrap;

use rephp\component\debug\interfaces\debugInterface;

/**
 * debug  V1 版本
 * @package rephp\framework\component\debug\bootstrap
 */
final class debugV1 implements debugInterface
{

    /**
     * 开启debug模式配置
     * todo:扩展sql及文件加载展示
     * @return bool
     */
    public function setOpenDebug()
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
    public function setCloseDebug()
    {
        error_reporting(0);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('log_errors_max_len', 1024);
        //日志位置
        $logPath = $this->config->get('config.debug.log_path', ROOT_PATH . 'runtime/log/');
        in_array(substr($logPath, -1), ['/', '\\']) || $logPath .= '/';
        $logFileName = $logPath . date('Y/m/d', time()) . '.log';
        is_dir(dirname($logFileName)) || mkdir(dirname($logFileName), 0777, true);
        ini_set('error_log', $logFileName);

        return true;
    }

}
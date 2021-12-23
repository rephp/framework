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
        if ($isCli) {
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
        return set_exception_handler([__CLASS__, 'setExceptionHandler']);
    }

    /**
     * 接管错误日志写入日志文件
     * @param $e
     * @throws \Exception
     */
    public static function setExceptionHandler($e)
    {
        //日志位置
        $logFileName = getLogFileName('php');
        if (empty($logFileName)) {
            throw new \Exception('创建PHP日志目录失败');
        }
        $prefix = 'Exception:'."\n";
        if ($e instanceof \Error) {
            $prefix = 'Error:'."\n";
        }
        $logContent  = $prefix.'    编号:'.$e->getCode()."\n";
        $logContent .= '    信息:'.$e->getMessage()."\n";
        $logContent .= '    文件:'.$e->getFile()."\n";
        $logContent .= '    行号:'.$e->getLine()."\n";
        if ($e instanceof \Error) {
            $logContent .= '    追踪:'."\n";
            $traceList = $e->getTrace();
            foreach ($traceList as $traceInfo) {
                $file  = empty($traceInfo['file']) ? '' : $traceInfo['file'];
                empty($file) && $file = empty($traceInfo['class']) ? '' : $traceInfo['class'];
                $logContent .= '        '.$file.'['.$traceInfo['line'].'] => function:'.$traceInfo['function']."\n";
            }
        }
        $logContent .= "--\n";

        file_put_contents($logFileName, $logContent, 8);
    }
}

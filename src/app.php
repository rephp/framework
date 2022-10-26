<?php
/**
 * app核心模块
 */

namespace rephp;

use rephp\component\container\container;
use rephp\component\debug\debug;

/**
 * app核心类
 * @package framework
 */
class app
{
    /**
     * @var string 配置实例类
     */
    private $configCom = component\config\com\configV1::class;

    /**
     * @var string env实例类
     */
    private $envCom = component\env\com\envV1::class;

    /**
     * 初始化默认绑定的对象
     * @var string[]
     */
    private $rephpConfig = [
        'reponse'   => component\response\response::class,
        'request'   => component\request\request::class,
        'coreRoute' => component\route\com\macawRoute::class,
        'coreEvent' => component\event\com\eventV1::class,
        'coreDebug' => component\debug\com\debugV1::class,
        'cmd'       => component\cmd\cmd::class,
    ];

    /**
     * 运行
     * @param string $appPath app路径
     * @return string
     */
    public function run($appPath)
    {
        try {
            //1.预加载资源环境
            $this->loadEnv(dirname($appPath) . '/');
            $this->loadConfig(dirname($appPath) . '/config/');
            //2.预先绑定具体组件
            $this->preBindCoreComponent();
            //3.加载核心类 & 运行
            container::getContainer()->bind('appCore', core\appCore::class, [$appPath])->run();
        } catch (\Exception $e) {
            try{
                container::getContainer()->bind('debug', debug::class);
            }catch (\Exception $e){
                $this->throwInitExcetionWithoutDebugComponent($e);
            }
        }
    }

    /**
     * 系统初始化时，来不及加载debug组件时,异常时抛出错误
     * @param \Exception $e 错误对象
     */
    private function throwInitExcetionWithoutDebugComponent($e)
    {
        $prefix = 'Exception:' . "\n";
        if ($e instanceof \Error) {
            $prefix = 'Error:' . "\n";
        }
        $logContent = $prefix . '    编号:' . $e->getCode() . "\n";
        $logContent .= '    信息:' . $e->getMessage() . "\n";
        $logContent .= '    文件:' . $e->getFile() . "\n";
        $logContent .= '    行号:' . $e->getLine() . "\n";
        if ($e instanceof \Error) {
            $logContent .= '    追踪:' . "\n";
            $traceList  = $e->getTrace();
            foreach ($traceList as $traceInfo) {
                $file = empty($traceInfo['file']) ? '' : $traceInfo['file'];
                empty($file) && $file = empty($traceInfo['class']) ? '' : $traceInfo['class'];
                $logContent .= '        ' . $file . '[' . $traceInfo['line'] . '] => function:' . $traceInfo['function'] . "\n";
            }
        }
        $logContent .= "--\n";

        $isDebug = config('config.debug.is_debug', false);
        if ($isDebug) {
            exit($logContent);
        } else {
            $logFileName = getLogFileName('php');
            if (empty($logFileName)) {
                exit('创建PHP日志目录失败');
            }
            file_put_contents($logFileName, $logContent, 8);
        }

    }

    /**
     * 加载env
     * @param string $envPath env路径
     * @return boolean
     */
    private function loadEnv($envPath)
    {
        container::getContainer()->bind('coreEnv', $this->envCom);
        container::getContainer()->bind('env', component\env\env::class, [$envPath]);
        return true;
    }

    /**
     * 首先需要加载的配置文件
     * @param string $configPath 配置文件目录
     * @return boolean
     */
    private function loadConfig($configPath)
    {
        container::getContainer()->bind('coreConfig', $this->configCom);
        container::getContainer()->bind('config', component\config\config::class, [$configPath]);
        return true;
    }

    /**
     * 预先注册组件
     * 优先级依赖按照从上往下顺序来加载
     * @return bool
     */
    private function preBindCoreComponent()
    {
        foreach ($this->rephpConfig as $bindName => $class) {
            container::getContainer()->bind($bindName, $class);
        }

        return true;
    }
}

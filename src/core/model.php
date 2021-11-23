<?php
namespace rephp\core;

use rephp\component\container\container;
use rephp\redb\redb;


/**
 * model抽象类
 * @package rephp\ext
 */
abstract class model extends redb
{
    use \rephp\traits\publicTrait;

    /**
     * 初始化模型
     */
    public function __construct($configList=[])
    {
        empty($configList) && $configList = config('database');
        //获取数据库配置
        $db = $this->getDb();
        if (!isset($configList[$db])) {
            throw new \Exception('当前模型db配置错误，请检查数据库配置项的key', 1404);
        }
        parent::__construct($configList[$db]);
    }

    /**
     * 静态方法获取动态对象
     * @param array $configList
     * @return redb
     */
    public static function db($configList = [])
    {
        return parent::db($configList);
    }

    /**
     * 调用私有或者不存在的类方法时触发
     * @param  string  $className  类名字
     * @param  array   $arguments  参数
     * @return mixed
     */
    public function __call($methodName, $arguments)
    {
        $calldClassName = get_called_class();
        $module = $this->getModuleName();

        $pos = strpos($calldClassName, 'app\\common\\');
        if($pos === false){
            $className = str_replace('app\\modules\\' . $module . '\\model\\', 'app\\common\\model\\', $calldClassName);
        }else{
            $className = str_replace('app\\common\\model\\', 'app\\modules\\' . $module . '\\model\\', $calldClassName);
        }
        if(!class_exists($className)){
            throw new \Exception('model not exist:' . $calldClassName, 404);
        }
        if(!method_exists($className, $methodName)){
            throw new \Exception('method not exist:'.$calldClassName. '->'.$methodName, 404);
        }

        return container::getContainer()->call($className, $methodName, $arguments);
    }

    /**
     * 销毁之前先追加sql信息
     */
    public function __destruct()
    {
        $sqlList = $this->getLog();
        $isDebug = config('config.debug.is_debug', false);
        if($isDebug){
            $logFileName = createLogPath('mysql');
            if(empty($logFileName)){
                throw new \Exception('创建日志目录失败');
            }
            $errorLogFileName = createLogPath('mysql_error');
            if(empty($errorLogFileName)){
                throw new \Exception('创建错误日志目录失败');
            }
        }
        //执行日志处理
        foreach($sqlList as $index=>$item){
            empty($logFileName) || file_put_contents($logFileName, '当前时间:'.date('Y-m-d H:i:s', time()).' | 运行时间:'.$item['time'].' | SQL:'.$item['sql']."\n-\n");
            container::getContainer()->get('debugbar')->sql($item['sql'], $item['time'], 'common');
        }
        //错误日志处理
        $errorLogList = $this->getErrorLog();
        foreach($errorLogList as $index=>$item){
            empty($errorLogFileName) || file_put_contents($errorLogFileName, '当前时间:'.date('Y-m-d H:i:s', time()).' | 运行时间:'.$item['time'].' | SQL:'.$item['sql'].' | 错误:'.$item['error']."\n-\n");
            container::getContainer()->get('debugbar')->sql($item['sql'], $item['time'], 'error');
            container::getContainer()->get('debugbar')->error($item['error']);
        }
    }

}
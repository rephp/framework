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
    public function __construct($configList = [])
    {
        empty($configList) && $configList = config('database');
        //获取数据库配置
        $db = self::getDb();
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
        return new static($configList);
    }

    /**
     * 开启事务-快捷方式
     * @return \rephp\redb\query\cmd
     */
    public static function startTrans()
    {
        return self::db()->getCmd()->startTrans();
    }

    /**
     * 插入数据-快捷方式
     * @param array $insertData array
     * @return int
     */
    public static function inserts($insertData)
    {
        return self::db()->insert($insertData);
    }

    /**
     * 更新数据-快捷方式
     * @param array $where      查询条件
     * @param array $updateData 更新数据
     * @return bool
     */
    public static function updates($where, $updateData)
    {
        return self::db()->where($where)->data($updateData)->update();
    }

    /**
     * 删除-快捷方式
     * @param $where
     * @return bool
     */
    public static function deletes($where)
    {
        return self::db()->where($where)->delete();
    }

    /**
     * 获取多条数据+条件筛选下的总记录数
     * @return array
     */
    public function fetch()
    {
        $orm   = $this->getOrmModel();
        $list  = $this->all();
        self::doSqlLog();
        $count = $this->setOrmModel($orm)->count();
        return [
            'list'  => $list,
            'count' => $count,
        ];
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
        if ($pos === false) {
            $className = str_replace('app\\modules\\' . $module . '\\model\\', 'app\\common\\model\\', $calldClassName);
        } else {
            $className = str_replace('app\\common\\model\\', 'app\\modules\\' . $module . '\\model\\', $calldClassName);
        }
        if (!class_exists($className)) {
            throw new \Exception('model not exist:' . $calldClassName, 404);
        }
        if (!method_exists($className, $methodName)) {
            throw new \Exception('method not exist:'.$calldClassName. '->'.$methodName, 404);
        }

        return container::getContainer()->call($className, $methodName, $arguments);
    }

    /**
     * 处理SQL日志
     * @throws \Exception
     */
    public function __destruct()
    {
        self::doSqlLog();
    }

    /**
     * 处理SQL日志
     * @throws \Exception
     */
    protected function doSqlLog()
    {
        $sqlInfo = $this->getLastErrorLog();
        empty($sqlInfo) && $sqlInfo = $this->getLastLog();
        if (!empty($sqlInfo)) {
            $this->setSqlToDebugbar($sqlInfo);
            $this->saveSqlLog($sqlInfo);
        }

        return true;
    }

    /**
     * 将sql信息加载到debugbar
     * @param array $sqlInfo sql执行信息
     * @return bool
     */
    protected function setSqlToDebugbar($sqlInfo)
    {
        $isDebug = config('config.debug.is_debug', false);
        if (!$isDebug) {
            return false;
        }
        $type = empty($sqlInfo['error']) ? 'common' : 'error';
        container::getContainer()->get('debugbar')->sql($sqlInfo['sql'], $sqlInfo['time'], $type);
        empty($sqlInfo['error']) || container::getContainer()->get('debugbar')->error($sqlInfo['error']);

        return true;
    }

    /**
     * 保存sql日志
     * @param  array $sqlInfo sql执行信息
     * @return false|int
     * @throws \Exception
     */
    protected function saveSqlLog($sqlInfo)
    {
        $isSaveSql = config('config.debug.is_save_sql', false);
        if (!$isSaveSql) {
            return false;
        }
        $logFileName = getLogFileName('mysql');
        if (empty($logFileName)) {
            throw new \Exception('创建SQL日志目录失败');
        }
        $logContent  = '时间:'.date('Y-m-d H:i:s', time())."\n";
        $logContent .= '耗时:'.$sqlInfo['time'].'秒'."\n";
        $logContent .= 'SQL:'.$sqlInfo['sql']."\n";
        if (isset($sqlInfo['error'])) {
            $logContent .= '错误编号:'.$sqlInfo['error']['code']."\n";
            $logContent .= '错误信息:'.$sqlInfo['error']['msg']."\n";
        }
        $logContent .= '---'."\n";

        return file_put_contents($logFileName, $logContent, FILE_APPEND);
    }
}

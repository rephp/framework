<?php

namespace rephp\component\database;

use rephp\component\container\container;
use rephp\component\database\interfaces\databaseInterface;

/**
 * 数据库操作类
 * @package rephp\component\database
 */
class database extends dbBase implements databaseInterface
{
    /**
     * @var 表名
     */
    public $table;
    /**
     * @var 数据库连接名
     */
    public  $db;

    /**
     * 查询单个记录
     * @param array  $where       查询条件
     * @param string $column      要显示的列名
     * @param string $orderBy     排序方式
     * @param string $groupBy     分组方式
     * @param array  $leftJoinArr 左连接查询关联数组，如['admin_user AS A' => 'A.id=order_table.uid', 'user_role'=>'A.id=user_role.uid']
     * @param string $table       表名
     * @return array|bool
     */
    public function getOne($where, $column = '*', $orderBy = '', $groupBy = '', $leftJoinArr = [], $table = '')
    {
        return container::getContainer()->get('coreDatabase')->getOne($where, $column, $orderBy, $groupBy, $leftJoinArr, $table);
    }

    /**
     * 查询多条记录，可分页
     * @param array  $where       查询条件
     * @param string $column      要显示的列名
     * @param int    $page        当前页数
     * @param int    $limit       每页多少条
     * @param string $orderBy     排序方式
     * @param string $groupBy     分组方式
     * @param array  $leftJoinArr 左连接查询关联数组，如['admin_user AS A' => 'A.id=order_table.uid', 'user_role'=>'A.id=user_role.uid']
     * @param string $table       表名
     * @return array|bool
     */
    public function fetch($where, $column = '*', $page = 0, $limit = 10, $orderBy = '', $groupBy = '', $leftJoinArr = [], $table = '')
    {
        return container::getContainer()->get('coreDatabase')->fetch($where, $column, $page, $limit, $orderBy, $groupBy, $leftJoinArr, $table);
    }

    /**
     * 统计个数
     * @param array  $where       查询条件
     * @param array  $leftJoinArr 左连接查询关联数组，如['admin_user AS A' => 'A.id=order_table.uid', 'user_role'=>'A.id=user_role.uid']
     * @param string $table       表名
     * @return int
     */
    public function count($where, $leftJoinArr = [], $table = '')
    {
        return container::getContainer()->get('coreDatabase')->count($where, $leftJoinArr, $table);
    }

    /**
     * 组合查询多条记录(含总数)，可分页
     * @param array  $where       查询条件  ['id'=>3, 'type'=>'hello'] or ['id'=>['>=', 55], 'type'=>['in', ['hi', 'hello', 2, 3] ]]
     * @param string $column      要显示的列名
     * @param int    $page        当前页数
     * @param int    $limit       每页多少条
     * @param string $orderBy     排序方式
     * @param string $groupBy     分组方式
     * @param array  $leftJoinArr 左连接查询关联数组，如['admin_user AS A' => 'A.id=order_table.uid', 'user_role'=>'A.id=user_role.uid']
     * @param string $table       表名
     * @return array|bool
     */
    public function getList($where, $column = '*', $page = 0, $limit = 10, $orderBy = '', $groupBy = '', $leftJoinArr = [], $table = '')
    {
        return container::getContainer()->get('coreDatabase')->getList($where, $column, $page, $limit, $orderBy, $groupBy, $leftJoinArr, $table);
    }

    /**
     * 插入单条数据
     * @param array  $insertData 要插入的单条数据
     * @param string $table      表名
     * @return boolean
     */
    public function insert($insertData, $table = '')
    {
        return container::getContainer()->get('coreDatabase')->insert($insertData, $table);
    }

    /**
     * 批量插入数据
     * @param array $batchInsertData 二维数组，批量插入的数据源
     * @return int
     */
    public function batchInsert($batchInsertData, $table = '')
    {
        return container::getContainer()->get('coreDatabase')->batchInsert($batchInsertData, $table);
    }

    /**
     * 更新数据
     * @param array  $where      查询条件
     * @param array  $updateData 更新数据
     * @param string $table      数据表名
     * @return int
     */
    public function update($where, $updateData, $table = '')
    {
        return container::getContainer()->get('coreDatabase')->update($where, $updateData, $table);
    }

    /**
     * 某字段自增指定步长
     * @param array  $where  查询条件
     * @param array  $column 列名，即要更新的字段名
     * @param int    $step   要增加的步长
     * @param string $table  数据表名
     */
    public function inc($where, $column, $step = 1, $table = '')
    {
        return container::getContainer()->get('coreDatabase')->inc($where, $column, $step, $table);
    }

    /**
     * 某字段减少指定步长
     * @param array  $where  查询条件
     * @param array  $column 列名，即要更新的字段名
     * @param int    $step   要减少的步长
     * @param string $table  数据表名
     */
    public function dec($where, $column, $step = 1, $table = '')
    {
        return container::getContainer()->get('coreDatabase')->dec($where, $column, $step, $table);
    }

    /**
     * 删除数据
     * @param array  $where 查询条件
     * @param string $table 数据表名
     * @return int
     */
    public function delete($where, $table = '')
    {
        return container::getContainer()->get('coreDatabase')->delete($where, $table);
    }

    /**
     * 开启事务
     * @return Transaction|null
     */
    public function startTrans()
    {
        return container::getContainer()->get('coreDatabase')->startTrans();
    }

    /**
     * 事务回滚
     * @param Transaction $trans 事务对象
     */
    public function rollback(Transaction $trans)
    {
        return container::getContainer()->get('coreDatabase')->rollback($trans);
    }

    /**
     * 事务提交
     * @param Transaction $trans 事务对象
     */
    public function commit(Transaction $trans)
    {
        return container::getContainer()->get('coreDatabase')->commit($trans);
    }

}
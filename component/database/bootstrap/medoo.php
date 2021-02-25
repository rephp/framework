<?php

namespace rephp\framework\component\database\bootstrap;

use rephp\framework\component\database\interfaces\databaseInterface;

/**
 * medoo数据库操作类
 * @package rephp\framework\component\database\bootstrap
 */
final class medoo implements databaseInterface
{


    /**
     * 获取Query对象
     * @return Query
     */
    protected function getQueryClient()
    {
        return new Query();
    }

    /**
     * 执行sql
     * @param string $sql 要执行的sql语句
     */
    public function query($sql)
    {
        return $app->db->createCommand($sql)->queryAll();
    }

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
        $table = $this->getTableName($table);

        $model = $this->getQueryClient()->from($table)->select($column);
        if (!empty($where)) {
            $wherePreSql = $this->formatWherePreSql($where);
            $whereParams = $this->formatPreSqlParams($where);
        }
        empty($wherePreSql) || $model->where($wherePreSql);
        empty($whereParams) || $model->addParams($whereParams);
        //左连查询
        $leftJoinArr = (array)$leftJoinArr;
        foreach ($leftJoinArr as $leftJoinTable => $on) {
            if (empty($leftJoinTable) || empty($on)) {
                continue;
            }
            $model->leftJoin($leftJoinTable, $on);
        }
        empty($orderBy) || $model->orderBy($orderBy);
        empty($groupBy) || $model->groupBy($groupBy);
        //整理查询结果统一为数组
        $res = $model->limit(1)->one();

        return empty($res) ? [] : $res;
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
        $limit = (int)$limit;
        $page  = (int)$page;
        ($page < 1) && $page = 0;
        $table = $this->getTableName($table);
        $model = $this->getQueryClient()->from($table)->select($column);
        if (!empty($where)) {
            $wherePreSql = $this->formatWherePreSql($where);
            $whereParams = $this->formatPreSqlParams($where);
        }

        empty($wherePreSql) || $model->where($wherePreSql);
        empty($whereParams) || $model->addParams($whereParams);
        //左连查询
        $leftJoinArr = (array)$leftJoinArr;
        foreach ($leftJoinArr as $leftJoinTable => $on) {
            if (empty($leftJoinTable) || empty($on)) {
                continue;
            }
            $model->leftJoin($leftJoinTable, $on);
        }
        empty($orderBy) || $model->orderBy($orderBy);
        empty($groupBy) || $model->groupBy($groupBy);
        empty($page) || $model->offset(($page - 1) * $limit)->limit($limit);
        return $model->all();
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
        $table = $this->getTableName($table);
        $model = $this->getQueryClient()->from($table)->select('count(*) AS num');
        if (!empty($where)) {
            $wherePreSql = $this->formatWherePreSql($where);
            $whereParams = $this->formatPreSqlParams($where);
        }
        empty($wherePreSql) || $model->where($wherePreSql);
        empty($whereParams) || $model->addParams($whereParams);
        //左连查询
        $leftJoinArr = (array)$leftJoinArr;
        foreach ($leftJoinArr as $leftJoinTable => $on) {
            if (empty($leftJoinTable) || empty($on)) {
                continue;
            }
            $model->leftJoin($leftJoinTable, $on);
        }
        $res = $model->one();

        return (int)$res['num'];
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
        $list  = $this->fetch($where, $column, $page, $limit, $orderBy, $groupBy, $leftJoinArr, $table);
        $count = $this->count($where, $leftJoinArr, $table);

        return ['list' => $list, 'count' => $count];
    }

    /**
     * 插入单条数据
     * @param array  $insertData 要插入的单条数据
     * @param string $table      表名
     * @return boolean
     */
    public function insert($insertData, $table = '')
    {
        $table        = $this->getTableName($table);
        $preSql       = $this->formatInsertPreSql($insertData, $table);
        $preSqlParams = $this->formatPreSqlParams($insertData);

        //执行插入
        $model = $app->db->createCommand($preSql);
        empty($preSqlParams) || $model->bindValues($preSqlParams);
        //插入是否成功
        $res      = $model->execute();
        $insertId = $res ? $app->db->getLastInsertID() : 0;

        return $insertId;
    }

    /**
     * 批量插入数据
     * @param array $batchInsertData 二维数组，批量插入的数据源
     * @return int
     */
    public function batchInsert($batchInsertData, $table = '')
    {
        $table        = $this->getTableName($table);
        $preSql       = $this->formatBatchInsertPreSql($batchInsertData, $table);
        $preSqlParams = $this->formatBatchInsertPreSqlParams($batchInsertData);
        //执行插入
        $model = $app->db->createCommand($preSql);
        empty($preSqlParams) || $model->bindValues($preSqlParams);

        return $model->execute();
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
        $table        = $this->getTableName($table);
        $preSql       = $this->formatUpdatePreSql($where, $updateData, $table);
        $preSqlParams = $this->formatUpdatePreSqlParams($where, $updateData);

        //执行更新动作
        $model = $app->db->createCommand($preSql);
        empty($preSqlParams) || $model->bindValues($preSqlParams);

        return $model->execute();
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
        $table        = $this->getTableName($table);
        $preSql       = $this->formatIncPreSql($where, $column, $step, $table);
        $preSqlParams = $this->formatPreSqlParams($where);
        //执行插入
        $model = $app->db->createCommand($preSql);
        empty($preSqlParams) || $model->bindValues($preSqlParams);

        return $model->execute();
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
        $step > 0 && $step = -$step;
        return $this->inc($where, $column, $step, $table);
    }

    /**
     * 删除数据
     * @param array  $where 查询条件
     * @param string $table 数据表名
     * @return int
     */
    public function delete($where, $table = '')
    {
        $table        = $this->getTableName($table);
        $preSql       = $this->formatDeletePreSql($where, $table);
        $preSqlParams = $this->formatPreSqlParams($where);
        //执行删除sql
        $model = $app->db->createCommand($preSql);
        empty($preSqlParams) || $model->bindValues($preSqlParams);

        return $model->execute();
    }

    /**
     * 开启事务
     * @return Transaction|null
     */
    public function startTrans()
    {
        return $app->db->beginTransaction();
    }

    /**
     * 事务回滚
     * @param Transaction $trans 事务对象
     */
    public function rollback(Transaction $trans)
    {
        $trans->rollBack();
    }

    /**
     * 事务提交
     * @param Transaction $trans 事务对象
     */
    public function commit(Transaction $trans)
    {
        $trans->commit();
    }
}
<?php
namespace rephp\component\database;

/**
 * 数据库操作抽象类
 * @package common\models\db
 * @author  sam.shi
 */
abstract class dbBase
{
    /**
     * @var 表名
     */
    public    static $table;
    /**
     * @var 数据库连接名
     */
    protected static $db;

    /**
     * 获取表名
     * @param string $table 表名
     * @return string
     */
    protected function getTableName($table)
    {
        $table = empty($table) ? $this->table : $table;

        return $table;
    }

    /**
     * 过滤字段名，防止sql注入
     * @param string $column 列名
     * @return string
     */
    protected function filterColumn($column)
    {
        $replaceArr = [
            ' ', '%', '*', '\t', '\\', '/', '\'', '"', '#', ';', '$', '@', '!', '(', ')', '+', '-', '=',
        ];
        $column     = str_replace($replaceArr, '', $column);
        return $column;
    }

    /**
     * 格式化查询条件为预查询sql
     * @param arrau|string $data 数据源,格式如：查询 ['id'=>3, 'title'=>['like', 'nokia mobile'], 'cate_id'=>['in', [2,3,4,5] ], 'content'=>['is not null'] ]
     * @return string
     */
    protected function formatWherePreSql($data)
    {
        if (empty($data)) {
            return '';
        }
        if (is_string($data)) {
            return addslashes($data);
        }

        $preSql = '';
        $data   = (array)$data;
        $split  = '';
        foreach ($data as $key => $val) {
            $docChar = is_array($val) ? $val[0] : '=';
            $key     = $this->filterColumn($key);
            //兼容is null 和is not null
            if (is_array($val) && !isset($val[1])) {
                $preSql = $split . $key . $val[0];
                $split  = ' AND ';
                continue;
            }
            $preSql = $split . $key . $docChar . ':' . $key;
            $split  = ' AND ';
        }

        return $preSql;
    }

    /**
     * 格式化数组为预查询sql参数
     * @param arrau|string $data 数据源,格式如：查询 ['id'=>3, 'title'=>['like', 'nokia mobile'], 'cate_id'=>['in', [2,3,4,5] ], 'content'=>['is not null'] ]
     * @return array
     */
    protected function formatPreSqlParams($data)
    {
        if (empty($data) || is_string($data)) {
            return [];
        }

        $data = (array)$data;
        $res  = [];
        foreach ($data as $key => $val) {
            $key = $this->filterColumn($key);
            if (!is_array($val)) {
                $res[':' . $key] = $val;
                continue;
            }
            //兼容is null和is not null
            if (!isset($val[1])) {
                continue;
            }
            $res[':' . $key] = is_array($val[1]) ? "('" . implode(" ',' ", $val[1]) . "')" : $val[1];
        }

        return $res;
    }

    /**
     * 生成插入数据的presql语句
     * @param array  $insertData 插入的数据源数组
     * @param string $table      数据表
     * @return  string
     */
    protected function formatInsertPreSql($insertData, $table)
    {
        if (is_string($insertData)) {
            return addslashes($insertData);
        }
        $insertData = (array)$insertData;
        $keyArr     = array_keys($insertData);
        $keyArr     = (array)$keyArr;
        foreach ($keyArr as $index => $key) {
            $key            = $this->filterColumn($key);
            $keyArr[$index] = $key;
        }

        $preSql = 'INSERT INTO `' . $table . '` (' . implode(',', $keyArr) . ') VALUES
            (:' . implode(', :', $keyArr) . ')';

        return $preSql;
    }

    /**
     * 生成批量插入数据的presql语句
     * @param array  $batchInsertData 批量数据源
     * @param string $table           表名
     * @return string
     */
    protected function formatBatchInsertPreSql($batchInsertData, $table)
    {
        if (is_string($batchInsertData)) {
            return addslashes($batchInsertData);
        }
        $batchInsertData = (array)$batchInsertData;
        $firstRow        = current($batchInsertData);
        $keyArr          = array_keys($firstRow);
        $keyArr          = (array)$keyArr;
        foreach ($keyArr as $index => $key) {
            $key            = $this->filterColumn($key);
            $keyArr[$index] = $key;
        }
        $preSql = 'INSERT INTO `' . $table . '` (' . implode(',', $keyArr) . ') VALUES ';

        $split = '';
        foreach ($batchInsertData as $index => $val) {
            $tempSplit = '';
            $preSql    .= $split . ' (';
            foreach ($keyArr as $key) {
                $preSql    .= $tempSplit . ' :' . $key . $index;
                $tempSplit = ',';
            }
            $preSql .= ' )';
            $split  = ', ';
        }

        return $preSql;
    }

    /**
     * 生成批量插入的绑定参数数组
     * @param array $batchInsertData 批量插入数据源
     * @return array
     */
    protected function formatBatchInsertPreSqlParams($batchInsertData)
    {
        if (is_string($batchInsertData)) {
            return [];
        }
        $batchInsertData = (array)$batchInsertData;
        $params          = [];
        foreach ($batchInsertData as $index => $val) {
            foreach ($val as $key => $vvv) {
                $key                         = $this->filterColumn($key);
                $params[':' . $key . $index] = $vvv;
            }
        }

        return $params;
    }

    /**
     * 生成更新presql语句
     * @param array  $where      查询条件
     * @param array  $updateData 更新数据内容
     * @param string $table      表名
     * @return string
     */
    protected function formatUpdatePreSql($where, $updateData, $table = '')
    {
        $preSql = 'UPDATE `' . $table . '` SET ';
        //整理数据presql
        $split      = '';
        $updateData = (array)$updateData;
        foreach ($updateData as $key => $val) {
            if (empty($key)) {
                continue;
            }
            $key    = $this->filterColumn($key);
            $preSql .= $split . ' ' . $key . '=:_UD_' . $key;
            $split  = ', ';
        }
        //整理查询条件presql
        if (empty($where)) {
            return $preSql;
        }
        if (is_string($where)) {
            return $preSql .= ' WHERE ' . addslashes($where);
        }
        $where  = (array)$where;
        $split  = '';
        $preSql .= ' WHERE ';
        foreach ($where as $key => $val) {
            if (empty($key)) {
                continue;
            }
            $key    = $this->filterColumn($key);
            $preSql .= $split . ' ' . $key . '=:_WH_' . $key;
            $split  = ' AND ';
        }

        return $preSql;
    }

    /**
     * 生成更新presql绑定参数列表
     * @param array $where      查询条件
     * @param array $updateData 更新数据内容
     * @return array
     */
    protected function formatUpdatePreSqlParams($where, $updateData)
    {
        $params = [];
        //整理更新数据部分
        foreach ($updateData as $key => $val) {
            if (empty($key)) {
                continue;
            }
            $key                    = $this->filterColumn($key);
            $params[':_UD_' . $key] = $val;
        }
        //整理查询条件部分
        if (is_string($where)) {
            return addslashes($params);
        }
        $where = (array)$where;
        foreach ($where as $key => $val) {
            if (empty($key)) {
                continue;
            }
            $key                    = $this->filterColumn($key);
            $params[':_WH_' . $key] = $val;
        }

        return $params;
    }

    /**
     * 生成自增长presql语句
     * @param array  $where  查询条件
     * @param string $column 要操作的字段
     * @param int    $step   步长
     * @param string $table  表名
     * @return string
     */
    public function formatIncPreSql($where, $column, $step, $table)
    {
        //隐式转换为数字型
        $step        = $step + 0;
        $column      = $this->filterColumn($column);
        $preSql      = 'UPDATE `' . $table . '` SET `' . $column . '` = `' . $column . '` + ' . $step;
        $wherePreSql = $this->formatWherePreSql($where);
        empty($wherePreSql) || $preSql .= ' WHERE ' . $wherePreSql;

        return $preSql;
    }

    /**
     * 生成删除presql语句
     * @param array  $where 查询条件
     * @param string $table 表名
     * @return string
     */
    protected function formatDeletePreSql($where, $table)
    {
        $preSql      = 'DELETE FROM `' . $table . '` ';
        $wherePreSql = $this->formatWherePreSql($where);
        empty($wherePreSql) || $preSql .= ' WHERE ' . $wherePreSql;

        return $preSql;
    }
}
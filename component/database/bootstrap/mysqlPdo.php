<?php
namespace database\bootstrap;

use database\interfaces\bootstrapInterface;

/**
 * 实例化pdo类
 * @package database\bootstrap
 */
class mysqlPdo implements bootstrapInterface
{
    public $client;

    /**
     * 获取pdo对象实例
     */
    public function getClient()
    {
        is_object($this->client) || $this->reConnect();
        return $this->client;
    }

    /**
     * 重连
     */
    public function reConnect()
    {
        $this->client = new \PDO();
    }




}
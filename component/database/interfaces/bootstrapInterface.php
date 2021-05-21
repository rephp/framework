<?php
namespace database\interfaces;

interface bootstrapInterface
{
    /**
     * 实现驱动实例化
     */
    public function reConnect();

    /**
     * 获取驱动对象
     * @return mixed
     */
    public function getClient();

}
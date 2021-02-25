<?php
namespace rephp\framework\component\event\interfaces;
/**
 * 事件接口
 * @package rephp\framework\component\event\interfaces
 */
interface eventInterface
{
    /**
     * 添加事件行为
     * 支持一个事件多个回调
     * @param string  $eventName 事件名字
     * @param mixed   $callback  回调函数
     * @param boolean $once      用完即焚
     * @return bool
     */
    public static function add($eventName, $callback, $once = false);

    /**
     * 移除事件
     * @param string $eventName 事件名字
     * @param int    $index     回调序号，如果不传则默认删除该事件所有回调
     * @return boolean
     */
    public static function remove($eventName, $index = -1);

    /**
     * 触发事件
     * @param mixed ...$params 参数，第一个参数为事件名，其他为事件传参
     * @return bool
     */
    public static function trigger(...$params);

}
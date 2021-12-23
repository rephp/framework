<?php

namespace rephp\component\event;

use rephp\component\event\interfaces\eventInterface;
use rephp\component\container\container;

/**
 * 配置管理类
 * @package rephp\component\config
 * //增加监听walk事件
 * event::add('walk', function(){
 *     echo "I am walking...n";
 * });
 * // 增加监听walk一次性事件
 * event::add('walk', function(){
 *     echo "I am listening...n";
 * }, true);
 * event::trigger('walk');
 *
 * // 增加回调参数
 * event::add('test', function($a, $b){
 *       echo "I am $a, $b ...n";
 *   }, true);
 * // 触发walk事件
 *  event::trigger('test', 3, 4);
 *
 * class test
 * {
 *     public function bar($a,$b){
 *         echo "$a and $b is : yyyyyyyy";
 *     }
 *
 *     public static function foo($a, $b){
 *         echo "$a and $b are : xxxxxxxx";
 *     }
 * }
 *
 * $test    = new test;
 * //动态方法
 * event::add('bar', array($test, 'bar'));
 * event::trigger('bar', 1, 2);
 * //静态方法
 * event::add('foo', 'test::foo');
 * event::trigger('foo', 4, 5);
 */
class event implements eventInterface
{

    /**
     * 获取当前所有的事件名字
     * @return array
     */
    public static function getAllEventName()
    {
        $class = container::getContainer()->get('coreEvent');
        return $class::getAllEventName();
    }

    /**
     * 添加事件行为
     * 支持一个事件多个回调
     * @param string  $eventName 事件名字
     * @param mixed   $callback  回调函数
     * @param boolean $once      用完即焚
     * @return bool
     */
    public static function add($eventName, $callback, $once = false)
    {
        $class = container::getContainer()->get('coreEvent');
        return $class::add($eventName, $callback, $once);
    }

    /**
     * 移除事件
     * @param string $eventName 事件名字
     * @param int    $index     回调序号，如果不传则默认删除该事件所有回调
     * @return boolean
     */
    public static function remove($eventName, $index = -1)
    {
        $class = container::getContainer()->get('coreEvent');
        return $class::remove($eventName, $index);
    }

    /**
     * 触发事件
     * @param mixed ...$params 参数，第一个参数为事件名，其他为事件传参
     * @return bool
     */
    public static function trigger(...$params)
    {
        $class = container::getContainer()->get('coreEvent');
        return $class::trigger(...$params);
    }
}

<?php
namespace rephp\framework\component\event\bootstrap;

use rephp\framework\component\event\interfaces\eventInterface;

/**
 * 事件，支持一个事件多个回调，支持回调用完即焚
 * 缺点： 如果一个事件有多个回调带参数，则所有回调方法的参数难以精确控制
 *
 *   // 增加监听walk事件
 *   event::add('walk', function(){
 *       echo "I am walking...n";
 *   });
 *  // 增加监听walk一次性事件
 *   event::add('walk', function(){
 *       echo "I am listening...n";
 *   }, true);
 *  event::trigger('walk');
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
 *
 */
final class eventV1 implements eventInterface
{
    /**
     * @var array 已注册的事件
     */
    public static $events = [];

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
        if (is_callable($callback)) {
            self::$events[$eventName][] = ['callback' => $callback, 'once' => $once];
            return true;
        }

        return false;
    }

    /**
     * 移除事件
     * @param string $eventName 事件名字
     * @param int    $index     回调序号，如果不传则默认删除该事件所有回调
     * @return boolean
     */
    public static function remove($eventName, $index = -1)
    {
        if ($index == -1) {
            unset(self::$events[$eventName]);
        } else {
            unset(self::$events[$eventName][$index]);
        }

        return true;
    }

    /**
     * 触发事件
     * @param mixed ...$params 参数，第一个参数为事件名，其他为事件传参
     * @return bool
     */
    public static function trigger(...$params)
    {
        $eventName = array_shift($params);
        if (!is_array(self::$events[$eventName])) {
            return false;
        }
        foreach (self::$events[$eventName] as $index => $callbackInfo) {
            call_user_func_array($callbackInfo['callback'], $params);
            $callbackInfo['once'] && self::remove($eventName, $index);
        }

        return true;
    }

}

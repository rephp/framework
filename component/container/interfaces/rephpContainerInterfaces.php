<?php

namespace rephp\framework\component\container\interfaces;
/**
 * Interface xyContainerInterface
 * @package xy\framework\component\container
 */
interface rephpContainerInterfaces
{
    /**
     * 获取容器本身实例
     * @return mixed
     */
    public static function getContainer();

    /**
     * 注册别名
     * @param string  $name       别名
     * @param string  $className  类完整名如\a\b\c\test::class
     * @return boolean
     */
    public function alias($name, $className);

    /**
     * 获得类的对象实例
     * @param string  $name       要注册的实例名字
     * @param string  $className  类名
     * @param array   $userParams 用户自定义参数（类对象参数除外的普通参数）
     * @param boolean $rebind     是否强制绑定
     * @return mixed
     */
    public function bind($name, $className, $userParams , $rebind );

    /**
     * 执行类的方法
     * @param  [type] $className  [类名]
     * @param  [type] $methodName [方法名称]
     * @param  [type] $params     [额外的参数]
     * @return [type]             [description]
     */
    public function call($className, $methodName, $params);

}
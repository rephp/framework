<?php

namespace rephp\traits;

use rephp\component\container\container;

trait publicTrait
{

    /**
     * 获取模块名
     * @return mixed
     * @throws \rephp\component\container\exceptions\notFoundException
     */
    public function getModuleName()
    {
        $request   = container::getContainer()->get('request');
        $routeInfo = $request->getRouteInfo();
        return $routeInfo['module'];
    }

    /**
     * 实例化同module下的logic层
     * @param string $className 类主名字字符串
     * @param array  $params    参数
     * @return \rephp\logic
     */
    public function logic($className, $params = [])
    {
        $module = $this->getModuleName();
        (strpos($className, '\\') === false) && $className = '\\app\\modules\\' . $module . '\\logic\\' . $className;
        $fullName = $className . 'Logic';
        class_exists($fullName) || $fullName = str_replace('\\app\\modules\\' . $module . '\\logic\\', '\\app\\common\\logic\\', $fullName);
        return container::getContainer()->bind($fullName, $fullName, $params);
    }

    /**
     * 实例化同module下的model层
     * @param string $className 类主名字字符串
     * @param array  $params    参数
     * @return \rephp\model
     */
    public function model($className, $params = [])
    {
        $module = $this->getModuleName();
        (strpos($className, '\\') === false) && $className = '\\app\\modules\\' . $module . '\\model\\' . $className;
        $fullName = $className . 'Model';
        class_exists($fullName) || $fullName =  str_replace('\\app\\modules\\' . $module . '\\model\\', '\\app\\common\\model\\', $fullName);
        return container::getContainer()->bind($fullName, $fullName, $params);
    }

    /**
     * 实例化同lib下的tool
     * @param string $className 类主名字字符串,跟文件名一致
     * @param array  $params    参数
     * @return \rephp\tool\$className
     */
    public function lib($className, $params = [])
    {
        $fullName = (strpos($className, '\\') === false) ? '\\rephp\\lib\\tool\\' . $className : $className;
        class_exists($fullName) || $fullName =  str_replace('\\rephp\\lib\\tool\\', '\\app\\lib\\', $fullName);
        return container::getContainer()->bind($fullName, $fullName, $params);
    }

}
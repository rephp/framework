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
        (strpos($className, '\\') === false) && $className = '\\app\\' . $module . '\\logic\\' . $className;
        $fullName = $className . 'Logic';
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
        (strpos($className, '\\') === false) && $className = '\\app\\' . $module . '\\model\\' . $className;
        $fullName = $className . 'Model';
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
        (strpos($className, '\\') === false) && $className = '\\rephp\\lib\\tool\\' . $className;
        return container::getContainer()->bind($className, $className, $params);
    }

}
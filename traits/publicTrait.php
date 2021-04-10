<?php
namespace traits;

trait publicTrait
{

    /**
     * 实例化同module下的logic层
     * @param string    $className     类主名字字符串
     * @return \rephp\logic
     */
    public function &logic($className)
    {
        (strpos($className, '\\') === false) && $className = '\\app\\'.WEB_MODULE.'\\logic\\'.$className;
        $fullName = $className.'Logic';
        return new $fullName();
    }

    /**
     * 实例化同module下的model层
     * @param  string    $className     类主名字字符串
     * @return \rephp\model
     */
    public function &model($className)
    {
        (strpos($className, '\\') === false) && $className = '\\app\\'.WEB_MODULE.'\\model\\'.$className;
        $fullName = $className.'Model';
        return new $fullName();
    }

    /**
     * 实例化同lib下的tool
     * @param  string    $className     类主名字字符串,跟文件名一致
     * @return \rephp\tool\$className
     */
    public function &lib($className)
    {
        (strpos($className, '\\') === false) && $className = '\\rephp\\lib\\tool\\'.$className;
        return new $className();
    }

}
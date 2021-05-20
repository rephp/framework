<?php
namespace rephp\core;

use rephp\component\container\container;

/**
 * model抽象类
 * @package rephp\ext
 */
abstract class model
{
    use \rephp\traits\publicTrait;

    /**
     * 调用私有或者不存在的类方法时触发
     * @param  string  $className  类名字
     * @param  array   $arguments  参数
     * @return mixed
     */
    public function __call($methodName, $arguments)
    {
        $calldClassName = get_called_class();
        $module = $this->getModuleName();

        $pos = strpos($calldClassName, '\\app\\common\\');
        if($pos === false){
            $className = str_replace('\\app\\modules\\' . $module . '\\model\\', '\\app\\common\\model\\', $calldClassName);
        }else{
            $className = str_replace('\\app\\common\\model\\', '\\app\\modules\\' . $module . '\\model\\', $calldClassName);
        }
        if(!class_exists($className)){
            throw new \Exception('model not exist:' . $calldClassName, 404);
        }
        if(!method_exists($className, $methodName)){
            throw new \Exception('method not exist:'.$calldClassName. '->'.$methodName, 404);
        }

        return container::getContainer()->call($className, $methodName, $arguments);
    }

}
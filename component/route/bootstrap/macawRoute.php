<?php

namespace rephp\framework\component\route\bootstrap;

use \NoahBuscher\Macaw\Macaw;
use rephp\framework\component\route\interfaces\routeInterface;

/**
 * macaw基础上衍生的中间路由层
 * @method static get(string $route, Callable $callback)
 * @method static post(string $route, Callable $callback)
 * @method static put(string $route, Callable $callback)
 * @method static delete(string $route, Callable $callback)
 * @method static options(string $route, Callable $callback)
 * @method static head(string $route, Callable $callback)
 */
final class macawRoute implements routeInterface
{

    /**
     * 根据当前url动态生成路由
     * @param string $baseUri    基本uri，如{$modeule}/{$controller}/{$action}
     * @param string $module     模块名
     * @param string $controller 控制器名字
     * @param string $action     方法名
     * @param string $method     请求方式
     * @return boolean
     */
    public function run($baseUri, $module, $controller, $action, $method = 'get')
    {
        //开启halt匹配模式
        Macaw::haltOnMatch(true);
        //判断参数
        if(empty($module) || empty($controller) || empty($action)){
            throw new \Exception('系统错误，请联系管理员');
        }
        //3.动态生成路由字符串
        $ruleStr   = $baseUri . '(:all)';
        $objectStr = 'app\\modules\\' . $module . '\\controller\\' . $controller . 'Controller@' . $action . 'Action';

        //4.挂载路由
        Macaw::$method($ruleStr, $objectStr);
        //5.兼容404
        Macaw::$error_callback = function () {
            throw new \Exception("路由无匹配项 404 Not Found");
        };
        //6.适配路由
        Macaw::dispatch();

        return true;
    }

    /**
     * 调用未定义的方法
     * @param string $name    方法名
     * @param array  $params 参数
     * @return mixed
     * @throws \rephp\framework\component\container\exceptions\notFoundException
     */
    public function __call($name, $params)
    {
        return Macaw::__callstatic($name, $params);
    }

    /**
     * 调用未定义的静态方法
     * @param string $name    方法名
     * @param array  $params 参数
     * @return mixed
     * @throws \rephp\framework\component\container\exceptions\notFoundException
     */
    public static function __callStatic($name, $params)
    {
        return Macaw::__callstatic($name, $params);
    }

}

<?php

namespace rephp\component\route\bootstrap;

use \NoahBuscher\Macaw\Macaw;
use rephp\component\route\interfaces\routeInterface;

/**
 * macaw基础上衍生的中间路由层
 * @method static get(string $env, Callable $callback)
 * @method static post(string $env, Callable $callback)
 * @method static put(string $env, Callable $callback)
 * @method static delete(string $env, Callable $callback)
 * @method static options(string $env, Callable $callback)
 * @method static head(string $env, Callable $callback)
 */
final class macawRoute implements routeInterface
{

    /**
     * 根据当前url动态生成路由
     * @param string $routeUri   基本uri，如{$modeule}/{$controller}/{$action}
     * @param string $module     模块名
     * @param string $controller 控制器名字
     * @param string $action     方法名
     * @param array $routeConfig  路由预定义配置
     * @return boolean
     */
    public function run($routeUri, $module, $controller, $action, $routeConfig = [])
    {
        //开启halt匹配模式
        Macaw::haltOnMatch(true);
        //判断参数
        if (empty($module) || empty($controller) || empty($action)) {
            throw new \Exception('系统错误，请联系管理员');
        }
        //3.动态生成路由字符串
        $ruleStr   = $routeUri . '(:all)';
        $objectStr = 'app\\modules\\' . $module . '\\controller\\' . $controller . 'Controller@' . $action . 'Action';
        empty($routeConfig['class'])  || $objectStr = $routeConfig['class'];
        $method    = $routeConfig['method'];
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
     * @param string $name   方法名
     * @param array  $params 参数
     * @return mixed
     * @throws \rephp\component\container\exceptions\notFoundException
     */
    public function __call($name, $params)
    {
        return Macaw::__callstatic($name, $params);
    }

    /**
     * 调用未定义的静态方法
     * @param string $name   方法名
     * @param array  $params 参数
     * @return mixed
     * @throws \rephp\component\container\exceptions\notFoundException
     */
    public static function __callStatic($name, $params)
    {
        return Macaw::__callstatic($name, $params);
    }

}

<?php
namespace rephp\component\cmd\com;

class help
{

    /**
     * 执行入口
     */
    public function run()
    {
        $output = $this->setBaseInfo(output::find());
        //加载客户自定义命令信息
        $output = $this->setSystemRouteList($output);
        //加载客户自定义命令信息
        $output = $this->setCustomRouteList($output);

        echo $output->get();
    }

    /**
     * 加载基本信息
     * @param $output
     * @return $output
     */
    private function setBaseInfo($output)
    {
        $output->title('用法:', 1, 1)
                        ->tab()->info('命令 [选项] [参数]', 1, 1)
                        ->endRow()
                        ->title('选项:', 1, 1)
                        ->tab()->success('-h, --help, --h, help, h', 1)->info('显示指定命令的帮助信息。当没有给出命令时，显示')->warning('list')->info('命令的帮助', 1, 1)
                        ->tab()->success('-q, --quiet', 1)->info('不输出任何消息')
                        ->tab()->success('-V, v, --version, version', 1)->info('显示此应用程序版本')
                        ->title('可用命令:', 1, 1);
        return $output;
    }

    /**
     * 加载系统命令信息
     * @param $output
     * @return $output
     */
    private function setSystemRouteList($output)
    {
        $cliSystemRouteList = require dirname(__DIR__) . '/route/console.php';
        //汇总整理数据
        $newCliRouteList = [];
        foreach ($cliSystemRouteList as $command => $item) {
            $newCommand = empty($newCliRouteList[$item['class']]) ? $command : $newCliRouteList[$item['class']] . ', ' . $command;
            empty($item['desc']) && $item['desc'] = '';
            $newCliRouteList[$item['class']] = ['command' => $newCommand, 'desc' => $item['desc']];
        }
        foreach ($newCliRouteList as $item) {
            $output->tab()->success($item['command'], 1)->info($item['desc'], 1, 1);
        }
        $cliSystemRouteList = null;
        $newCliRouteList    = null;

        return $output;
    }

    /**
     * 加载客户自定义命令信息
     * @param $output
     * @return $output
     */
    private function setCustomRouteList($output)
    {
        $routeConfigList = file_exists(ROOT_PATH . 'route/console.php') ? require ROOT_PATH . 'route/console.php' : [];
        $newRouteList    = [];
        foreach ($routeConfigList as $command => $item) {
            $newCommand = empty($routeConfigList[$item['class']]) ? $command : $routeConfigList[$item['class']] . ', ' . $command;
            empty($item['desc']) && $item['desc'] = '';
            $newRouteList[$item['class']] = ['command' => $newCommand, 'desc' => $item['desc']];
        }
        foreach ($newRouteList as $item) {
            $output->tab()->success($item['command'], 1)->info($item['desc'], 1, 1);
        }
        $routeConfigList    = null;
        $newRouteList       = null;

        return $output;
    }

}
<?php
namespace rephp\component\cmd\com;

class commandList
{

    public function run()
    {
        $output = output::find()->title('用法:', 1, 1)
                        ->tab()->info('命令 [选项] [参数]', 1, 1)
                        ->endRow()
                        ->title('选项:', 1, 1)
                        ->tab()->success('-h, --help, --h, help, h', 1)->info('显示指定命令的帮助信息。当没有给出命令时，显示')->warning('list')->info('命令的帮助', 1, 1)
                        ->tab()->success('-q, --quiet', 1)->info('不输出任何消息')
                        ->tab()->success('-V, v, --version, version', 1)->info('显示此应用程序版本')
                        ->title('可用命令:', 1, 1);

       //循环处理内置方法
        $cliSystemRouteList = require dirname(__DIR__) . '/route/console.php';
        foreach($cliSystemRouteList as $command=>$item){
            empty($item['desc']) && $item['desc'] = '';
            $output->tab()->success($command, 1)->info($item['desc'], 1, 1);
        }
        //循环分析外置方法
        $routeConfigList    = file_exists(ROOT_PATH . 'route/console.php') ? require ROOT_PATH . 'route/console.php' : [];
        foreach($routeConfigList as $command=>$item){
            empty($item['desc']) && $item['desc'] = '';
            $output->tab()->success($command, 1)->info($item['desc'], 1, 1);
        }
        echo $output->get();
    }


}
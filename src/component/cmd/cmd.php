<?php
namespace rephp\component\cmd;

use rephp\component\container\container;

class cmd
{
    /**
     * 设置路由
     * @return $this
     */
    public function setRoute()
    {
        $cliSystemRouteList = require './route/console.php';
        container::getContainer()->get('config')->set('console', 'cli_system_route_list', $cliSystemRouteList);
        return $this;
    }

}
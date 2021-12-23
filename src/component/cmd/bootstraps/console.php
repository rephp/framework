<?php

use rephp\component\container\container;

$cliSystemRouteList = require dirname(__DIR__).'/route/console.php';
container::getContainer()->get('config')->set('console', 'cli_system_route_list', $cliSystemRouteList);

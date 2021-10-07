<?php

use rephp\component\container\container;

$cliSystemRouteList = require './route/console.php';
container::getContainer()->get('config')->set('console', 'cli_system_route_list', $cliSystemRouteList);
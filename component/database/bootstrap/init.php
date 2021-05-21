<?php
namespace database\bootstrap;
//获取配置
use rephp\component\container\container;

$dbBootstrapClass = config('database.bootstrap.class', mysqlPdo::class);
container::getContainer()->bind('coreDatabase', $dbBootstrapClass);
<?php

namespace rephp\bootstrap;

use rephp\component\container\container;
use rephp\component\debug\debug;
use rephp\component\config\config;
use rephp\component\env\env;
use rephp\component\request\request;
use rephp\component\response\response;
use rephp\component\cmd\cmd;
use rephp\component\event\event;
use rephp\component\route\route;

//必须按顺序加载驱动
//1.env
container::getContainer()->bind('coreEnv', \rephp\component\env\com\envV1::class);
container::getContainer()->bind('env', env::class, [ROOT_PATH]);
//2.config
container::getContainer()->bind('coreConfig', \rephp\component\config\com\configV1::class);
container::getContainer()->bind('config', config::class, [ROOT_PATH.'config/']);
//3.request
container::getContainer()->bind('request', request::class);
//4.debug
container::getContainer()->bind('coreDebug', \rephp\component\debug\com\debugV1::class);
container::getContainer()->bind('debug', debug::class);
//5.reponse
container::getContainer()->bind('response', response::class);
//6.路由
container::getContainer()->bind('coreRoute', \rephp\component\route\com\macawRoute::class);
container::getContainer()->bind('route', route::class);
//7.事件
container::getContainer()->bind('coreEvent', \rephp\component\event\com\eventV1::class);
container::getContainer()->bind('event', event::class);
//8.cmd
container::getContainer()->bind('cmd', cmd::class);

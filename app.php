<?php
/**
 * app核心模块
 */
namespace rephp\framework;

use rephp\framework\helper\common;
use rephp\framework\core\appCore;
use rephp\framework\component\container\container;
//use rephp\helper;

/**
 * app核心类
 * @package framework
 */
class app
{

    public function __construct($appPath='')
    {
        $core = container::bind('appBootstrap', appCore::class);
        $core->init($appPath);
    }

    public function bind()
    {

    }

    public function run()
    {

    }

}
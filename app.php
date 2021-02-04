<?php
/**
 * app核心模块
 */
namespace rephp\framework;

use rephp\framework\helper\common;
use rephp\framework\components\bootstrap\appBootstrap;

/**
 * app核心类
 * @package framework
 */
class app
{
    public function __construct($appPath='')
    {
        appBootstrap::start($appPath);
    }

    public function run()
    {

    }

}

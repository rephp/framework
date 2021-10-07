<?php
namespace rephp\component\cmd;

use rephp\component\container\container;

class cmd
{
    /**
     * 启动
     * @return $this
     */
    public function __construct()
    {
        require 'bootstraps/console.php';
        return $this;
    }

}
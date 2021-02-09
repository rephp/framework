<?php

function dump(...$params)
{
    var_dump($params);
}

function env($name, $default='')
{
    return MillionMile\GetEnv\Env::get($name, $default);
}


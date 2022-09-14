<?php

namespace rephp\component\container\exceptions;

use Psr\Container\NotFoundExceptionInterface;
use Exception;

/**
 * Class notFoundException
 * @package rephp\component\container\exceptions
 */
class notFoundException extends Exception implements NotFoundExceptionInterface
{
}

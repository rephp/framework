<?php
namespace rephp\framework\component\container\exceptions;

use Psr\Container\NotFoundExceptionInterface;
use \Exception;

/**
 * Class notFoundException
 * @package xy\framework\component\container\exceptions
 */
class notFoundException extends Exception implements NotFoundExceptionInterface
{

}
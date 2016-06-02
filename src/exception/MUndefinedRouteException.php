<?php
namespace mtoolkit\controller\exception;

class MUndefinedRouteException extends \Exception
{
    public function __construct($route)
    {
        parent::__construct(sprintf('The route %s is not define.', $route));
    }
}
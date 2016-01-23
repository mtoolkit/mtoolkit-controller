<?php
namespace mtoolkit\controller\routing;

class UndefinedRouteException extends \Exception
{
    public function __construct($route)
    {
        parent::__construct(sprintf("The route %s is not define.", $route));
    }
}
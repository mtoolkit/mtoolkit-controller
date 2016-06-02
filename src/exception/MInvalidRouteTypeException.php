<?php

namespace mtoolkit\controller\exception;

class MInvalidRouteTypeException extends \Exception
{
    public function __construct()
    {
        parent::__construct( 'The route type in mtoolkit\controller\routing\MRoute must not be null or empty' );
    }
}
<?php

namespace mtoolkit\controller\exception;

class MInvalidMethodNameException extends \Exception
{
    public function __construct()
    {
        parent::__construct( 'The name of the method in mtoolkit\controller\routing\MRoute must not be null or empty' );
    }
}
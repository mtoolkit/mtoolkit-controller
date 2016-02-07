<?php

namespace mtoolkit\controller\routing\exception;

class MInvalidClassNameException extends \Exception
{
    public function __construct()
    {
        parent::__construct( 'The name of the class in mtoolkit\controller\routing\MRoute must not be null or empty' );
    }
}
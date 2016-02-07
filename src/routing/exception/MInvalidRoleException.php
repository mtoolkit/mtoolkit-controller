<?php

namespace mtoolkit\controller\routing\exception;

class MInvalidRoleException extends \Exception
{
    public function __construct()
    {
        parent::__construct( 'The role in mtoolkit\controller\routing\MRoute must not be null or empty' );
    }
}
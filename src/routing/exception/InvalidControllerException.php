<?php
namespace mtoolkit\controller\routing\exception;

class InvalidControllerException extends \Exception
{
    public function __construct($className)
    {
        parent::__construct(printf("%s is not a subclass of mtoolkit\controller\MAbstractController"));
    }
}
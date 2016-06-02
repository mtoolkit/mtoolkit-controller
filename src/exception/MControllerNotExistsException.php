<?php
namespace mtoolkit\controller\exception;

class MControllerNotExistsException extends \Exception
{
    public function __construct($className)
    {
        parent::__construct(printf("Class %s does not exist", $className));
    }
}
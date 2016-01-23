<?php
namespace mtoolkit\controller\routing;

use QueryPath\Exception;

class ControllerNotExistsException extends \Exception
{
    public function __construct($className)
    {
        parent::__construct(printf("Class %s does not exist", $className));
    }
}
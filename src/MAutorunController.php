<?php

namespace mtoolkit\controller;

interface MAutorunController
{
    static function autorun();
}

register_shutdown_function( array('mtoolkit\controller\MAutorunController', 'autorun') );
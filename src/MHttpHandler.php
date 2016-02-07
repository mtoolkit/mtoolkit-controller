<?php
namespace mtoolkit\controller;

/*
 * This file is part of MToolkit.
 *
 * MToolkit is free software: you can redistribute it and/or modify
 * it under the terms of the LGNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * MToolkit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * LGNU Lesser General Public License for more details.
 *
 * You should have received a copy of the LGNU Lesser General Public License
 * along with MToolkit.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author  Michele Pagnin
 */

use mtoolkit\core\MDataType;

abstract class MHttpHandler extends MAbstractController implements MAutorunController
{
    /**
     * @var string
     */
    private $output=null;
    
    public function init()
    {}
    
    public abstract function run();
    
    public static function autorun()
    {
        /* @var $classes string[] */ $classes = array_reverse( get_declared_classes() );
        
        foreach( $classes as $class )
        {
            $type = new \ReflectionClass($class);
            $abstract = $type->isAbstract();

            if( is_subclass_of( $class, 'mtoolkit\controller\MAbstractHttpHandler' ) === true && $abstract === false )
            {
                /* @var $handler MHttpHandler */
                $handler = new $class();
                $handler->init();
                $handler->run();
                
                MDataType::mustBeNullableString($handler->getOutput());
                
                if( $handler->getOutput()!=null )
                {
                    echo $handler->getOutput();
                }
                
                return;
            }
        }
    }
    
    /**
     * Returns, by reference, the string to print after the execution of the handler.
     * @return string
     */
    public function &getOutput()
    {
        return $this->output;
    }
    
    /**
     * @param string $output
     * @return \MToolkit\Controller\MHttpHandler
     */
    public function setOutput( $output )
    {
        MDataType::mustBeNullableString($output);
        
        $this->output = $output;
        return $this;
    }
}

register_shutdown_function( array(MHttpHandler::class, 'autorun') );
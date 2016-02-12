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

abstract class MHttpHandler extends MAbstractController
{
    public function init()
    {
    }

    public abstract function run();

    /**
     * @return MHttpHandler
     */
    public static function autorun()
    {
        /* @var $classes string[] */
        $classes = array_reverse( get_declared_classes() );

        foreach( $classes as $class )
        {
            $type = new \ReflectionClass( $class );
            $abstract = $type->isAbstract();

            if( is_subclass_of( $class, MAutorunController::class ) === true && $abstract === false )
            {
                /* @var $handler MHttpHandler */
                $handler = new $class();
                $handler->init();
                $handler->run();

                return $handler;
            }
        }

        return null;
    }
}

register_shutdown_function( function ()
{
    // Don't run the controller in cli mode
    if( php_sapi_name() == 'cli' )
    {
        return;
    }

    // Run the controller
    /* @var $httpHandler MHttpHandler */
    $httpHandler = MHttpHandler::autorun();

    if( $httpHandler != null )
    {
        header( 'Content-Type: ' . $httpHandler->getHttpResponse()->getContentType() );
        echo $httpHandler->getHttpResponse()->getOutput();
    }
} );
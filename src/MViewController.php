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

use mtoolkit\controller\exception\MTemplateNotFoundException;
use mtoolkit\core\MDataType;

abstract class MViewController extends MAbstractController
{
    /**
     * @var boolean
     */
    private $isVisible = true;

    /**
     * The path of the file containing the html of the controller.
     *
     * @var string
     */
    private $template = null;

    private $charset = 'UTF-8';

    /**
     * @param string $template The path of the file containing the html of the controller.
     * @param MViewController $parent
     */
    public function __construct( $template, MViewController $parent = null )
    {
        parent::__construct( $parent );

        $this->template = $template;
    }

    public function init()
    {
    }

    public function load()
    {
    }

    /**
     * The method returns the path of the html of the controler.
     *
     * @return string|null
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * The method sets the path of the html of the controler.
     *
     * @param string $template
     * @return MViewController
     */
    protected function setTemplate( $template )
    {
        MDataType::mustBeString( $template );

        $this->template = $template;

        return $this;
    }

    /**
     * The method sets the visibility of the controller.
     *
     * @return bool
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * The method returns the visibility of the controller.
     *
     * @param bool $isVisible
     * @return \MToolkit\Controller\MViewController
     */
    public function setIsVisible( $isVisible )
    {
        MDataType::mustBeBoolean( $isVisible );

        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * @return bool
     */
    public static function isPostBack()
    {
        return ( count( $_POST ) > 0 );
    }

    /**
     * If template is setted (the path of the html of controller) and if controller is visible,
     * it renders the template.
     */
    protected function render()
    {
        // It's better if the path of the template file is assigned.
        if( $this->template == null || file_exists( $this->template ) == false )
        {
            throw new MTemplateNotFoundException( ( $this->template == null ? 'null' : $this->template ) );
        }

        if( $this->isVisible === false )
        {
            return;
        }

        ob_start();

        /** @noinspection PhpIncludeInspection */
        include $this->template;

        $this->getHttpResponse()->appendOutput( ob_get_clean() );
    }

    /**
     * This method pre-renderize the controller.
     */
    protected function preRender()
    {

    }

    /**
     * This method post-renderize the controller.
     */
    protected function postRender()
    {

    }

    protected function unload()
    {

    }

    /**
     * The method calls the render methods (<i>preRender</i>,
     * <i>render</i> and <i>postRender</i>) and it prints to screen
     * the html of the controller rendered if it is visible.
     */
    public function show()
    {
        if( $this->isVisible === false )
        {
            return;
        }

        $this->init();
        $this->load();
        $this->preRender();
        $this->render();
        $this->postRender();
        $this->unload();
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function setCharset( $charset )
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * This function run the UI process of the web application.
     *
     * - Call preRender method of the last MAbstractController.
     * - Call render method of the last MAbstractController.
     * - Call postRender method of the last MAbstractController.
     * - Clean <i>$_SESSION</i>.
     *
     * @return MViewController
     * @throws \Exception when hte application try to running a non MAbstractController object.
     */
    public static function autorun()
    {
        /* @var $classes string[] */
        $classes = array_reverse( get_declared_classes() );

        foreach( $classes as $class )
        {
            $type = new \ReflectionClass( $class );
            $abstract = $type->isAbstract();

            if( is_subclass_of( $class, MViewController::class ) === true && $abstract === false )
            {
                /* @var $controller MViewController */
                $controller = new $class();

                return $controller;
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
        header( $httpHandler->getHttpResponse()->getContentType() );
        echo $httpHandler->getHttpResponse()->getOutput();
    }
} );
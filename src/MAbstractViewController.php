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

use mtoolkit\core\exception\MTemplateNotFoundException;
use mtoolkit\core\MDataType;
use mtoolkit\core\MMap;

abstract class MAbstractViewController extends MAbstractController
{
    const POST_SIGNALS = 'MToolkit\Controller\MAbstractViewController\PostSignals';

    /**
     * @var boolean
     */
    private $isVisible = true;

    /**
     * The path of the file containing the html of the controller.
     * @var string 
     */
    private $template = null;

    /**
     * It contains the controller rendered.
     * It's valorized after the call the method <i>render()<i>.
     * 
     * @var string|null 
     */
    private $output = "";

    /**
     * @var MMap[MAbstractViewController]
     */
    private $controls = null;

    /**
     * @var string An array of key => value
     */
    private $attributes = null;
    private $charset = 'UTF-8';

    /**
     * @param string $template The path of the file containing the html of the controller.
     * @param MAbstractViewController $parent
     */
    public function __construct( $template, MAbstractViewController $parent = null )
    {
        parent::__construct( $parent );

        $this->template = $template;
        $this->controls = new MMap();
        $this->attributes = new MMap();
    }

    public function init()
    {
        $this->initControls();
    }

    public function initControls()
    {
        
    }

    public function load()
    {
        
    }

    /**
     * @return MMap
     */
    public function &getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getAttributeValue( $name )
    {
        return $this->attributes->getValue( $name );
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setAttribute( $name, $value )
    {
        $this->attributes->insert( $name, $value );
    }

    public function renderAttributes()
    {
        $input = iterator_to_array( $this->attributes );
        $output = ' ' . implode( ' ', array_map( function ($v, $k)
                        {
                            return $k . '="' . $v . '"';
                        }, $input, array_keys( $input ) ) );
        return $output;
    }

    /**
     * @param string $id the id of the control
     * @return MAbstractViewController
     */
    public function getControl( $id )
    {
        MDataType::mustBeString( $id );

        if( array_key_exists( $id, $this->controls->__toArray() ) )
        {
            return $this->controls[$id];
        }

        return null;
    }

    /**
     * @param string $id
     * @param \MToolkit\Controller\MAbstractViewController $control
     */
    public function addControl( $id, MAbstractViewController $control )
    {
        MDataType::mustBeString( $id );

        $this->controls->insert( $id, $control );
    }

    /**
     * The method returns <i>$this->output</i>.
     * <i>$this->output</i> contains the controller rendered.
     * It's valorized after the call the method <i>render()<i>.
     * 
     * @return string|null
     */
    protected function getOutput()
    {
        return $this->output;
    }

    /**
     * The method sets <i>$this->output</i>.
     * <i>$this->output</i> contains the controller rendered.
     * 
     * @param string $output
     * @return \MToolkit\Controller\MAbstractViewController
     */
    protected function setOutput( $output )
    {
        MDataType::mustBeString( $output );

        $this->output = $output;

        return $this;
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
     * @return MAbstractViewController
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
     * @return \MToolkit\Controller\MAbstractViewController
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

        include $this->template;

        $this->output .= ob_get_clean();

        $this->renderControls();
    }

    public function setClass( $class )
    {
        $this->setAttribute( "class", $class );
        return $this;
    }

    public function getClass()
    {
        return $this->getAttributeValue( "class" );
    }

    public function getStyle()
    {
        return $this->getAttributeValue( "style" );
    }

    public function setStyle( $style )
    {
        $this->setAttribute( "style", $style );
        return $this;
    }

    /**
     * This method pre-renderize the controller.
     */
    protected function preRender()
    {
        
    }

    protected function renderControls()
    {
        foreach( $this->controls as $key => $value )
        {
            ob_start();
            $value->show();
            $controlRendered = ob_get_clean();

            $this->output = qp( qp( $this->output )->find( "#" . $key )->prepend( $controlRendered ) )->find( "#" . $key )->get( 2 )->remove();
        }
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

        echo $this->output;

        $this->unload();

        $this->output = "";
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

}

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

use mtoolkit\core\enum\Orientation;
use mtoolkit\model\MAbstractDataModel;

class MRepeaterView extends MAbstractViewController
{
    /**
     * @var MAbstractDataModel
     */
    private $model;
    private $headerTemplateFile = null;
    private $bodyTemplateFile = null;
    private $footerTemplateFile = null;
    private $headerTemplate = null;
    private $bodyTemplate = null;
    private $footerTemplate = null;
    private $currentRow = null;

    public function __construct( MAbstractViewController $parent = null )
    {
        parent::__construct( __DIR__ . '/MRepeaterView.php', $parent );
    }

    public function getHeaderTemplateFile()
    {
        return $this->headerTemplateFile;
    }

    public function setHeaderTemplateFile( $headerTemplateFile )
    {
        if( file_exists( $headerTemplateFile )==false )
        {
            throw new \Exception( 'The header template file ' . $headerTemplateFile . ' does not exists.' );
        }

        $this->headerTemplateFile = $headerTemplateFile;
        $this->headerTemplate = file_get_contents( $this->headerTemplateFile );
        return $this;
    }

    public function getBodyTemplateFile()
    {
        return $this->bodyTemplateFile;
    }

    public function setBodyTemplateFile( $bodyTemplateFile )
    {
        if( file_exists( $bodyTemplateFile )==false )
        {
            throw new \Exception( 'The body template file ' . $bodyTemplateFile . ' does not exists.' );
        }
        
        $this->bodyTemplateFile = $bodyTemplateFile;
        $this->bodyTemplate = file_get_contents( $this->bodyTemplateFile );
        return $this;
    }

    public function getFooterTemplateFile()
    {
        return $this->footerTemplateFile;
    }

    public function setFooterTemplateFile( $footerTemplateFile )
    {
        if( file_exists( $footerTemplateFile )==false )
        {
            throw new \Exception( 'The footer template file ' . $footerTemplateFile . ' does not exists.' );
        }

        $this->footerTemplateFile = $footerTemplateFile;
        $this->footerTemplate = file_get_contents( $this->footerTemplateFile );
        return $this;
    }

    public function getHeaderTemplate()
    {
        return $this->headerTemplate;
    }

    public function setHeaderTemplate( $headerTemplate )
    {
        $this->headerTemplate = $headerTemplate;
        $this->headerTemplateFile = null;
        return $this;
    }

    public function getBodyTemplate()
    {
        return $this->bodyTemplate;
    }

    public function setBodyTemplate( $bodyTemplate )
    {
        $this->bodyTemplate = $bodyTemplate;
        $this->bodyTemplateFile = null;
        return $this;
    }

    public function getFooterTemplate()
    {
        return $this->footerTemplate;
    }

    public function setFooterTemplate( $footerTemplate )
    {
        $this->footerTemplate = $footerTemplate;
        $this->footerTemplateFile = null;
        return $this;
    }

    /**
     * @return MAbstractDataModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param MAbstractDataModel $model
     * @return MRepeaterView
     */
    public function setModel( MAbstractDataModel $model )
    {
        $this->model = $model;
        return $this;
    }

    protected function render()
    {
        if( $this->headerTemplate!=null )
        {
            eval( "?>" . $this->headerTemplate );
        }

        for( $i = 0; $i<$this->model->rowCount(); $i++ )
        {
            $this->currentRow = $this->model->getHeaderData( $i, Orientation::VERTICAL );
                        
            eval( "?>" . $this->bodyTemplate );
        }

        if( $this->footerTemplate!=null )
        {
            eval( "?>" . $this->footerTemplate );
        }
    }

    public function getItem( $itemName = null )
    {
        return $this->model->getData( $this->currentRow, $itemName );
    }

}

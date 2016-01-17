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

class MHttpResponse
{
    private $contentType='text/html';
    
    /**
     * Redirects a client to a new URL. <br />
     * Specifies the new URL and whether execution of the current page should terminate.<br />
     * The redirect will not be done in case of a PHP error or PHP warning.
     * 
     * @param string $url
     * @param boolean $endResponse Default <i>true</i>
     */
    public function redirect($url, $endResponse=true)
    {
        $lastError=  error_get_last();
        if( $lastError["type"]==E_ERROR || $lastError["type"]==E_WARNING )
        {
            return;
        }
        
        header('location: ' . $url);
        
        if( $endResponse===true )
        {
            die();
        }
    }
    
    /**
     * Clears all content output from the buffer stream.
     */
    public function clear()
    {
        ob_get_clean();
    }
    
    /**
     * Gets the HTTP MIME type of the output stream.
     * 
     * @return string|null
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Sets the HTTP MIME type of the output stream.
     * 
     * @param string $contentType
     * @return \MToolkit\Controller\MHttpResponse
     */
    public function setContentType( $contentType )
    {
        $this->contentType = $contentType;
        return $this;
    }
}

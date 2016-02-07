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

/**
 * The classe MAbstractMasterPageController rappresents a base class for
 * master page classes. <br />
 * <br />
 * A master page contains the parts of a web page repeated in more pages of the
 * web site/web application. <br />
 * <br />
 * The notion of mster page for MToolkit is similar to ASP.Net.
 * 
 * @link http://msdn.microsoft.com/en-us/library/system.web.ui.masterpage.aspx ASP.Net notion for master page.
 */
class MMasterPageController extends MPageController
{
    /**
     * Constructs an master page controller with the given <i>$template</i> and <i>$parent</i>.
     *
     * @param string $template        The path to the template of the master page. Must be set.
     * @param MPageController $parent Must be set.
     */
    public function __construct( $template, MPageController $parent )
    {
        parent::__construct( $template, $parent );
        
        if( $parent==null )
        {
            trigger_error( sprintf( 'The parent of the master page is not set in %s. You must set it for a correct execution of the render process.', get_class( $this )), E_USER_WARNING);
        }
    }
}

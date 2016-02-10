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

use mtoolkit\core\MObject;

/**
 * MAbstractController class provides a base methods
 * for the controller classes. <br />
 */
abstract class MAbstractController extends MObject
{
    private $parent;

    public function __construct( MAbstractController $parent = null )
    {
        $this->parent = $parent;
    }

    /**
     * @var MHttpResponse
     */
    private $httpResponse = null;

    /**
     * @return MHttpResponse
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

}

<?php
namespace mtoolkit\controller\routing;

use mtoolkit\controller\MAbstractController;
use mtoolkit\controller\routing\exception\MControllerNotExistsException;
use mtoolkit\controller\routing\exception\MInvalidClassNameException;
use mtoolkit\controller\routing\exception\MInvalidControllerException;
use mtoolkit\controller\routing\exception\MInvalidMethodNameException;
use mtoolkit\controller\routing\exception\MInvalidRoleException;
use mtoolkit\controller\routing\exception\MInvalidRouteTypeException;
use mtoolkit\controller\routing\exception\MUndefinedRouteException;
use mtoolkit\core\MMap;
use mtoolkit\core\MString;

final class MRouting
{

    /**
     * @var MRoutingInstace
     */
    private static $instance = null;

    private static function getInstance()
    {
        if( self::$instance == null )
        {
            self::$instance = new MRoutingInstace();
        }

        return self::$instance;
    }

    /**
     * Defines the root of the route list.
     *
     * @param $root
     */
    public static function setRoot( $root )
    {
        self::getInstance()->setRoot( $root );
    }

    /**
     * Add a new route.
     *
     * @param MRoute $r
     */
    public static function add( MRoute $r )
    {
        self::getInstance()->add( $r );
    }

    /**
     * Executes the routing.
     *
     * @throws MControllerNotExistsException
     * @throws MInvalidControllerException
     * @throws MUndefinedRouteException
     */
    public static function run()
    {
        self::getInstance()->run();
    }

}

/**
 * Don't use this class.
 *
 * @private
 * @package mtoolkit\controller\routing
 */
class MRoutingInstace
{
    /**
     * @var MMap
     */
    private $routeList = null;

    /**
     * @var string
     */
    private $root = "";

    public function __construct()
    {
        $this->root = dirname( $_SERVER['SCRIPT_NAME'] );
        $this->routeList = new MMap();
    }

    /**
     * Finds the exaclty correspondency between the requested routes and the ones defined.<br>
     * If the route exists, it will be execute, otherwise an exception will be throw.
     *
     * @throws MControllerNotExistsException
     * @throws MInvalidControllerException
     * @throws MUndefinedRouteException
     */
    public function run()
    {
        $requestType = strtolower( $_SERVER['REQUEST_METHOD'] );
        /* @var $requestedRoute MRoute */
        $requestedRoute = $this->routeList->getValue( $this->getRequestedRole() );

        if( $requestedRoute === null )
        {
            throw new MUndefinedRouteException( $this->getRequestedRole() );
        }

        if( $requestedRoute->getType() != MRouteType::CONTROLLER && $requestedRoute->getType() != MRouteType::ALL && $requestedRoute->getType() !== $requestType )
        {
            throw new MUndefinedRouteException( $this->getRequestedRole() );
        }

        $className = $requestedRoute->getClass();
        $methodName = $requestedRoute->getMethod();

        if( !class_exists( $className ) )
        {
            throw new MControllerNotExistsException( $className );
        }

        // If the class is not a {@link MAbstractController} will be run the method defined in the root.
        if( $requestedRoute->getType() !== MRouteType::CONTROLLER )
        {
            $controller = new $className();

            if( $controller instanceof MAbstractController )
            {
                $controller->$methodName();
            }
            else
            {
                throw new MInvalidControllerException( $className );
            }
        }
    }

    /**
     * Sets the root of the routes.
     *
     * @param $root
     */
    public function setRoot( $root )
    {
        $this->$root = $root;
    }

    /**
     * Adds a new definition of Route.
     *
     * @param MRoute $route
     * @throws MInvalidClassNameException
     * @throws MInvalidMethodNameException
     * @throws MInvalidRoleException
     * @throws MInvalidRouteTypeException
     */
    public function add( MRoute $route )
    {
        $this->validateRoute( $route );

        $this->routeList->insert( $route->getRole(), $route );
    }

    /**
     * Validate the <i>$route</i>.<br>
     * If the route has some invalida data, an exception will be throw.<br>
     * The class name, the type and the role must not be null or empty.<br>
     * If the type is equal to MRouteType::CONTROLLER, the method property must not be null or empty.<br>
     *
     * @param MRoute $route
     * @throws MInvalidClassNameException
     * @throws MInvalidMethodNameException
     * @throws MInvalidRoleException
     * @throws MInvalidRouteTypeException
     */
    private function validateRoute( MRoute $route )
    {
        if( MString::isNullOrEmpty( $route->getType() ) )
        {
            throw new MInvalidRouteTypeException();
        }

        if( MString::isNullOrEmpty( $route->getClass() ) )
        {
            throw new MInvalidClassNameException();
        }

        if( $route->getType() != MRouteType::CONTROLLER && MString::isNullOrEmpty( $route->getMethod() ) )
        {
            throw new MInvalidMethodNameException();
        }

        if( MString::isNullOrEmpty( $route->getRole() ) )
        {
            throw new MInvalidRoleException();
        }
    }

    /**
     * Returns the requested role (URI), without the query strings.
     *
     * @return string
     */
    private function getRequestedRole()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if( $this->root != '/' && substr( $uri, 0, strlen($this->root) ) === $this->root )
        {
            $uri = substr( $uri, strlen( $this->root ) );
        }

        $questionMarkPosition = strpos( $uri, '?' );
        if( $questionMarkPosition !== false )
        {
            $uri = substr( $uri, 0, $questionMarkPosition );
        }

        return $uri;
    }
}
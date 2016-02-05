<?php
namespace mtoolkit\controller\routing;

use mtoolkit\controller\MAbstractController;
use mtoolkit\controller\routing\exception\ControllerNotExistsException;
use mtoolkit\controller\routing\exception\EmptyRouteTypeException;
use mtoolkit\controller\routing\exception\InvalidControllerException;
use mtoolkit\controller\routing\exception\UndefinedRouteException;
use mtoolkit\core\MString;

final class Routing
{
    private static $getRouteList = array();
    private static $postRouteList = array();
    private static $putRouteList = array();
    private static $deleteRouteList = array();
    private static $allRouteList = array();
    private static $root = "";

    public static function setRoot( $root )
    {
        self::$root = $root;
    }

    public static function add( Route $r )
    {
        if( MString::isNullOrEmpty( $r->getType() ) )
        {
            throw new EmptyRouteTypeException();
        }

        switch( $r->getType() )
        {
            case RouteType::GET:
                self::$getRouteList[$r->getRole()] = $r;
                break;
            case RouteType::POST:
                self::$postRouteList[$r->getRole()] = $r;
                break;
            case RouteType::PUT:
                self::$putRouteList[$r->getRole()] = $r;
                break;
            case RouteType::DELETE:
                self::$deleteRouteList[$r->getRole()] = $r;
                break;
            case RouteType::ALL:
            case RouteType::CONTROLLER:
                self::$allRouteList[$r->getRole()] = $r;
                break;
        }
    }

    public static function run()
    {
        $requestType = strtolower( $_SERVER['REQUEST_METHOD'] );
        $routeList = self::getRouteList( $requestType );
        $role = self::getRole();

        if( array_key_exists( $role, $routeList ) === false )
        {
            throw new UndefinedRouteException( $role );
        }

        /* @var $route Route */
        $route = $routeList[$role];
        $className = $route->getClass();
        $methodName = $route->getMethod();
        $classPath = $route->getClassPath();

        if( $route->getType() === RouteType::CONTROLLER )
        {
            require_once $classPath;
            return;
        }

        if( !class_exists( $className ) )
        {
            throw new ControllerNotExistsException( $className );
        }

        $controller = new $className();

        if( $controller instanceof MAbstractController )
        {
            $controller->$methodName();
        }
        else
        {
            throw new InvalidControllerException( $className );
        }
    }

    private static function getRole()
    {
        $root = dirname( $_SERVER['SCRIPT_NAME'] );
        $role = $_SERVER['REQUEST_URI'];

        if( $root != '/' )
        {
            $role = substr( $role, strlen( $root ) );
        }

        return $role;
    }

    private static function getRouteList( $routeType )
    {
        switch( $routeType )
        {
            case RouteType::GET:
                return array_merge( self::$getRouteList, self::$allRouteList );
            case RouteType::POST:
                return array_merge( self::$postRouteList, self::$allRouteList );
            case RouteType::PUT:
                return array_merge( self::$putRouteList, self::$allRouteList );
            case RouteType::DELETE:
                return array_merge( self::$deleteRouteList, self::$allRouteList );
            default:
                return array();
        }
    }
}
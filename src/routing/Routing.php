<?php
namespace mtoolkit\controller\routing;

use mtoolkit\controller\MAbstractController;
use mtoolkit\core\MString;

final class Routing
{
    private static $getRouteList = array();
    private static $postRouteList = array();
    private static $putRouteList = array();
    private static $deleteRouteList = array();
    private static $allRouteList = array();
    private static $customRouteList = array();
    private static $root = "";

    public static function setRoot($root)
    {
        self::$root = $root;
    }

    public static function add(Route $r)
    {
        if (MString::isNullOrEmpty($r->getType())) {
            throw new EmptyRouteTypeException();
        }

        switch ($r->getType()) {
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
                self::$allRouteList[$r->getRole()] = $r;
                break;
            default:
                self::$customRouteList[$r->getRole()] = $r;
                break;
        }
    }

    public static function run()
    {
        $requestType = strtolower($_SERVER['REQUEST_METHOD']);
        $routeList = self::getRouteList($requestType);
        $role = self::getRole();

        if (array_key_exists($role, $routeList) === false) {
            throw new UndefinedRouteException($role);
        }

        /* @var $route Route */
        $route = $routeList[$role];
        $className = $route->getClass();
        $methodName = $route->getMethod();

        if (!class_exists($className)) {
            throw new ControllerNotExistsException($className);
        }

        $controller = new $className();

        if ($controller instanceof MAbstractController) {
            $controller->$methodName();
        } else {
            throw new InvalidControllerException($className);
        }
    }

    private static function getRole()
    {
        $root = dirname($_SERVER['SCRIPT_NAME']);
        $role = $_SERVER['REQUEST_URI'];

        if ($root != '/') {
            $role = substr($role, strlen($root));
        }

        return $role;
    }

    private static function getRouteList($routeType)
    {
        switch ($routeType) {
            case RouteType::GET:
                return self::$getRouteList;
            case RouteType::POST:
                return self::$postRouteList;
            case RouteType::PUT:
                return self::$putRouteList;
            case RouteType::DELETE:
                return self::$deleteRouteList;
            case RouteType::ALL:
                return self::$allRouteList;
            default:
                return self::$customRouteList;
        }
    }
}
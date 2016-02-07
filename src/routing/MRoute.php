<?php
namespace mtoolkit\controller\routing;

final class MRoute
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $method;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    private function __construct()
    {
    }

    public static function create( $type, $role, $class, $method )
    {
        $route = new MRoute();

        $route->type = $type;
        $route->role = $role;
        $route->class = $class;
        $route->method = $method;

        return $route;
    }

    public static function get( $role, $class, $method )
    {
        return self::create( MRouteType::GET, $role, $class, $method );
    }

    public static function post( $role, $class, $method )
    {
        return self::create( MRouteType::GET, $role, $class, $method );
    }

    public static function put( $role, $class, $method )
    {
        return self::create( MRouteType::GET, $role, $class, $method );
    }

    public static function delete( $role, $class, $method )
    {
        return self::create( MRouteType::GET, $role, $class, $method );
    }

    public static function all( $role, $class, $method )
    {
        return self::create( MRouteType::ALL, $role, $class, $method );
    }

    public static function page( $role, $class )
    {
        $route = new MRoute();
        $route->type = MRouteType::CONTROLLER;
        $route->role = $role;
        $route->class = $class;

        return $route;
    }
}
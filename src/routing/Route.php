<?php
namespace mtoolkit\controller\routing;

final class Route
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
     * @var string
     */
    private $classPath;

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

    /**
     * @return string
     */
    public function getClassPath()
    {
        return $this->classPath;
    }

    private function __construct()
    {
    }

    public static function create( $type, $role, $class, $method )
    {
        $route = new Route();

        $route->type = $type;
        $route->role = $role;
        $route->class = $class;
        $route->method = $method;

        return $route;
    }

    public static function get( $role, $class, $method )
    {
        return self::create( RouteType::GET, $role, $class, $method );
    }

    public static function post( $role, $class, $method )
    {
        return self::create( RouteType::GET, $role, $class, $method );
    }

    public static function put( $role, $class, $method )
    {
        return self::create( RouteType::GET, $role, $class, $method );
    }

    public static function delete( $role, $class, $method )
    {
        return self::create( RouteType::GET, $role, $class, $method );
    }

    public static function all( $role, $class, $method )
    {
        return self::create( RouteType::ALL, $role, $class, $method );
    }

    public static function page( $role, $classPath )
    {
        $route = new Route();
        $route->type = RouteType::CONTROLLER;
        $route->role = $role;
        $route->classPath = $classPath;

        return $route;
    }
}
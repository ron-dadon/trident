<?php

/**
 * Class Trident_Router
 *
 * Router class for Trident Framework.
 * This class is responsible for matching the requested uri with an application route, and dispatching the route
 * if one is found, or throwing an error if none is found.
 * The class searches the routes in the order they are set in the routes file, and searches for the first match,
 * if one is found, the search stops and the router dispatches the matched route.
 */
class Trident_Router
{

    /**
     * Routes data
     *
     * @var array
     */
    private $_routes = [];

    /**
     * Constructor
     *
     * Load routes file if $file is specified.
     *
     * @param null|string $file file path
     *
     * @throws Trident_Exception
     */
    function __construct($file = null)
    {
        if (!is_null($file))
        {
            $this->_load($file);
        }
    }

    /**
     * Load routes file.
     *
     * @param string $file file path
     *
     * @throws Trident_Exception
     */
    private function _load($file)
    {
        if (!is_readable($file))
        {
            throw new Trident_Exception("Router can't load file $file. The file doesn't exists or is not readable");
        }
        $data = file_get_contents($file);
        $data = json_decode($data, true);
        foreach ($data as $route)
        {
            $this->_routes[] = new Trident_Route($route['controller'], $route['function'], $route['pattern']);
        }
    }

    /**
     * Search routes for a match to $uri.
     *
     * @param string $uri request uri
     *
     * @return null|Trident_Route
     */
    private function _match_route($uri)
    {
        $uri = '/' . trim($uri, '/');
        /** @var Trident_Route $route */
        foreach ($this->_routes as $key => $route)
        {
            if (preg_match($route->pattern, $uri, $parameters))
            {
                $dispatch_parameters = [];
                foreach ($route->parameters as $parameter)
                {
                    $dispatch_parameters[] = $parameters[$parameter];
                }
                $route->parameters = $dispatch_parameters;
                return $route;
            }
        }
        return null;
    }

    /**
     * Dispatch the route
     *
     * @param string $uri request uri
     */
    public function dispatch($uri)
    {
        if (($route = $this->_match_route($uri)) !== null)
        {
            // TODO: implement dispatch match
        }
        else
        {
            // TODO: implement dispatch no match
        }
    }
}
<?php

/**
 * Class Trident_Router
 *
 * Routing handling.
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
     * @param Trident_Request $request
     * @param Trident_Configuration $configuration
     * @param Trident_Session $session
     *
     * @throws Trident_Exception
     * @internal param string $uri request uri
     *
     */
    public function dispatch($request, $configuration, $session)
    {
        if (($route = $this->_match_route($request->uri)) !== null)
        {
            if (strtolower(substr($route->controller, -11, 11)) !== '_controller')
            {
                $controller = $route->controller . '_controller';
            }
            else
            {
                $controller = $route->controller;
            }
            $controller = new $controller($configuration, $request, $session);
            if (call_user_func_array([$controller, $route->function], $route->parameters) === false)
            {
                $route = $route->pattern;
                throw new Trident_Exception("Error dispatching route $route", TRIDENT_ERROR_DISPATCH_ROUTE);
            }
        }
        else
        {
            throw new Trident_Exception("Can't find matching route", TRIDENT_ERROR_NO_MATCHED_ROUTE);
        }
    }
}
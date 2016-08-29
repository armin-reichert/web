<?php
namespace AFD\Controller;

use AFD\Controller\Request;

/**
 * Dispatches requests to controller methods using the specified routing information.
 *
 * @author Armin Reichert
 */
class Dispatcher
{

    const MAX_REDIRECTS = 10;

    /**
     *
     * @var \AFD\Controller\Route[]
     */
    private $routes = [];

    /**
     * Constructor.
     * 
     * @param string $routes
     *            route configuration file
     */
    public function __construct($routes)
    {
        if (! is_file($routes)) {
            throw new \Exception("Invalid application route configuration");
        }
        $this->addRoutes($routes);
    }

    /**
     * Adds the routes defined in the given configuration file.
     *
     * @param string $routingConfigFile
     *            path to route configuration file
     * @throws \Exception
     */
    private function addRoutes($routingConfigFile)
    {
        if (! is_file($routingConfigFile)) {
            throw new \Exception("Invalid routing configuration, not a file");
        }
        $routingConfigFile = realpath($routingConfigFile);
        $routes = require $routingConfigFile;
        if (! is_array($routes)) {
            throw new \Exception("Invalid routing configuration, no array returned");
        }
        foreach ($routes as $route) {
            if (! $route instanceof Route) {
                throw new \Exception(sprintf("Illegal entry in routing config file '%s': '%s'", $routingConfigFile, $route));
            }
            $this->routes[] = $route;
        }
    }

    /**
     * Uses the routing configuration to find the controller for handling the current
     * request and runs it.
     *
     * @param \AFD\Controller\Request $request
     *            the HTTP request to be dispatched
     * @return \AFD\View\Renderable content produces by controller instance for handling the request
     * @throws \Exception
     */
    public function dispatch(Request $request)
    {
        $route = $this->findControllerRoute($request);
        return $this->runController($route, $request);
    }

    /**
     *
     * @param \AFD\Controller\Request $request
     */
    private function findControllerRoute(Request $request)
    {
        $redirectCount = 0;
        while (true) {
            $route = $this->findMatchingRoute($request);
            if ($route == null) {
                throw new \Exception("No route found for request");
            }
            if ($route instanceof ControllerRoute) {
                return $route;
            }
            if ($route instanceof RedirectRoute) {
                $route->redirect($request);
                if (++ $redirectCount > self::MAX_REDIRECTS) {
                    throw new \Exception("No route found for request. Cyclic redirect?");
                }
            }
        }
    }

    /**
     *
     * @param \AFD\Controller\Request $request
     * @param \AFD\PropertyBag $params
     */
    private function findMatchingRoute(Request $request)
    {
        foreach ($this->routes as $route) {
            if ($route->matches($request)) {
                return $route;
            }
        }
        return null;
    }

    /**
     *
     * @param \AFD\Controller\ControllerRoute $route
     * @param \AFD\Controller\Request $request
     * @return \AFD\View\Renderable
     */
    private function runController(ControllerRoute $route, Request $request)
    {
        if (! class_exists($route->controllerClass)) {
            throw new \Exception("Controller class does not exist: " . $route->controllerClass);
        }
        $controller = new $route->controllerClass();
        $controller->action = $route->method;
        $controller->request = $request;
        $method = new \ReflectionMethod($controller, $route->method);
        $params = [];
        foreach ($route->params->keys() as $name) {
            if ($request->query->has($name)) {
                $params[$name] = $request->query->get($name);
            } else {
                $params[$name] = $route->params->get($name);
            }
        }
        return $method->invokeArgs($controller, $params);
    }
}
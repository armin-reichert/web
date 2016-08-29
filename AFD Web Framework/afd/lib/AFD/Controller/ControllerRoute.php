<?php
namespace AFD\Controller;

use AFD\PropertyBag;

/**
 * A route to a controller method ("action").
 *
 * @author Armin Reichert
 */
class ControllerRoute extends Route
{

    /**
     * The fully-qualified name of the controller class.
     *
     * @var string
     */
    public $controllerClass;

    /**
     * The name of the controller method.
     *
     * @var string
     */
    public $method;

    /**
     * The controller method parameters.
     *
     * @var AFD\PropertyBag
     */
    public $params;

    /**
     * Constructor.
     *
     * @param string $pattern
     *            route path pattern
     * @param string $controllerClass
     *            fully qualified controller class name
     * @param string $method
     *            name of controller method
     * @param array $params
     *            arguments passed to controller method
     */
    public function __construct($pattern, $controllerClass, $method = 'show', array $params = [])
    {
        parent::__construct($pattern);
        $this->controllerClass = $controllerClass;
        $this->method = $method;
        $this->params = new PropertyBag($params);
    }
}
<?php
namespace AFD\Controller;

use AFD\PropertyBag;

/**
 * A redirect to another route.
 *
 * @author Armin Reichert
 */
class RedirectRoute extends Route
{

    /**
     * The parameter patterns against which the query parameters of the request are matched.
     *
     * @var AFD\PropertyBag
     */
    public $paramPatterns;

    /**
     * The target rout of this redirect.
     *
     * @var string
     */
    public $target;

    /**
     * Target parameters.
     *
     * @var array
     */
    public $targetParameters;

    /**
     * Constructor.
     *
     * @param string $pattern
     *            URL path pattern
     * @param array $paramPatterns
     *            query parameters
     * @param string $target
     *            target route path
     */
    public function __construct($pattern, array $paramPatterns, $target, $targetParameters = [])
    {
        parent::__construct($pattern);
        $this->paramPatterns = new PropertyBag($paramPatterns);
        $this->target = $target;
        $this->targetParameters = $targetParameters;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \AFD\Controller\Route::matches()
     */
    public function matches(Request $request)
    {
        if (! parent::matches($request)) {
            return false;
        }
        // check that all required query parameters exist and the value matches the required pattern
        foreach ($this->paramPatterns->keys() as $name) {
            $pattern = '#^' . $this->paramPatterns->get($name) . '$#';
            if (! $request->query->has($name) && ! preg_match($pattern, '')) {
                return false; // mandatory request parameter is missing
            }
            if ($request->query->has($name) && ! preg_match($pattern, $request->query->get($name))) {
                return false; // parameter is set but value does not match
            }
        }
        return true;
    }

    /**
     * Applies this redirect to the given request.
     *
     * @param Request $request
     *            the request to redirect
     */
    public function redirect(Request $request)
    {
        $request->path = $this->target;
        foreach ($this->targetParameters as $name => $value) {
            $request->query->set($name, $value);
        }
    }
}

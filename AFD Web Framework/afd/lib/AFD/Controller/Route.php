<?php
namespace AFD\Controller;

/**
 * Base class for route definitions.
 *
 * @author Armin Reichert
 */
class Route
{

    /**
     *
     * @var string
     */
    protected $pattern;

    /**
     * Constructor.
     *
     * @param string $pattern
     *            route pattern without start and end-delimiters, for example '/orchestra'
     */
    public function __construct($pattern)
    {
        $this->pattern = '#^' . $pattern . '$#';
    }

    /**
     * Checks if this route matches the given request.
     *
     * @param \AFD\Controller\Request $request
     *            request to match route against
     * @return boolean tells if the route matches the request
     */
    public function matches(Request $request)
    {
        return (boolean) preg_match($this->pattern, $request->path);
    }
}
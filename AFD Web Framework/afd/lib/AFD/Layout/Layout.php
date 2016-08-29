<?php
namespace AFD\Layout;

use AFD\View\Renderable;

/**
 * Base layout class.
 *
 * @author Armin Reichert
 */
abstract class Layout
{

    /**
     * Mapping of layout positions to their content.
     *
     * @var array
     */
    protected $positions = [];

    /**
     * Applies this layout to the given content.
     *
     * @param string|AFD\View\Renderable $content
     *            the content to be decorated by this layout
     * @return string|AFD\View\Renderable the decorated content
     */
    public abstract function apply($content);

    /**
     * Sets the content for the given position.
     *
     * @param string $position
     *            identifier for layout position
     * @param string|AFD\View\Renderable $content
     *            content for layout position
     */
    public function setContent($position, $content)
    {
        if (! is_string($position)) {
            throw new \Exception("Layout position must be identified by a string");
        }
        if (! (is_string($content) || $content instanceof Renderable)) {
            throw new \Exception("Layout position can only be populated by a string or renderable object");
        }
        $this->positions[$position] = $content;
    }

    /**
     * Gets the content for the given position.
     *
     * @param string $position
     *            identifier for layout position
     * @return string|AFD\View\Renderable $content content for layout position
     */
    public function getContent($position)
    {
        if (! $position instanceof string) {
            throw new \Exception("Layout position must be identified by a string");
        }
        return $this->positions[$position];
    }
}
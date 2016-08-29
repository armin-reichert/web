<?php
namespace AFD\View;

/**
 * Abstract base class for renderable content.
 *
 * @author Armin Reichert
 */
abstract class Renderable
{

    /**
     * Renders the content to the output buffer.
     */
    abstract public function render();

    /**
     * Returns the content as a string.
     *
     * @return string the content as a string
     */
    public function getContent()
    {
        ob_start();
        $this->render();
        return ob_get_clean();
    }
}
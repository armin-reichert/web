<?php
namespace AFD\Components\Infobox;

use AFD\View\Renderable;
use AFD\View\Template;

/**
 * Represents an infobox as shown in the right pane of the layout.
 *
 * @author Armin Reichert
 */
class Infobox extends Renderable
{

    /**
     *
     * @var string
     */
    private $title;

    /**
     *
     * @var string AFD\View\Renderable
     */
    private $content;

    /**
     * Constructor.
     *
     * @param string $title
     *            the infobox title
     * @param string|AFD\View\Renderable $content
     *            the infobox content
     */
    public function __construct($title = "", $content = "")
    {
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Sets the content.
     *
     * @param string|AFD\View\Renderable $content
     *            the content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     *            the infobox title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /*
     * (non-PHPdoc) @see \AFD\View\Renderable::render()
     */
    public function render()
    {
        $view = new Template(__DIR__ . '/InfoboxView.php', [
            'title' => $this->title,
            'content' => $this->content
        ]);
        $view->render();
    }
}
<?php
namespace AFD\Components\YouTubeVideo;

use AFD\View\Renderable;
use AFD\View\Template;

/**
 * Component for embedding a YouTube hosted video.
 *
 * @author Armin Reichert
 *
 */
class YouTubeVideo extends Renderable
{

    private $id;

    private $width;

    private $height;

    public function __construct($videoID, $width, $height)
    {
        $this->id = $videoID;
        $this->width = $width;
        $this->height = $height;
    }

    /*
     * (non-PHPdoc) @see \AFD\View\Renderable::render()
     */
    public function render()
    {
        $view = new Template(__DIR__ . '/YouTubeVideoView.php', [
            'width' => $this->width,
            'height' => $this->height,
            'videoID' => $this->id
        ]);
        $view->render();
    }
}
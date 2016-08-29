<?php
namespace AFD\Layout\Simple;

use AFD\Components\Breadcrumb\Breadcrumb;
use AFD\Controller\App;
use AFD\View\Template;
use AFD\Layout\Layout;

/**
 * This class implements a simple site layout with the following areas:
 * "title", "subtitle", "topnavigation", "rightpane", "footer", "script_calls".
 *
 * Additionally, this layout provides favicon support and a breadcrumb component.
 *
 * @author Armin Reichert
 */
class SimpleLayout extends Layout
{

    /**
     * (non-PHPdoc)
     *
     * @see \AFD\View\Layout::apply()
     */
    public function apply($content)
    {
        list ($css, $scripts) = App::app()->getAssets();
        $document = new Template(__DIR__ . '/SimpleLayout.html.php', [
            'css' => $css,
            'scripts' => $scripts,
            'breadcrumb' => new Breadcrumb(),
            'content' => $content
        ]);
        $favicon = App::app()->appURL('/assets/images/favicon.ico');
        if ($favicon) {
            $document->set('favicon', $favicon);
        }
        foreach ($this->positions as $pos => $content) {
            $document->set($pos, $content);
        }
        return $document;
    }
}
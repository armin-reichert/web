<?php
namespace AFD\Components\Breadcrumb;

use AFD\Controller\App;
use AFD\View\Renderable;
use AFD\View\Template;

/**
 * Breadcrumb navigation component.
 *
 * @author Armin Reichert
 */
class Breadcrumb extends Renderable
{

    public function __construct()
    {}

    /*
     * (non-PHPdoc) @see \AFD\View\Renderable::render()
     */
    public function render()
    {
        $path = $this->computePath();
        if (count($path) > 0) {
            $view = new Template(__DIR__ . '/BreadcrumbView.php', [
                'path' => $path
            ]);
            $view->render();
        }
    }

    private function computePath()
    {
        $request = App::app()->getRequest();
        $nodes = [];
        $nodes[] = [
            'text' => 'Startseite',
            'url' => App::app()->controllerURL('')
        ];
        if ('/pages' === $request->path) {
            if ($request->query->has('page')) {
                $pageId = '';
                $pageRootURL = App::app()->controllerURL('/pages');
                $levels = explode('/', $request->query->get('page'));
                for ($i = 0; $i < count($levels) - 1; ++ $i) {
                    $level = $levels[$i];
                    if (! empty($pageId)) {
                        $pageId .= '/';
                    }
                    $pageId .= $level;
                    $nodes[] = [
                        'text' => $this->beautify($level),
                        'url' => $pageRootURL . '?page=' . $pageId
                    ];
                }
            }
        } else {
            // TODO
        }
        return $nodes;
    }

    /**
     * Converts texts like '2011_freya_wagner' into more readable '2011 Freya Wagner'
     *
     * @param string $text
     * @return string beautified text
     */
    private function beautify($text)
    {
        return join(' ', array_map(function ($piece)
        {
            return ucfirst($piece);
        }, explode('_', $text)));
    }
}
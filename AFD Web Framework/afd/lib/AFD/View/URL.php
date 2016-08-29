<?php
namespace AFD\View;

use AFD\Controller\App;

/**
 * Provides utility methods for URL composition.
 *
 * @author Armin Reichert
 *
 */
trait URL
{

    public function appURL($path)
    {
        return App::app()->appURL($path);
    }

    public function docURL($dir)
    {
        return App::app()->docURL($dir);
    }

    public function controllerURL($route, $params = array())
    {
        return App::app()->controllerURL($route, $params);
    }
}
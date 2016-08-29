<?php
/*
 * Front controller of the web application.
 */
define('DS', DIRECTORY_SEPARATOR);
define('AFD', __DIR__ . DS . 'afd');
define('APP', __DIR__ . DS . 'app');

if ('localhost' === $_SERVER['HTTP_HOST']) {
    error_reporting(E_ALL);
}

require_once 'Autoloader.php';
use AFD\Controller\App;
use AFD\Controller\Request;

App::app()->run(new Request($_SERVER['SCRIPT_NAME']));
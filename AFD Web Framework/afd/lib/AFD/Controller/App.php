<?php
namespace AFD\Controller;

use AFD\Controller\Dispatcher;
use AFD\Controller\Request;
use AFD\View\Response;
use AFD\Layout\Layout;

/**
 * The web application.
 *
 * The singleton instance of this class is created on every request by the
 * front controller. Every request is processed by this class.
 *
 * @author Armin Reichert
 */
class App
{

    /**
     *
     * @var \AFD\Controller\App
     */
    private static $app;

    /**
     *
     * @var array
     */
    private $config;

    /**
     *
     * @var \AFD\Controller\Request
     */
    private $request;

    /**
     * Returns the (singleton) application instance.
     *
     * @return application instance
     */
    public static function app()
    {
        if (! isset(self::$app)) {
            self::$app = new App();
        }
        return self::$app;
    }

    /**
     * Constructor.
     *
     * Creates the application in the given directory which must conform to the AFD conventions.
     */
    private function __construct()
    {
        $configFile = APP . '/config/config.php';
        if (! is_file($configFile)) {
            throw new \Exception("Missing application configuration file");
        }
        $this->config = require $configFile;
        if (! is_array($this->config)) {
            throw new \Exception("Invalid application configuration");
        }
    }

    /**
     * Returns the request object.
     *
     * @return \AFD\Controller\Request the request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Processes the request by dispatching it to the configured controller method and renders the
     * response object.
     */
    public function run($request)
    {
        $this->request = $request;
        try {
            $dispatcher = new Dispatcher(APP . '/config/routes.php');
            $content = $dispatcher->dispatch($this->request);
            $content = $this->applyLayout($content);
            $response = new Response($content, 200);
        } catch (\Exception $e) {
            $msg = "<h1>Sou gäähdet net!</h1><p><b>%s</b></p><pre>%s</pre>";
            $response = new Response(sprintf($msg, $e->getMessage(), $e->getTraceAsString()), 500);
        }
        $response->render();
    }

    /**
     * Executes an external redirect to the given route.
     *
     * @param string $route
     *            a valid route to some controller
     * @param array $params
     *            key/value pairs which will become URL parameters
     */
    public function redirect($route, $params = [])
    {
        header("Location: " . $this->controllerURL($route, $params));
    }

    /**
     * Returns the application configuration value for the given key
     *
     * @param string $key
     *            configuration key
     * @return mixed the configuration value
     */
    public function getConfigValue($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        throw new \Exception("Invalid configuration key: " . $key);
    }

    /**
     */
    public function appURL($pathFromAppRoot = '')
    {
        return $this->docURL(realpath(APP . DS . $pathFromAppRoot));
    }

    /**
     */
    public function controllerURL($route, $params = [])
    {
        $path = $this->request->pathPrefix . $route;
        if (count($params) > 0) {
            $path .= '?' . http_build_query($params);
        }
        return 'http://' . $_SERVER['HTTP_HOST'] . $path;
    }

    /**
     */
    public function docURL($path)
    {
        $path = str_replace('\\', '/', $path);
        if (0 === strpos($path, $_SERVER['DOCUMENT_ROOT'])) {
            return 'http://' . $_SERVER['HTTP_HOST'] . substr($path, strlen($_SERVER['DOCUMENT_ROOT']));
        }
        throw new \Exception("Cannot create URL for path: " . $path);
    }

    /**
     * Decorates the content using the assigned layout.
     *
     * @param mixed $content
     *            content without layout
     * @return \AFD\View\Renderable content decorated by layout
     */
    private function applyLayout($content)
    {
        $layoutClass = $this->getConfigValue('layout');
        $layout = new $layoutClass();
        if (! $layout instanceof Layout) {
            throw new \Exception("Invalid layout class: " . $layoutClass);
        }
        return $layout->apply($content);
    }

    /**
     * Returns an array containing the array of stylesheets and the array of scripts needed for the final document.
     *
     * @return array(array,array)
     */
    public function getAssets()
    {
        $css = [];
        $scripts = [];
        foreach ($this->config['assets'] as $assetID => $assets) {
            if (isset($assets['css'])) {
                foreach ($assets['css'] as $url) {
                    $css[] = $this->adjustURL($url);
                }
            }
            if (isset($assets['scripts'])) {
                foreach ($assets['scripts'] as $url) {
                    $scripts[] = $this->adjustURL($url);
                }
            }
        }
        return [
            $css,
            $scripts
        ];
    }

    private function adjustURL($url)
    {
        if (preg_match('#^(http:)#', $url)) {
            return $url;
        } else {
            return $this->docURL($url);
        }
    }
}

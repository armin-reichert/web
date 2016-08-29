<?php
namespace AFD\Controller;

use AFD\Controller\App;
use AFD\Controller\Request;
use AFD\View\Response;
use AFD\View\Template;

/**
 * Base class for controllers.
 *
 * @author Armin Reichert
 */
abstract class Controller
{

    /**
     *
     * @var \AFD\Controller\Request
     */
    public $request;

    /**
     * Name of currently executed action.
     *
     * @var string
     */
    public $action;

    /**
     * Returns the selected template for this controller and assigns the variables from the
     * given context.
     *
     * @param string $templateName
     *            name of template file, if empty, it is derived from the executed action
     * @param array $context
     *            key/value pairs to be assigned to template
     * @return \AFD\View\Template template instance
     */
    protected function getTemplate($templateName = '', array $context = [])
    {
        $info = new \ReflectionClass($this);
        // chop trailing "Controller":
        $entityName = substr($info->getShortName(), 0, - 10);
        if (empty($templateName)) {
            $templateName = $this->action . '.php';
        }
        $templatePath = dirname($info->getFileName()) . '/../View/' . $entityName . '/' . $templateName;
        $context['controller'] = $this;
        return new Template($templatePath, $context);
    }

    /**
     * Returns an URL for calling this controller.
     *
     * @param array $params
     *            array of URL parameters
     * @return string the URL for calling this controller with the given parameters
     */
    public function createURL(array $params = [])
    {
        return App::app()->controllerURL($this->request->path, $params);
    }
}
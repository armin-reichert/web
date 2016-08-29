<?php
namespace AFD\View;

/**
 * A view realized by a PHP "template" file.
 * The template file can access the instance of this class via the <code>$this</code> reference.
 * The context values are extracted before including such that the template can access them
 * as local variables.
 *
 * @author Armin Reichert
 */
class Template extends View
{
    const FILENAME_PATTERN = '/\.php$/';

    /**
     * Path to the template file.
     *
     * @var string
     */
    private $templatePath;

    /**
     * The data context of the template (key/value pairs).
     *
     * @var array
     */
    private $context = [];

    /**
     * Constructor.
     *
     * @param string $templatePath
     *            path to the template file
     * @param array $context
     *            optional context for rendering
     */
    public function __construct($templatePath, array $context = [])
    {
        $path = realpath($templatePath);
        if (is_file($path) && preg_match(self::FILENAME_PATTERN, $path)) {
            $this->templatePath = $path;
        } else {
            throw new \Exception("Template: Not a valid template file: " . $templatePath);
        }
        $this->context = $context;
    }

    /**
     * Assigns the given value to the given variable.
     *
     * @param string $key
     *            the variable name
     * @param mixed $value
     *            the value
     */
    public function set($key, $value)
    {
        $this->context[$key] = $value;
    }

    /**
     * (non-PHPdoc)
     *
     * @see View::render()
     */
    public function render()
    {
        $this->renderContextValues();
        extract($this->context);
        include $this->templatePath;
    }

    private function renderContextValues()
    {
        foreach ($this->context as $key => $value) {
            if ($value instanceof Renderable) {
                $this->context[$key] = $value->getContent();
            }
        }
    }

    /**
     * Renders the given template using the context of the outer template.
     * If context values are specified, they replace values of the outer template's context.
     *
     * @param string $templatePath
     *            template file to be included
     * @param array $context
     *            additional context values for rendering the included template
     * @return AFD\View\Template the included template
     */
    public function includeTemplate($templatePath, array $context = [])
    {
        return new Template($templatePath, array_replace($this->context, $context));
    }

    /**
     * Returns the URL for the given resource path relative to the template directory.
     *
     * @param string $resourcePath
     *            relative path to resource (without leading slash)
     * @return string URL to resource
     */
    public function url($resourcePath)
    {
        return $this->docURL(dirname($this->templatePath) . '/' . $resourcePath);
    }
}
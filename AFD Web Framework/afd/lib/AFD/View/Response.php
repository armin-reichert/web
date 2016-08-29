<?php
namespace AFD\View;

use AFD\View\Renderable;

/**
 * A response object encapsulates renderable content and a HTTP headers.
 *
 * @author Armin Reichert
 */
class Response
{

    private $charset = 'utf-8';

    private $contentType = 'text/html';

    /**
     *
     * @var Renderable | string
     */
    private $content;

    /**
     *
     * @var int
     */
    private $statusCode;

    /**
     * Constructor.
     *
     * @param string|Renderable $content
     *            the content, initially empty
     * @param int $statusCode
     *            the status code, initially 200
     */
    public function __construct($content = '', $statusCode = 200)
    {
        $this->setContent($content);
        $this->setStatus($statusCode);
    }

    /**
     * Sets the content of this response.
     *
     * @param string|Renderable $content
     *            the content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Sets the content type of this response.
     *
     * @param string $contentType
     *            the content type (default: 'text/html')
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Returns the content of this response.
     *
     * This is either a string or an instance of some class that implements the \AFD\Renderable interface.
     *
     * @return string | Renderable the content of this response
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the HTTP status code.
     *
     * @return int HTTP status code
     */
    public function getStatus()
    {
        return $this->statusCode;
    }

    /**
     * Sets the HTTP status code.
     *
     * @param int $statusCode
     *            number HTTP status code
     */
    public function setStatus($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Renders the response (headers and content).
     */
    public function render()
    {
        if ($this->content instanceof Renderable) {
            try {
                $this->content = $this->content->getContent();
            } catch (\Exception $e) {
                ob_end_clean();
                $this->content = 'Rendering error in file ' . $e->getFile() . ':' . $e->getLine() . '<br>' . '<pre>' . $e->getTraceAsString() . '</pre>';
                $this->statusCode = 500;
            }
        }
        if (! headers_sent()) {
            header('HTTP/1.1 ' . $this->statusCode);
            header(sprintf('Content-Type: %s; charset=%s', $this->contentType, $this->charset));
        }
        echo $this->content;
    }
}
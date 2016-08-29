<?php
namespace AFD\Controller;

use AFD\PropertyBag;

/**
 * Represents the HTTP request.
 *
 * @author Armin Reichert
 */
class Request
{

    /**
     *
     * @var string
     */
    public $scheme;

    /**
     *
     * @var string
     */
    public $host;

    /**
     *
     * @var string
     */
    public $pathPrefix;

    /**
     *
     * @var string
     */
    public $path;

    /**
     *
     * @var \AFD\PropertyBag
     */
    public $query;

    /**
     *
     * @var string
     */
    public $fragment;

    /**
     * Constructor.
     *
     * @param string $pathPrefix
     *            path prefix of request path, usually the front controller path
     * @throws \Exception
     */
    public function __construct($pathPrefix)
    {
        $this->pathPrefix = $pathPrefix;

        $requestUri = parse_url($_SERVER['REQUEST_URI']);

        $this->scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $this->host = empty($requestUri['host']) ? $_SERVER['HTTP_HOST'] : $requestUri['host'];
        $this->fragment = empty($requestUri['fragment']) ? '' : $requestUri['fragment'];

        $this->path = empty($requestUri['path']) ? '' : $requestUri['path'];
        if (strpos($this->path, $pathPrefix !== 0)) {
            throw new \Exception("Illegal URI path: " . $this->path);
        }
        // cast is needed, otherwise false instead of '' might be returned!
        $this->path = (string) substr($this->path, strlen($pathPrefix), strlen($this->path));

        $query = [];
        if (! empty($requestUri['query'])) {
            parse_str($requestUri['query'], $query);
        }
        $this->query = new PropertyBag($query);
    }

    public function getURI() {
        $uri = $this->scheme . '://' . $this->host . $this->pathPrefix . $this->path;
        if (0 !== count($this->query->all())) {
            $separator = '?';
            foreach ($this->query->all() as $key => $value) {
                $uri .= $separator . $key . '=' . urlencode($value); // TODO check URL encoding
                $separator = '&';
            }
        }
        if (!empty($this->fragment)) {
            $uri .= '#' . $this->fragment;
        }
        return $uri;
    }
}
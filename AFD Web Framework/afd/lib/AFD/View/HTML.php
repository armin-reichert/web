<?php
namespace AFD\View;

/**
 * Provides HTML utility methods.
 *
 * @author Armin Reichert
 */
trait HTML
{

    public function __construct()
    {}

    /**
     * Returns the HTML-escaping for a string.
     *
     * @param string $str
     *            the string to be HTML-escaped
     * @param int $quote_style
     *            how quotes are escaped (default: ENT_QUOTES)
     * @param string $charset
     *            character set (default: 'UTF-8')
     * @return string the HTML-escaped string
     */
    public function esc($str, $quote_style = ENT_QUOTES, $charset = 'utf-8')
    {
        return htmlspecialchars($str, $quote_style, $charset);
    }

    /**
     * Creates the HTML for a stylesheet include.
     *
     * @param string $url
     *            URL for href attribute
     * @return string HTML for link-tag to stylesheet
     */
    public function css($url)
    {
        return '<link href="' . $url . '" rel="stylesheet" type="text/css">' . "\n";
    }

    /**
     * Creates the HTML for a script include.
     *
     * @param string $url
     *            URL for src attribute
     * @return string HTML for script-tag
     */
    public function script($url)
    {
        return '<script src="' . $url . '"></script>' . "\n";
    }

    /**
     * Creates the HTML for a link.
     *
     * @param string $url
     *            URL for href attribute
     * @param array $options
     *            array of link options ('text', 'imageURL', 'imageStyle', 'target')
     * @return string HTML for anchor-tag
     */
    public function link($url, array $options)
    {
        $html = '<a href="' . $url . '"';
        if (isset($options['target'])) {
            $html .= ' target="' . $options['target'] . '"';
        }
        $html .= '>';
        if (isset($options['imageURL'])) {
            $html .= '<img src="' . $options['imageURL'] . '"';
            if (isset($options['imageStyle'])) {
                $html .= ' style="' . $options['imageStyle'] . '"';
            }
            $html .= '>';
        }
        if (isset($options['text'])) {
            $html .= $options['text'];
        }
        $html .= '</a>' . "\n";
        return $html;
    }
}
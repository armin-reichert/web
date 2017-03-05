<?php
defined('_JEXEC') or die();

/**
 * Joomla 2.5 Plugin zur Einbettung von Begegnungen und Tabellen von
 * <a href="http://fussball.de">fussball.de</a>.
 *
 * Verwendung in Joomla:
 * <code>{fbde2015:<em>wettbewerbID</em>}</code>
 *
 * <p>
 * <b>Parameter</b>:
 * <em>wettbewerbID</em>: Website-Schlüssel für Widget
 * </p>
 *
 * @author Armin Reichert (armin.reichert@web.de)
 */
class plgContentFbdeWidget2015 extends JPlugin
{

    const PLUGIN_CALL = "/{fbde2015:(.*)}/U";

    const WIDGET_SCRIPT = "http://www.fussball.de/static/egm//js/widget2.js";

    private function _hasLicense()
    {
        $today = getdate();
        return $today["year"] <= 2015;
    }

    /**
     * Event handler for Joomla's <code>onContentPrepare</code> event.
     */
    public function onContentPrepare($context, &$article, &$params, $page)
    {
        $pluginCalls = $this->_findPluginCalls($article->text);
        if (count($pluginCalls) == 0) {
            return true;
        }
        
        if (! $this->_hasLicense()) {
            $article->text = preg_replace(self::PLUGIN_CALL, "<div style='color:red'><b>Your license for this plugin has expired.</b></div>", $article->text, 1);
            return;
        }
        
        JFactory::getDocument()->addScript(self::WIDGET_SCRIPT);
        foreach ($pluginCalls as $pluginCall) {
            $websiteKey = trim($pluginCall[1]);
            if (! preg_match("/^[0-9A-Za-z\-]+$/", $websiteKey)) {
                return false;
            }
            $html = $this->_createHtml($websiteKey);
            $article->text = preg_replace(self::PLUGIN_CALL, $html, $article->text, 1);
        }
        return true;
    }

    /**
     * Returns an array containing all plugin calls in the given text.
     *
     * Each entry in the result array is an array where the first entry is the full plugin call
     * and the second entry is the parameter list.
     *
     * @param string $text
     *            the UTF-8 encoded text to be searched
     *            
     * @return array array of found plugin calls
     */
    private function _findPluginCalls($text)
    {
        $pluginCalls = array();
        preg_match_all(self::PLUGIN_CALL, $text, $pluginCalls, PREG_SET_ORDER);
        return $pluginCalls;
    }

    /**
     * Creates the HTML containing the div's where the widget will be rendered
     * and the Javascript calls to create and display the widget.
     *
     * @param string $websiteKey
     *            the widget's website key
     * @return string the HTML for displaying the widget
     */
    private function _createHtml($websiteKey)
    {
        $id = str_replace('.', '-', uniqid("fbdePlg_", true));
        $html = <<<END
<div id="${id}" class="fbde_widget">Lade Spielplan...</div>		
<script type="text/javascript">
new fussballdeWidgetAPI().showWidget('${id}', '${websiteKey}');
</script>
END;
        return $html;
    }
}
?>
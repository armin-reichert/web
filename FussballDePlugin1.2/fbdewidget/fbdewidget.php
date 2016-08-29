<?php

defined('_JEXEC') or die;

/**
 * Joomla 2.5 Plugin zur Einbettung von Begegnungen und Tabellen von
 * <a href="http://fussball.de">fussball.de</a>.
 *
 * Verwendung in Joomla:
 * <code>{fbde:<em>parts</em>,<em>mandant</em>,<em>wettbewerbID</em>,<em>width</em>}<code>
 *
 * <p>
 * <b>Parameter</b>:
 * <em>parts</em>: (t|b|tb|bt)
 * <em>mandant</em>: siehe fussball.de
 * <em>wettbewerbID</em>: siehe fussball.de
 * <em>style</em>: Zusätzliche Stilangaben, durch Strichpunkt getrennt. Derzeit unterstützt:
 * <ul>
 * <li><em>width</em>: Zahl(px|em|%)</li>
 * <li><em>notabs</em></li>
 * </ul>
 * </pre>
 *
 * @author Armin Reichert (armin.reichert@web.de)
 */
class plgContentFbdeWidget extends JPlugin {

	const PLUGIN_CALL = "/{fbde:(.*)}/U";
	const LOADING_TEXT_TABELLE = "Tabelle wird geladen...";
	const LOADING_TEXT_BEGEGNUNGEN = "Begegnungen werden geladen...";

	const JS_EGM_WIDGET = "http://www.fussball.de/export.widget.js/-/schluessel/";
	const JS_JQUERY_MIN = "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js";

	/**
	 * Trim function for UTF-8 encoded strings.
	 */
	private function mb_trim($str) {
		return preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $str);
	}

	private function _hasLicense() {
		$today = getdate();
		return $today["year"] <= 2014;
	}

	/**
	 * Event handler for Joomla's <code>onContentPrepare</code> event.
	 */
	public function onContentPrepare($context, &$article, &$params, $page) {

		$pluginCalls = $this->_findPluginCalls($article->text);
		if (count($pluginCalls) == 0) {
			return true;
		}

		if (!$this->_hasLicense()) {
			$article->text = preg_replace(self::PLUGIN_CALL,
					"<div style='color:red'><b>Your license for this plugin has expired.</b></div>",
					 $article->text, 1);
			return;
		}

		$debug = $this->params->get("debug") == 1;
		$errors = array();

		$host = $_SERVER['HTTP_HOST'];
		$fussballDeKey = $this->_findKey($host);
		if (empty($fussballDeKey)) {
			if (!$debug) return false;
			array_push($errors,
				"Es wurde kein Schlüssel für '${host}' gefunden. Bitte prüfen Sie Ihre Einstellungen im Plugin-Manager");
		}

		JFactory::getDocument()->addScript(self::JS_EGM_WIDGET . $fussballDeKey);

		$beautify = $this->params->get("beautify") == 1;
		if ($beautify) {
			JFactory::getDocument()->addStyleSheet(JURI::base() . "plugins/content/fbdewidget/style.css");
			$clubName = $this->params->get("clubName");
			if (!empty($clubName)) {
				JFactory::getDocument()
					->addScript(self::JS_JQUERY_MIN)
					->addScript(JURI::base() . "plugins/content/fbdewidget/scripting.js")
					->addScriptDeclaration("fbdePlg.beautify({clubName: '$clubName'});");
			}
		}

		foreach ($pluginCalls as $pluginCall) {
			$callParams = explode(',', $pluginCall[1]);
			$numParams = count($callParams);
			if ($numParams == 3) {
				list($parts, $mandant, $wettbewerb) = $callParams;
				$styles = array();
			} else if ($numParams == 4) {
				list($parts, $mandant, $wettbewerb, $styleSettings) = $callParams;
				$styles = explode(';', $this->mb_trim($styleSettings));
			} else {
				if (!$debug) return false;
				array_push($errors, "Die Anzahl der Parameter ist nicht korrekt: " . $numParams);
			}

			// Initialize optional parameter values
			$maxWidth = "max-width:".$this->params->get("width", "98%");
			$notabs = false;

			// check parameter values
			$parts = trim($parts);
			if (!preg_match("/^[bt]+$/", $parts)) {
				if (!$debug) return false;
				array_push($errors, "Ungültige Teileangabe: " . $parts);
			}
			$mandant = trim($mandant);
			if (!preg_match("/^[0-9]+$/", $mandant)) {
				if (!$debug) return false;
				array_push($errors, "Ungültiger Mandant: " . $mandant);
			}
			$wettbewerb = trim($wettbewerb);
			if (!preg_match("/^[0-9A-Za-z\-]+$/", $wettbewerb)) {
				if (!$debug) return false;
				array_push($errors, "Ungültige Wettbewerb ID: " . $wettbewerb);
			}
			// styles
			foreach ($styles as $style) {
				$style = $this->mb_trim($style);
				$matches = array();
				if (preg_match("/^breite:\p{Z}*([0-9]+)(px|em|%)?$/", $style, $matches)) {
					$maxWidth = "max-width:".$matches[1].$matches[2];
				} else if ("ohnereiter" == $style) {
					$notabs = true;
				}
				else {
					array_push($errors, "Ungültige Stilangabe: '" . $style . "'");
				}
			}

			if ($debug) {
				$cssClass = count($errors) > 0 ? "fbdePlgHasErrors" : "fbdePlgNoErrors";
				$html = "<div class='${cssClass}' style='${maxWidth}'>";
				$html .= "<b>{ fbde: " . htmlentities($pluginCall[1]) . "}</b>";
				foreach ($errors as $error) {
					$html .= "<p>".htmlentities($error)."</p>";
				}
				$html .= "</div>";
				$errors = array();
			} else {
				$html = $this->_createHtml($parts, $mandant, $wettbewerb, $maxWidth, $notabs);
			}
			// replace next plugin call by computed HTML
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
	 * 		the UTF-8 encoded text to be searched
	 *
	 * @return array
	 * 		array of found plugin calls
	 */
	private function _findPluginCalls($text) {
		$pluginCalls = array();
		preg_match_all(self::PLUGIN_CALL, $text, $pluginCalls, PREG_SET_ORDER);
		return $pluginCalls;
	}

	/**
	 * Finds the key for the given host from the plugin configuration.
	 *
	 * @param string $host
	 * 	the host where this plugin is running
	 * @return string the fussball.de key for the given host or <code>null</code>
	 */
	private function _findKey($host) {
		for ($i = 1; $i <= 10; ++$i) {
			$paramHost = $this->params->get("host$i");
			if ($paramHost != null) {
				$paramHost = trim($paramHost);
				$paramHost = preg_replace('#^https?://#', '', $paramHost);
				if ($paramHost == $host) {
					return $this->params->get("key$i");
				}
			}
		}
		return null;
	}

	/**
	 * Creates the HTML containing the div's where the widget will be rendered
	 * and the Javascript calls to create and display the widget.
	 *
	 * @param string $parts
	 *   the widget parts to be displayed e.g. "bt"
	 * @param string $mandant
	 * 	 the client ID for this contest
	 * @param string $wettbewerb
	 *   the contest ID
	 * @param string $maxWidth
	 *   the maximum width of the widget container given as "max-width:<em>CSS-size</em>"
	 * @param boolean $notabs
	 *   controls whether the tabs on top are rendered
	 * @return string
	 *   the HTML replacing the plugin call
	 */
 	private function _createHtml($parts, $mandant, $wettbewerb, $maxWidth, $notabs) {
 		$cssClasses = "fbdePlg";
 		if ($notabs) {
 			$cssClasses .= " notabs";
 		}
		$html = "<div class='${cssClasses}' style='width:100%;${maxWidth}'>";
		$id = str_replace('.', '-', uniqid("fbdePlg_", true));
		for ($i = 0; $i < strlen($parts); ++$i) {
			$html .= "\n<div id='${id}${i}'";
			if ($i > 0) {
				$html .= " class='fbdePlgTrailing'";
			}
			$html .= " style='width:100%'>";
			switch ($parts[$i]) {
				case 'b':
					$html .= self::LOADING_TEXT_BEGEGNUNGEN;
					break;
				case 't':
					$html .= self::LOADING_TEXT_TABELLE;
					break;
			}
			$html .= "</div>";
		}
		$html .= <<<END

</div>
<script type="text/javascript">
(function () {
var w = new fussballdeAPI();
w.setzeMandant('${mandant}');
w.setzeWettbewerb('${wettbewerb}');
END;
		for ($i = 0; $i < strlen($parts); ++$i) {
			switch ($parts[$i]) {
				case 'b':
					$html .= "\nw.zeigeBegegnungen('${id}${i}');";
					break;
				case 't':
					$html .= "\nw.zeigeTabelle('${id}${i}');";
					break;
			}
		}
		$html .= <<<END

}());
</script>
END;
		return $html;
	}
}
?>
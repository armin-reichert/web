/**
 * JS functions to beautify fussball.de widget after rendering.
 * 
 * @author armin.reichert@web.de
 */

/* Namespace */
this.fbdePlg = this.fbdePlg || {};

(function() {
	
	/**
	 * Highlights the given text inside the fussball.de widget.
	 * 
	 * @param {string} sClubName
	 * 		Full or partial name of club to be emphasized
	 */
	function highlightClubName(sClubName) {
		var sSelector = "a.egmClubInfoLink:contains('" + sClubName + "')";
		jQuery(sSelector).css("font-weight", "bold");
		jQuery(".egmTableContent " + sSelector).css("font-size", "larger");
	};

	/**
	 * Registers the beautifier as a callback for the jQuery "ready" event.
	 * TODO: how to avoid duplicate registration?
	 * TODO: is there any way of getting notified after widget has been rendered?
	 */
	fbdePlg.beautify = function (oParameters) {
		var iDelay = 4000;
		jQuery(document).ready(function() {
			setTimeout(highlightClubName.bind(null, oParameters.clubName), iDelay);
		});
	};
	
}());

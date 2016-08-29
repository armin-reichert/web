package de.amr.web.fussballde.output;

/**
 * Enum type for output formats.
 * 
 * @author Armin Reichert
 * 
 */
public enum OutputFormat {
	GoogleCalendarCSV("Google Calendar CSV");

	private final String displayName;

	private OutputFormat(String displayName) {
		this.displayName = displayName;
	}

	@Override
	public String toString() {
		return displayName;
	};
}
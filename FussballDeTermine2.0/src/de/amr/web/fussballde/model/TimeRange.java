package de.amr.web.fussballde.model;

/**
 * Enum type containing time intervals.
 * 
 * @author Armin Reichert
 */
public enum TimeRange {

	OneDay("1 Tag", 1), OneWeek("1 Woche", 7), TwoWeeks("2 Wochen", 14), OneMonth("1 Monat", 30), TwoMonths(
			"2 Monate", 60), ThreeMonths("3 Monate", 90), SixMonths("6 Monate", 180), OneYear("1 Jahr",
			365);

	private final String text;

	private final int days;

	private TimeRange(String text, int days) {
		this.text = text;
		this.days = days;
	}

	public String getText() {
		return text;
	}

	public int getDays() {
		return days;
	}

	@Override
	public String toString() {
		return text;
	}
}

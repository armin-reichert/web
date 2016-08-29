package de.amr.web.fussballde.controller;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Iterator;
import java.util.List;

import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.PageFactory;

import de.amr.web.fussballde.misc.Util;
import de.amr.web.fussballde.model.Match;
import de.amr.web.fussballde.model.TeamSchedule;

/**
 * Grabs the matches from the fussball.de team calendar page and writes them
 * into a CSV file.
 * 
 * @author Armin Reichert
 */
public class TeamCalendarGrabber {

	private static final DateFormat TIME_FORMAT = new SimpleDateFormat("HH:mm");
	private static final DateFormat DATE_FORMAT = new SimpleDateFormat("dd.MM.yyyy");

	private static String getText(WebDriver driver, WebElement element) {
		String html = (String) ((JavascriptExecutor) driver).executeScript(
				"return arguments[0].innerHTML", element);
		return cleanup(html);
	}

	private static String cleanup(String text) {
		text = text.replaceAll("(\\n|&nbsp;|\\s+)", " ");
		text = text.replaceAll("(,|\\|)", ";");
		text = text.replaceAll(" ;", ";");
		text = text.replaceAll("Für weitere Informationen klicken Sie(.*)$", "");
		return text.trim();
	}

	private static void trace(String msg) {
		System.err.println(">> " + msg);
	}

	public TeamCalendarGrabber() {
	}

	public List<Match> grab(TeamSchedule schedule, String startDate, String endDate) {
		final WebDriver driver = new ChromeDriver();
		final TeamCalendarPage page = PageFactory.initElements(driver, TeamCalendarPage.class);
		driver.get(schedule.getUrl());
		trace("Page loaded successfully");
		page.prepare().selectDateRange(startDate, endDate).showMatchesTable();
		List<Match> matchList = Collections.emptyList();
		if (page.getMatchesTable() != null) {
			matchList = evaluate(driver, page);
		}
		trace("Close browser");
		driver.quit();
		return matchList;
	}

	private List<Match> evaluate(WebDriver driver, TeamCalendarPage page) {
		trace("Evaluate matches table");
		final List<Match> matches = new ArrayList<Match>();
		return matches;
	}

	// private List<Match> evaluate(WebDriver driver, TeamCalendarPage page) {
	// final List<Match> matches = new ArrayList<Match>();
	// String date = "";
	// String ageGroup = "";
	// for (WebElement row : page.getMatchesTableRows()) {
	// final List<WebElement> cells = page.getMatchesTableCells(row);
	// if (cells.isEmpty()) {
	// continue;
	// }
	// // Two leadings rows followed by 1:N data rows
	// if (page.isDateRow(cells)) {
	// // Freitag, 28.02.2014
	// final String text = cells.get(0).getText();
	// date = text.substring(text.length() - 10);
	// } else if (page.isAgeGroupRow(cells)) {
	// // C-Junioren
	// ageGroup = cleanup(cells.get(0).getText());
	// } else {
	// // data row(s) - each representing a match
	// final Match match = new Match();
	// matches.add(match);
	// match.setDate(date);
	// match.setAgeGroup(ageGroup);
	// match.setMatchId(cleanup(cells.get(0).getText()));
	// match.setFussballDeUrl(page.getDetailsPageUrl(cells));
	// match.setStartTime(cleanup(cells.get(1).getText()));
	// // TODO this is not exact:
	// match.setEndTime(Util.later(TIME_FORMAT, match.getStartTime(), 1, 30));
	// match.setHomeTeam(cleanup(cells.get(2).getText()));
	// match.setGuestTeam(cleanup(cells.get(4).getText()));
	// match.setResult(cleanup(cells.get(5).getText()));
	// match.setLeague(cleanup(cells.get(6).getText()));
	// buildInfoHTML(driver, match, page.getInfoTooltipItems(cells.get(7)));
	// }
	// }
	// return matches;
	// }

	private void buildInfoHTML(WebDriver driver, Match match, List<WebElement> infoListItems) {
		match.setReferee("");
		match.setLocation("");
		match.setInfo("");

		final StringBuilder info = new StringBuilder();
		info.append("<b>");
		info.append(match.getAgeGroup()).append(": ");
		info.append(match.getHomeTeam()).append(" - ").append(match.getGuestTeam());
		if (!Util.isEmpty(match.getResult())) {
			// result is not accessible as text: thank you, fuckers from Telekom/DFB!
			// info.append(" (").append(match.getResult()).append(")");
		}
		info.append("</b>");
		info.append("<br>");
		info.append(Util.weekday(DATE_FORMAT, match.getDate())).append(" ").append(match.getDate())
				.append(" ").append(match.getStartTime()).append(" Uhr");
		info.append("<br>");
		for (Iterator<WebElement> it = infoListItems.iterator(); it.hasNext();) {
			final String text = getText(driver, it.next());
			if (text.startsWith("Spiel:")) {
				// ignore
			} else if (text.startsWith("verlegt vom:")) {
				info.append(cleanup(text));
			} else if (text.startsWith("Schiedsrichter:")) {
				match.setReferee(cleanup(text.substring(15)));
			} else if (text.startsWith("Spielstätte:")) {
				String location = cleanup(text.substring(12)).replaceAll(
						"(Hartplatz|Kunstrasen|Rasenplatz)", "");
				if (it.hasNext()) {
					location += " " + cleanup(getText(driver, it.next()));
				}
				match.setLocation(location.trim());
				if (it.hasNext()) {
					match.setSurface(cleanup(getText(driver, it.next())));
				}
			}
		}
		if (!Util.isEmpty(match.getLocation())) {
			info.append("<br>Ort: ").append(match.getLocation());
		}
		if (!Util.isEmpty(match.getSurface())) {
			info.append("<br>Platzart: ").append(match.getSurface());
		}
		if (!Util.isEmpty(match.getReferee())) {
			info.append("<br>Schiedsrichter: ").append(match.getReferee());
		}
		if (match.getFussballDeUrl() != null) {
			info.append("<br><a href='").append(match.getFussballDeUrl())
					.append("' target='_blank'>Weitere Informationen</a>");
		}
		match.setInfo(cleanup(info.toString()));
	}
}
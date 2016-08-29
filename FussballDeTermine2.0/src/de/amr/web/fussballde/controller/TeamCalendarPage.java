package de.amr.web.fussballde.controller;

import java.util.Collections;
import java.util.List;

import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

/**
 * Page object for the fussball.de team calendar page.
 * 
 * @author Armin Reichert
 */
public class TeamCalendarPage {

	private final WebDriver driver;

	@FindBy(name = "date-from")
	private WebElement inputDateFrom;

	@FindBy(name = "date-to")
	private WebElement inputDateTo;

	@FindBy(xpath = "//*[@id='egmClubFixtureListInputTimeEnd']/button")
	private WebElement buttonShowMatches;

	@FindBy(xpath = "//*[@id='team-matchplan-table']/table")
	private WebElement matchesTable;

	@FindBy(xpath = "//*[@id='cookie-layer']/a")
	private WebElement cookiePopupCloseLink;

	/**
	 * Public constructor taking the web driver as expected by the page factory.
	 * 
	 * @param driver
	 *          the web driver
	 */
	public TeamCalendarPage(WebDriver driver) {
		this.driver = driver;
	}

	public TeamCalendarPage prepare() {
		WebDriverWait wait = new WebDriverWait(driver, 10);
		try {
			cookiePopupCloseLink = wait.until(ExpectedConditions.visibilityOf(cookiePopupCloseLink));
			cookiePopupCloseLink.click();
		} catch (NoSuchElementException e) {
			e.printStackTrace(System.err);
		}
		return this;
	}

	/**
	 * Fills the given date range into the corresponding fields.
	 * 
	 * @param startDate
	 *          the start date of the query
	 * @param endDate
	 *          the end date of the query
	 * @return the page
	 */
	public TeamCalendarPage selectDateRange(String startDate, String endDate) {
		// inputDateFrom.clear();
		// inputDateFrom.sendKeys(startDate);
		// inputDateTo.clear();
		// inputDateTo.sendKeys(endDate);
		return this;
	}

	/**
	 * Exceutes the query and displays the matches table for the selected date
	 * range. Waits until the table has become visible.
	 * 
	 * @return the page
	 */
	public TeamCalendarPage showMatchesTable() {
		// buttonShowMatches.click();
		WebDriverWait wait = new WebDriverWait(driver, 10);
		try {
			matchesTable = wait.until(ExpectedConditions.visibilityOf(matchesTable));
		} catch (NoSuchElementException e) {
			matchesTable = null;
			e.printStackTrace(System.err);
		}
		return this;
	}

	/**
	 * Returns the web element representing the table of matches.
	 * 
	 * @return matches table
	 */
	public WebElement getMatchesTable() {
		return matchesTable;
	}

	/**
	 * Returns the list of rows of the matches table.
	 * 
	 * @return list of rows
	 */
	public List<WebElement> getMatchesTableRows() {
		if (matchesTable != null) {
			return matchesTable.findElements(By.tagName("tr"));
		}
		return Collections.emptyList();
	}

	/**
	 * Returns the list of cells in the given matches table row.
	 * 
	 * @param row
	 *          web element representing a row in the matches table
	 * @return list of cells
	 */
	public List<WebElement> getMatchesTableCells(WebElement row) {
		return row.findElements(By.tagName("td"));
	}

	/**
	 * Tells if the given list of cells represent the matches table row containing
	 * the date.
	 * 
	 * @param cells
	 *          list of cells of the matches table
	 * @return <code>true</code> if the cells represent the row containing the
	 *         match date
	 */
	public boolean isDateRow(List<WebElement> cells) {
		return cells.get(0).getAttribute("class").equals("egmMatchTable egmRowGrouped");
	}

	/**
	 * Tells if the given list of cells represent the matches table row containing
	 * the age group.
	 * 
	 * @param cells
	 *          list of cells of the matches table
	 * @return <code>true</code> if the cells represent the row containing the age
	 *         group
	 */
	public boolean isAgeGroupRow(List<WebElement> cells) {
		return cells.get(0).getAttribute("class").equals("egmMatchTable egmRowGroupedSubGroup");
	}

	/**
	 * Returns the URL pointing to the match details
	 * 
	 * @param cells
	 *          cells of a row of the matches table
	 * @return URL pointing to the match details
	 */
	public String getDetailsPageUrl(List<WebElement> cells) {
		List<WebElement> anchors = cells.get(0).findElements(By.tagName("a"));
		return !anchors.isEmpty() ? anchors.get(0).getAttribute("href") : "";
	}

	/**
	 * Returns the list of web elements containing the additional info for a
	 * match.
	 * 
	 * @param cell
	 *          cell containing the tooltip with the additional info
	 * @return list of elements with additional info
	 */
	public List<WebElement> getInfoTooltipItems(WebElement cell) {
		return cell.findElements(By.tagName("li"));
	}

}
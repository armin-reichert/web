package de.amr.web.fussballde.model;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;

/**
 * The schedule for a team. The URL points to the page at fussball.de where the
 * schedule can be displayed for a specific time interval.
 * 
 * @author Armin Reichert
 */
@XmlRootElement(name = "schedule")
public class TeamSchedule {

	@XmlElement
	private String teamName;

	@XmlElement
	private String url;

	public TeamSchedule() {
	}

	public TeamSchedule(String teamName, String url) {
		this.teamName = teamName;
		this.url = url;
	}

	public String getTeamName() {
		return teamName;
	}

	public String getUrl() {
		return url;
	}

	@Override
	public String toString() {
		return teamName;
	}

}

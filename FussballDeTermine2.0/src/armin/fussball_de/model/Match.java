package armin.fussball_de.model;

/**
 * Data of a match.
 */
public class Match {
	private String matchId;
	private String ageGroup;
	private String homeTeam;
	private String guestTeam;
	private String referee;
	private String result;
	private String league;
	private String date;
	private String startTime;
	private String endTime;
	private String location;
	private String surface;
	private String info;
	private String fussballDeUrl;

	@Override
	public String toString() {
		return matchId + "," + ageGroup + "," + date + "," + startTime + "," + endTime + "," + homeTeam
				+ "," + guestTeam + "," + referee + "," + result + "," + league + "," + location + ","
				+ info + "," + fussballDeUrl;
	}

	public String getAgeGroup() {
		return ageGroup;
	}

	public String getMatchId() {
		return matchId;
	}

	public String getHomeTeam() {
		return homeTeam;
	}

	public String getGuestTeam() {
		return guestTeam;
	}

	public String getResult() {
		return result;
	}

	public String getLeague() {
		return league;
	}

	public String getDate() {
		return date;
	}

	public String getReferee() {
		return referee;
	}

	public String getSurface() {
		return surface;
	}

	public String getStartTime() {
		return startTime;
	}

	public String getEndTime() {
		return endTime;
	}

	public String getLocation() {
		return location;
	}

	public String getInfo() {
		return info;
	}

	public String getFussballDeUrl() {
		return fussballDeUrl;
	}

	public void setMatchId(String matchId) {
		this.matchId = matchId;
	}

	public void setHomeTeam(String homeTeam) {
		this.homeTeam = homeTeam;
	}

	public void setGuestTeam(String guestTeam) {
		this.guestTeam = guestTeam;
	}

	public void setResult(String result) {
		this.result = result;
	}

	public void setLeague(String league) {
		this.league = league;
	}

	public void setDate(String date) {
		this.date = date;
	}

	public void setStartTime(String startTime) {
		this.startTime = startTime;
	}

	public void setEndTime(String endTime) {
		this.endTime = endTime;
	}

	public void setLocation(String location) {
		this.location = location;
	}

	public void setInfo(String info) {
		this.info = info;
	}

	public void setFussballDeUrl(String fussballDeUrl) {
		this.fussballDeUrl = fussballDeUrl;
	}

	public void setAgeGroup(String ageGroup) {
		this.ageGroup = ageGroup;
	}

	public void setReferee(String referee) {
		this.referee = referee;
	}

	public void setSurface(String surface) {
		this.surface = surface;
	}

}

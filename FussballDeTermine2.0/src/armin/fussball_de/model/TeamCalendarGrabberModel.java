package armin.fussball_de.model;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.PrintStream;
import java.util.List;

/**
 * Data model for the team calendar grabber.
 * 
 * @author Armin Reichert
 */
public class TeamCalendarGrabberModel {

	private static final String SCHEDULE_LIST_PATH = "/armin/fussball_de/model/scheduleList.xml";

	private TeamScheduleCollection schedules;
	private List<Match> matches;

	public void setMatchList(List<Match> matches) {
		this.matches = matches;
	}

	public List<Match> getMatchList() {
		return matches;
	}

	public TeamScheduleCollection getTeamSchedules() {
		if (schedules == null) {
			schedules = readSchedules(SCHEDULE_LIST_PATH);
		}
		return schedules;
	}

	public void save(String text, File outputFile) throws FileNotFoundException {
		PrintStream ps = new PrintStream(new FileOutputStream(outputFile));
		ps.println(text);
		ps.close();
		System.out.println("Output written to " + outputFile.getAbsolutePath());
	}

	private TeamScheduleCollection readSchedules(String path) {
		InputStream in = getClass().getResourceAsStream(path);
		if (in != null) {
			InputStreamReader reader = new InputStreamReader(in);
			return TeamScheduleCollection.createFromXml(reader);
		} else {
			System.err.println("No team schedule found at resource path " + path);
			return new TeamScheduleCollection();
		}
	}

}

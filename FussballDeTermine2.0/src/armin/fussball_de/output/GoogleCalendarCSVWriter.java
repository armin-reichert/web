package armin.fussball_de.output;

import java.io.ByteArrayOutputStream;
import java.io.PrintStream;
import java.util.List;

import armin.fussball_de.model.Match;

/**
 * Writes a CSV file that can be used for importing into Google Calendar.
 * 
 * Possible headers: Subject, "Start Date", "Start Time", "End Date",
 * "End Time", "All Day Event", Description, Location, and Private
 * 
 * @author Armin Reichert
 */
public class GoogleCalendarCSVWriter implements TeamCalendarWriter {

	@Override
	public void print(List<Match> matches, PrintStream p) {
		p.println("Subject,Start Date,Start Time,End Date,End Time,All Day Event,Description,Location,Private");
		for (Match match : matches) {
			p.print(match.getAgeGroup() + ": " + match.getHomeTeam() + " - " + match.getGuestTeam());
			p.print(",");
			p.print(match.getDate());
			p.print(",");
			p.print(match.getStartTime());
			p.print(",");
			p.print(match.getDate());
			p.print(",");
			p.print(match.getEndTime());
			p.print(",");
			p.print("false"); /* All Day Event */
			p.print(",");
			p.print(match.getInfo());
			p.print(",");
			p.print(match.getLocation());
			p.print(",");
			p.print("false"); /* Private */
			p.println();
		}
	}

	public String getMatchListAsCsv(List<Match> matches) {
		ByteArrayOutputStream bs = new ByteArrayOutputStream();
		PrintStream stream = new PrintStream(bs);
		print(matches, stream);
		stream.close();
		return bs.toString();
	}

}
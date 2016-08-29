package armin.fussball_de.output;

import java.io.PrintStream;
import java.util.List;

import armin.fussball_de.model.Match;

/**
 * Interface for classes that write the match list to a print stream.
 * 
 * @author Armin Reichert
 */
public interface TeamCalendarWriter {

	/**
	 * Writes the given list of matches into the given stream.
	 * 
	 * @param matches
	 *          the match list
	 * @param stream
	 *          a print stream
	 */
	public void print(List<Match> matches, PrintStream stream);
}

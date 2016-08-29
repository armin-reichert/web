package de.amr.web.dfbnet.gcalendar;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.PrintStream;
import java.nio.file.FileSystems;
import java.nio.file.Path;

import de.amr.web.dfbnet.DFBNetCSVReader;
import de.amr.web.dfbnet.DFBNetRecord;
import de.amr.web.dfbnet.DFBNetRecordList;
import de.amr.web.dfbnet.gcalendar.GCalendarComposer.GCalendarColumn;

/**
 * Converts a CSV file exported from DFBNet into a CSV file that can be imported
 * into Google Calendar.
 * 
 * @author armin.reichert@web.de
 */
public class DFBNet2GoogleCalendar
{
	private static void usage() {
		System.out.println("Usage: dfbnet2gcalendar <dfbnet_export.csv>");
		System.out.println("Eingabedatei muss die aus DFBNet exportierten Termine enthalten.");
		System.out.println("Ausgabe ist eine CSV-Datei, die sich in Google Calendar importieren lässt.");
	}

	public static void main(String[] args) {
		if (args.length < 1) {
			usage();
			System.exit(0);
		}
		String inputFileName = args[0];
		if (!inputFileName.endsWith(".csv")) {
			System.out.println("Eingabedatei muss auf .csv enden");
			System.exit(0);
		}
		try {
			PrintStream out = new PrintStream(new File(outputFileName(inputFileName)));
			new DFBNet2GoogleCalendar().convert(FileSystems.getDefault().getPath(inputFileName), out);
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		}
	}

	private static String outputFileName(String inputFileName) {
		return inputFileName.substring(0, inputFileName.length() - 4) + ".gcal.csv";
	}

	private void convert(Path inputPath, PrintStream out) {
		DFBNetCSVReader reader = new DFBNetCSVReader();
		try {
			DFBNetRecordList records = reader.read(inputPath);
			// records.print(System.out);
			printGoogleCalendarCSV(records, out);
			printGoogleCalendarCSV(records, System.out);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	private void printGoogleCalendarCSV(DFBNetRecordList recordList, PrintStream out) {
		GCalendarComposer composer = new GCalendarComposer();
		// Header line
		out.println("Subject,Start Date,Start Time,End Date,End Time,All Day Event,Description,Location,Private");
		// Data lines
		for (DFBNetRecord record : recordList) {
			for (GCalendarColumn column : GCalendarColumn.values()) {
				if (column.ordinal() > 0) {
					out.print(",");
				}
				out.print(composer.makeCalendarField(column, record));
			}
			out.println();
		}
		out.close();
	}
}

package de.amr.web.dfbnet.gcalendar;

import java.text.DateFormat;
import java.text.ParseException;
import java.util.Calendar;
import java.util.Locale;

import de.amr.web.dfbnet.DFBNetColumn;
import de.amr.web.dfbnet.DFBNetRecord;

public class GCalendarComposer
{
	enum GCalendarColumn {
		Subject, Start_Date, Start_Time, End_Date, End_Time, All_Day_Event, Description, Location, Private;
	}
	
	public String makeCalendarField(GCalendarColumn column, DFBNetRecord record) {
		switch (column) {
		case Subject:
			return record.concat(DFBNetColumn.Liga, ": ", DFBNetColumn.Heimmannschaft, " - ", DFBNetColumn.Gastmannschaft);
		case Start_Date:
			return record.concat(DFBNetColumn.Spieldatum);
		case Start_Time:
			return record.concat(DFBNetColumn.Uhrzeit);
		case End_Date:
			return record.concat(DFBNetColumn.Spieldatum);
		case End_Time:
			return addTime(record.concat(DFBNetColumn.Uhrzeit), 1, 30);
		case All_Day_Event:
			return "false";
		case Description:
			return qq(record.concat("Saison ", DFBNetColumn.Saison, " ", DFBNetColumn.Typ, " ", DFBNetColumn.Liga, ": ",
					DFBNetColumn.Heimmannschaft, " - ", DFBNetColumn.Gastmannschaft, ", ", DFBNetColumn.Ort, ", Spielstätte: ",
					DFBNetColumn.Spielstätte));
		case Location:
			return qq(record.concat(DFBNetColumn.PLZ, " ", DFBNetColumn.Ort, ", ", DFBNetColumn.Straße));
		case Private:
			return "false";
		default:
			return "";
		}
	}

	private String addTime(String time, int hours, int minutes) {
		Calendar calendar = Calendar.getInstance(Locale.GERMAN);
		DateFormat fmt = DateFormat.getTimeInstance(DateFormat.SHORT, Locale.GERMAN);
		try {
			calendar.setTime(fmt.parse(time));
			calendar.add(Calendar.HOUR_OF_DAY, 1);
			calendar.add(Calendar.MINUTE, 30);
			return fmt.format(calendar.getTime());
		} catch (ParseException e) {
			e.printStackTrace();
		}
		return time;
	}

	private static String qq(String s) {
		return "\"" + s + "\"";
	}
}

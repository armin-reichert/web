package de.amr.web.fussballde.misc;

import java.net.URI;
import java.net.URISyntaxException;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.Comparator;
import java.util.Date;
import java.util.List;
import java.util.Properties;

import javax.swing.UIManager;
import javax.swing.UIManager.LookAndFeelInfo;

public class Util {

	public static void setLookAndFeel(String name) {
		try {
			for (LookAndFeelInfo laf : UIManager.getInstalledLookAndFeels()) {
				if (name.equals(laf.getName())) {
					UIManager.setLookAndFeel(laf.getClassName());
					return;
				}
			}
		} catch (Exception e) {
			System.err.println("Could not set LAF '" + name + "', using system LAF instead.");
			try {
				UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
			} catch (Exception x) {
				x.printStackTrace(System.err);
			}
		}
	}

	public static boolean isValidUrl(String url) {
		try {
			new URI(url);
		} catch (URISyntaxException e) {
			return false;
		}
		return true;
	}

	public static boolean isValidDate(String date) {
		try {
			new SimpleDateFormat("dd.MM.YYYY").parse(date);
		} catch (ParseException e) {
			return false;
		}
		return true;
	}

	public static boolean isEmpty(String text) {
		return text == null || text.trim().length() == 0;
	}

	public static String later(DateFormat timeFormat, String startTime, int hours, int minutes) {
		if (isEmpty(startTime)) {
			return "";
		}
		try {
			Calendar cal = Calendar.getInstance();
			cal.setTime(timeFormat.parse(startTime));
			cal.add(Calendar.HOUR_OF_DAY, 1);
			cal.add(Calendar.MINUTE, 30);
			return timeFormat.format(cal.getTime());
		} catch (Exception x) {
			return "";
		}
	}

	public static String weekday(DateFormat dateFormat, String date) {
		if (isEmpty(date)) {
			return "";
		}
		try {
			Date d = dateFormat.parse(date);
			return new SimpleDateFormat("EEEE").format(d);
		} catch (Exception x) {
			return "";
		}
	}

	public static void printSystemProperties() {
		Properties p = System.getProperties();
		List<Object> keys = new ArrayList<Object>(p.keySet());
		Collections.sort(keys, new Comparator<Object>() {

			@Override
			public int compare(Object o1, Object o2) {
				return ((String) o1).compareTo((String) o2);
			}
		});

		for (Object key : keys) {
			System.out.println(key + "=" + p.getProperty((String) key));
		}
	}

}

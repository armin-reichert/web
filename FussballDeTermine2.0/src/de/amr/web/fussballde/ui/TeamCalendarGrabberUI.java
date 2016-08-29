package de.amr.web.fussballde.ui;

import java.awt.BorderLayout;
import java.awt.FlowLayout;
import java.awt.Font;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.ItemEvent;
import java.awt.event.ItemListener;
import java.io.File;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Vector;

import javax.swing.AbstractAction;
import javax.swing.Action;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JFileChooser;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;
import javax.swing.SwingUtilities;

import de.amr.web.fussballde.controller.TeamCalendarGrabber;
import de.amr.web.fussballde.misc.FormBuilder;
import de.amr.web.fussballde.misc.Util;
import de.amr.web.fussballde.model.Match;
import de.amr.web.fussballde.model.TeamCalendarGrabberModel;
import de.amr.web.fussballde.model.TeamSchedule;
import de.amr.web.fussballde.model.TimeRange;
import de.amr.web.fussballde.output.GoogleCalendarCSVWriter;
import de.amr.web.fussballde.output.OutputFormat;

/**
 * User interface for the fussball.de team calendar grabber.
 * 
 * @author Armin Reichert
 */
@SuppressWarnings("serial")
public class TeamCalendarGrabberUI extends JFrame {

	private ImageIcon getIcon(String iconName) {
		return new ImageIcon(getClass().getResource("icons/" + iconName + ".png"));
	}

	private static void handleException(Exception x) {
		JOptionPane.showMessageDialog(null, x.getMessage(), "Sou gähdet net!",
				JOptionPane.ERROR_MESSAGE);
		x.printStackTrace(System.err);
	}

	public static void main(String[] args) {
		System.setProperty("webdriver.chrome.driver", System.getProperty("user.home")
				+ "/chromedriver.exe");
		SwingUtilities.invokeLater(new Runnable() {
			@Override
			public void run() {
				Util.setLookAndFeel("Nimbus");
				try {
					final TeamCalendarGrabberUI ui = new TeamCalendarGrabberUI(new TeamCalendarGrabberModel());
					ui.setLocation(200, 200);
					ui.pack();
					ui.setVisible(true);
				} catch (Exception x) {
					handleException(x);
				}
			}
		});
	}

	private final TeamCalendarGrabberModel model;

	private final JComboBox<TeamSchedule> scheduleSelector;

	private final JComboBox<TimeRange> timeRangeSelector;

	private final JComboBox<OutputFormat> outputFormatSelector;

	private final JTextField startDateField;

	private final JTextField endDateField;

	private final JTextArea outputArea;

	private final Action actionStartGrabbing = new AbstractAction("Start", getIcon("hand_fuck")) {

		@Override
		public void actionPerformed(ActionEvent e) {
			try {
				TeamSchedule schedule = (TeamSchedule) scheduleSelector.getSelectedItem();
				String startDate = startDateField.getText();
				String endDate = endDateField.getText();
				TeamCalendarGrabber grabber = new TeamCalendarGrabber();
				List<Match> matches = grabber.grab(schedule, startDate, endDate);
				model.setMatchList(matches);
				outputArea.setText(new GoogleCalendarCSVWriter().getMatchListAsCsv(matches));
				actionSaveResult.setEnabled(true);
			} catch (Exception x) {
				actionSaveResult.setEnabled(false);
				handleException(x);
			}
		}
	};

	private final Action actionSaveResult = new AbstractAction("Speichern", getIcon("save_as")) {

		@Override
		public void actionPerformed(ActionEvent e) {
			try {
				TeamSchedule schedule = (TeamSchedule) scheduleSelector.getSelectedItem();
				String startDate = startDateField.getText();
				String endDate = endDateField.getText();
				JFileChooser fileChooser = new JFileChooser();
				fileChooser.setDialogType(JFileChooser.SAVE_DIALOG);
				String fileName = schedule.getTeamName().replaceAll("[/ ]", "_") + "_" + startDate + "-"
						+ endDate + ".txt";
				fileChooser.setSelectedFile(new File(fileName));
				if (fileChooser.showSaveDialog(TeamCalendarGrabberUI.this) == JFileChooser.APPROVE_OPTION) {
					model.save(outputArea.getText(), fileChooser.getSelectedFile());
				}
			} catch (Exception x) {
				handleException(x);
			}
		}
	};

	private void initOutputArea() {
		outputArea.setText("Das Ergebnis wird hier erscheinen...");
	}

	private void updateDateRange() {
		Calendar calendar = Calendar.getInstance();
		Date startDate = null;
		DateFormat fmt = new SimpleDateFormat("dd.MM.yyyy");
		try {
			startDate = fmt.parse(startDateField.getText());
		} catch (ParseException e) {
			startDate = calendar.getTime();
			startDateField.setText(fmt.format(startDate));
		}
		calendar.setTime(startDate);
		TimeRange range = (TimeRange) timeRangeSelector.getSelectedItem();
		calendar.add(Calendar.DAY_OF_MONTH, range.getDays());
		endDateField.setText(fmt.format(calendar.getTime()));
	}

	public TeamCalendarGrabberUI(TeamCalendarGrabberModel model) {

		this.model = model;

		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		setTitle("fussball.de Teamkalender-Abgreifer - \u00a9 2014 Armin Reichert");
		setResizable(true);

		scheduleSelector = new JComboBox<TeamSchedule>(new Vector<TeamSchedule>(model
				.getTeamSchedules().asList()));
		scheduleSelector.addItemListener(new ItemListener() {
			@Override
			public void itemStateChanged(ItemEvent e) {
				if (e.getStateChange() == ItemEvent.SELECTED) {
					initOutputArea();
				}
			}
		});

		timeRangeSelector = new JComboBox<TimeRange>(TimeRange.values());
		timeRangeSelector.setSelectedItem(TimeRange.TwoWeeks);
		timeRangeSelector.addItemListener(new ItemListener() {
			@Override
			public void itemStateChanged(ItemEvent e) {
				if (e.getStateChange() == ItemEvent.SELECTED) {
					updateDateRange();
					initOutputArea();
				}
			}
		});

		startDateField = new JTextField("", 8);
		startDateField.addActionListener(new ActionListener() {
			@Override
			public void actionPerformed(ActionEvent e) {
				updateDateRange();
				initOutputArea();
			}
		});
		endDateField = new JTextField("", 8);
		endDateField.setEditable(false);

		outputFormatSelector = new JComboBox<OutputFormat>(OutputFormat.values());

		outputArea = new JTextArea(20, 80);
		outputArea.setEditable(false);
		outputArea.setFont(new Font("Courier New", Font.PLAIN, 12));

		final JPanel row = new JPanel(new FlowLayout(FlowLayout.LEADING, 10, 5));
		row.add(startDateField);
		row.add(new JLabel("für"));
		row.add(timeRangeSelector);
		row.add(new JLabel("bis zum"));
		row.add(endDateField);

		final JPanel settingsPanel = new FormBuilder().addLeft(new JLabel("Verein"))
				.addRight(scheduleSelector).addLeft(new JLabel("Termine ab dem")).addRight(row)
				.addLeft(new JLabel("Ausgabeformat")).addRight(outputFormatSelector).getForm();

		final JPanel buttonPanel = new JPanel(new FlowLayout());
		buttonPanel.add(new JButton(actionStartGrabbing));
		buttonPanel.add(new JButton(actionSaveResult));

		getContentPane().add(settingsPanel, BorderLayout.NORTH);
		getContentPane().add(new JScrollPane(outputArea), BorderLayout.CENTER);
		getContentPane().add(buttonPanel, BorderLayout.SOUTH);

		// set initial state
		actionSaveResult.setEnabled(false);
		updateDateRange();
		initOutputArea();
	}
}
package de.amr.web.fussballde.model;

import java.io.File;
import java.io.Reader;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;
import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlElementWrapper;
import javax.xml.bind.annotation.XmlRootElement;

/**
 * A list of schedules which is persisted as an XML file (scheduleList.xml).
 * 
 * @author Armin Reichert
 */
@XmlRootElement(namespace = "armin.fussball_de.xml.jaxb.model")
public class TeamScheduleCollection {

	public static TeamScheduleCollection createFromXml(Reader reader) {
		try {
			final JAXBContext context = JAXBContext.newInstance(TeamScheduleCollection.class);
			final Unmarshaller um = context.createUnmarshaller();
			return (TeamScheduleCollection) um.unmarshal(reader);
		} catch (Exception x) {
			throw new RuntimeException("Cannot read team schedule", x);
		}
	}

	@XmlElementWrapper(name = "scheduleCollection")
	@XmlElement(name = "schedule")
	private final ArrayList<TeamSchedule> scheduleList;

	public TeamScheduleCollection() {
		scheduleList = new ArrayList<TeamSchedule>();
	}

	public List<TeamSchedule> asList() {
		return Collections.unmodifiableList(scheduleList);
	}

	public void write(File file) {
		try {
			final JAXBContext context = JAXBContext.newInstance(getClass());
			final Marshaller m = context.createMarshaller();
			m.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, Boolean.TRUE);
			m.marshal(this, file);
		} catch (Exception x) {
			throw new RuntimeException("Cannot write team schedule", x);
		}
	}

}
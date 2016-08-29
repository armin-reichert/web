package de.amr.web.dfbnet;

import java.io.PrintStream;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;

/**
 * List of records exported from DFBNet database.
 */
public class DFBNetRecordList implements Iterable<DFBNetRecord>
{
	final List<String> columnIDs;
	final List<DFBNetRecord> records = new ArrayList<>();

	public DFBNetRecordList(String[] columnIDs) {
		this.columnIDs = Arrays.asList(columnIDs);
		handleDuplicateIDs();
	}

	public DFBNetRecord add() {
		DFBNetRecord r = new DFBNetRecord(this);
		records.add(r);
		return r;
	}
	
	@Override
	public Iterator<DFBNetRecord> iterator() {
		return records.iterator();
	}

	public void print(PrintStream out) {
		for (DFBNetRecord record : records) {
			for (String columnID : columnIDs) {
				out.println(columnID + "=" + record.getField(columnID));
			}
		}
	}

	private void handleDuplicateIDs() {
		HashMap<String, Integer> occurences = new HashMap<>();
		int i = 0;
		for (String id : columnIDs) {
			if (occurences.containsKey(id)) {
				int n = occurences.get(id);
				occurences.put(id, n + 1);
				columnIDs.set(i, id + (n + 1));
			} else {
				occurences.put(id, 1);
			}
			++i;
		}
	}
}

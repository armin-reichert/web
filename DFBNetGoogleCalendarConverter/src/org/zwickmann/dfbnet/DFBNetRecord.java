package org.zwickmann.dfbnet;

public class DFBNetRecord
{
	private final DFBNetRecordList parent;
	private final String[] fields;

	public DFBNetRecord(DFBNetRecordList parent) {
		this.parent = parent;
		fields = new String[parent.columnIDs.size()];
	}

	public String getField(String columnID) {
		return fields[indexOf(columnID)];
	}

	public String getField(DFBNetColumn columnID) {
		return fields[indexOf(columnID.toString())];
	}

	public void setField(String columnID, String value) {
		fields[indexOf(columnID)] = value;
	}
	
	private int indexOf(String columnID) {
		int index = parent.columnIDs.indexOf(columnID);
		if (index == -1) {
			throw new IllegalArgumentException("Illegal column ID:" + columnID);
		}
		return index;
	}

	public void setField(int i, String value) {
		fields[i] = value;
	}

	public String concat(Object... objects) {
		StringBuilder sb = new StringBuilder();
		for (Object object : objects) {
			if (object instanceof DFBNetColumn) {
				sb.append(getField((DFBNetColumn) object));
			} else {
				sb.append(object == null ? "" : String.valueOf(object));
			}
		}
		return sb.toString();
	}
}

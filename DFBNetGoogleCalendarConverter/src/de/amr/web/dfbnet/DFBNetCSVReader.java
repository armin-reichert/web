package de.amr.web.dfbnet;

import java.io.BufferedReader;
import java.io.IOException;
import java.nio.charset.Charset;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;

public class DFBNetCSVReader
{
	private Charset charSet;
	private String line;

	public DFBNetCSVReader() {
		charSet = StandardCharsets.ISO_8859_1;
	}

	public void setCharset(Charset charSet) {
		this.charSet = charSet;
	}

	public DFBNetRecordList read(Path inputFilePath) throws IOException {
		BufferedReader in = Files.newBufferedReader(inputFilePath, charSet);
		// Header line with column IDs:
		line = in.readLine();
		DFBNetRecordList records = new DFBNetRecordList(line.split(";"));
		// Data lines:
		while ((line = in.readLine()) != null) {
			DFBNetRecord record = records.add();
			int column = 0;
			for (String value : line.split(";")) {
				record.setField(column++, value);
			}
		}
		in.close();
		return records;
	}
}

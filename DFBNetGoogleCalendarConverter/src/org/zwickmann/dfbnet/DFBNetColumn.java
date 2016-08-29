package org.zwickmann.dfbnet;

/**
 * Columns of CSV file exported from DFBNet.
 */
public enum DFBNetColumn {
	Saison("Saison"), 
	Verband("Verband"), 
	Spielgebiet("Spielgebiet"), 
	Staffel("Staffel"), 
	Spielstätte("Spielstätte"),
	Spielstätten_Nr("Spielstätten-Nr."), 
	Straße("Straße"), 
	PLZ("PLZ"), 
	Ort("Ort"), 
	Platznummer("Platznummer"), 
	Platztyp("Typ"), 
	Größe("Größe"), 
	Max_parallele_Spiele("Max. parallele Spiele"), 
	Max_Spiele_Tag("Max. Spiele/Tag"), 
	Max_Spiele_Wochenende("Max. Spiele/Wochenende"), 
	Früheste_Anstoßzeit("Früheste Anstoßzeit"), 
	Späteste_Anstoßzeit("Späteste Anstoßzeit"), 
	Mittagspause("Mittagspause"), 
	Wochentag("Wochentag"), 
	Spieldatum("Spieldatum"), 
	Uhrzeit("Uhrzeit"), 
	Sptg("Sptg."), 
	Spielkennung("Spielkennung"), 
	Typ("Typ2"), // duplicate ID!
	Liga("Liga"), 
	Heimmannschaft("Heimmannschaft"), 
	Gastmannschaft("Gastmannschaft"), 
	Spielleitung("Spielleitung"), 
	Assistent_1("Assistent 1"), 
	Assistent_2("Assistent 2"), 
	SpielleitungAusweisnr("SpielleitungAusweisnr."), 
	Assistent_1Ausweisnr("Assistent 1Ausweisnr."), 
	Assistent_2Ausweisnr("Assistent 2Ausweisnr."), 
	SpielleitungSchirigebiet("SpielleitungSchirigebiet"), 
	Assistent_1Schirigebiet("Assistent 1Schirigebiet"), 
	Assistent_2Schirigebiet("Assistent 2Schirigebiet");

	private final String columnID;

	private DFBNetColumn(String columnID) {
		this.columnID = columnID;
	}

	public String toString() {
		return columnID;
	};
}

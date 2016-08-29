package de.amr.web.fussballde.misc;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.util.Arrays;
import java.util.List;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

public class NextGamesGrabber {

  private static final String COMMA = ",";
  
  private static final List<String> GARBAGE = Arrays.asList(
      " Weiter zur Vereinsseite",
      " Für weitere Informationen klicken Sie bitte"
  );

  private static String quote(String text) {
    return "\"" + text + "\"";
  }
  
  private static String cleanup(String text) {
    return text 
        .replaceAll("\\P{Graph}&&[^äöüß]", " ")
        .replaceAll("\\|", ",")
        .replaceAll("(\\p{Space})+,", ",")
        .replaceAll("(\\p{Space})+", " ")
        .trim();
  }
  
  
  private static String removeTrailingGarbage(String text) {
    for (String g : GARBAGE) {
      int pos = text.indexOf(g);
      if (pos >= 0) {
        return text.substring(0, pos);
      }
    }
    return text;
  }
  
  private static String removeDay(String text) {
    int pos = text.indexOf(", ");
    if (pos >= 0) {
      return text.substring(pos + 2);
    }
    return text;
  }
  
  private final PrintWriter w;
  private final String url;
  
  /**
   * "Beginnt am", "Beginnt um", "Betreff", "Ort", "Beschreibung"
   */
  private class Row {
    String startDate;
    String startTime;
    String location;
    String subject;
    String description;
    
    private void println() {
      w.print(quote(startDate));
      w.print(COMMA);
      w.print(quote(startTime));
      w.print(COMMA);
      w.print(quote(subject));
      w.print(COMMA);
      w.print(location == null ? quote(getLocationFromDescription()) : quote(location) );
      w.print(COMMA);
      w.print(quote(description));
      w.println();
    }
    
    private String getLocationFromDescription() {
      String pattern = "Spielstätte:";
      int pos = description.indexOf(pattern);
      if (pos >= 0) {
        return description.substring(pos + pattern.length() + 1)
            .replaceAll("(Rasenplatz|Hartplatz|Kunstrasen(platz)?)", "")
            + ", Saarland, Deutschland";
      }
      return "";
    }
  }
  
  private void processTable(Element table) {
    Row headerRow = new Row();
    headerRow.startDate = "Beginnt am";
    headerRow.startTime = "Beginnt um";
    headerRow.subject = "Betreff";
    headerRow.location = "Ort";
    headerRow.description = "Beschreibung";
    headerRow.println();
    Element body = table.getElementsByTag("tbody").first();
    for (Element tableRow : body.getElementsByTag("tr")) {
      Row row = processTableRow(table, tableRow);
      if (row != null) {
        row.println();
      }
    }
  }
  
  private Row processTableRow(Element table, Element tableRow) {
    Elements cells = tableRow.getElementsByTag("td");
    if (cells.isEmpty()) {
      return null;
    }
    Row row = new Row();
    StringBuilder buffer = new StringBuilder();
    for (int i = 0, n = cells.size(); i < n; ++i) {
      Element cell = cells.get(i);
      String cellText = "";
      if (!cell.getElementsByTag("a").isEmpty()) {
        cellText = removeTrailingGarbage(cell.getElementsByTag("a").first().text());
      } else {
        cellText = removeTrailingGarbage(cell.text());
      }
      switch (i) {
      case 0:
        row.startDate = cleanup(removeDay(cellText));
        break;
      case 1:
        row.startTime = cleanup(cellText);
        break;
      case 2:
        buffer.append(cellText).append(": ");
        break;
      case 3:
      case 4:
        buffer.append(cellText).append(" ");
        break;
      case 5:
        buffer.append(cellText);
        row.subject = cleanup(buffer.toString());
        buffer = new StringBuilder();
        break;
      case 6:
        break; // "Ergebnis"
      case 7:
        buffer.append(cellText);
        row.description = cleanup(buffer.toString());
        buffer = new StringBuilder();
        break;
      }
    }
    return row;
  }

  public NextGamesGrabber(OutputStream os, String url) {
    this.w = new PrintWriter(os);
    this.url = url;
  }
  
  public void run() throws IOException {
    Document doc = Jsoup.connect(url).get();
    processTable(doc.select("table.egmMatchesTable").last());
    w.close();
  }
  
  /**
   * @param args 
   *    args[0] = URL of page containing event table e.g.
   *    "http://community.fussball.de/de/verein/jfg-hochwald-losheim/43147535.html"
   * @throws IOException
   */
  public static void main(String[] args) throws IOException {
    if (args.length < 1) {
      System.err.println("Usage: java -jar <archive.jar> http://community.fussball.de/de/verein/...");
      return;
    }
    String url = args[0];
    OutputStream out = System.out;
    if (args.length > 1) {
      File file = new File(args[1]);
      out = new FileOutputStream(file);
      System.out.println("Ausgabe wird nach " + file.getPath() + " geschrieben...");
    }
    
//    String startDate = "1.10.2013";
//    String endDate = "31.10.2013";
//    BrowserController browser = new BrowserController();
//    browser.select(url, startDate, endDate);
//    String html = browser.getHtml();
//    browser.quit();
    
    new NextGamesGrabber(out, url).run();
  }

}

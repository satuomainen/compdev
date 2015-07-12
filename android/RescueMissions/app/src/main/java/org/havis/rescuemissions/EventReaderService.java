package org.havis.rescuemissions;

import android.util.Log;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

public class EventReaderService {
    private static final String TAG = "EventReaderService";
    private static final String ITEM_IDENTIFIER = "item";

    public EventReaderService() {
        super();
    }

    public List<RescueEvent> readEventsFeed(String feedUrl) {
        List<RescueEvent> markers = new ArrayList<>();
        try {
            NodeList itemList = getRssNodesByTagName(feedUrl, ITEM_IDENTIFIER);

            for(int i = 0; i < itemList.getLength(); i++) {
                Node item = itemList.item(i);
                if(item.getNodeType() == Node.ELEMENT_NODE) {
                    markers.add(createRescueEvent(item));
                }
            }
        }
        catch (IOException | SAXException | ParserConfigurationException e) {
            Log.e(TAG, "readEventsFeed failed: " + e.toString(), e);
        }
        return markers;
    }

    private RescueEvent createRescueEvent(Node node) {
        Element element = (Element)node;
        NodeList title = element.getElementsByTagName("title");
        NodeList description = element.getElementsByTagName("description");
        NodeList geoLat = element.getElementsByTagName("geo:lat");
        NodeList geoLon = element.getElementsByTagName("geo:long");

        final Double lat = Double.parseDouble(geoLat.item(0).getChildNodes().item(0).getNodeValue());
        final Double lon = Double.parseDouble(geoLon.item(0).getChildNodes().item(0).getNodeValue());

        final String titleString = title.item(0).getChildNodes().item(0).getNodeValue();
        final String descString = description.item(0).getChildNodes().item(0).getNodeValue();

        return new RescueEvent(lat, lon, titleString, descString);
    }

    private NodeList getRssNodesByTagName(String feedUrl, String tagName) throws SAXException, IOException, ParserConfigurationException {
        URL url = new URL(feedUrl);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();

        if(conn.getResponseCode() == HttpURLConnection.HTTP_OK){
            DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
            DocumentBuilder db = dbf.newDocumentBuilder();
            Document doc;
            doc = db.parse(url.openStream());
            doc.getDocumentElement().normalize();
            return doc.getElementsByTagName(tagName);
        }
        return null;
    }
}

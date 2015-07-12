package org.havis.rescuemissions;

import android.util.JsonReader;
import android.util.JsonWriter;

import java.io.IOException;
import java.io.StringReader;
import java.io.StringWriter;
import java.util.ArrayList;
import java.util.List;

public class RescueEventHelper {
    private static final String EVENT_TITLE_FIELD_NAME = "title";
    private static final String EVENT_SNIPPET_FIELD_NAME = "snippet";
    private static final String EVENT_LATITUDE_FIELD_NAME = "latitude";
    private static final String EVENT_LONGITUDE_FIELD_NAME = "longitude";

    public static ArrayList<String> getRescueEventsAsStringArrayList(List<RescueEvent> rescueEvents) throws IOException {
        ArrayList<String> rescueEventsAsStringArrayList = new ArrayList<>();
        for (RescueEvent rescueEvent : rescueEvents) {
            StringWriter writer = new StringWriter();
            new JsonWriter(writer)
                    .beginObject()
                    .name(EVENT_LATITUDE_FIELD_NAME).value(rescueEvent.getLatitude())
                    .name(EVENT_LONGITUDE_FIELD_NAME).value(rescueEvent.getLongitude())
                    .name(EVENT_TITLE_FIELD_NAME).value(rescueEvent.getTitle())
                    .name(EVENT_SNIPPET_FIELD_NAME).value(rescueEvent.getSnippet())
                    .endObject()
                    .close();
            rescueEventsAsStringArrayList.add(writer.toString());
        }
        return rescueEventsAsStringArrayList;
    }

    public static List<RescueEvent> getRescueEventsFromJsonList(List<String> rescueEventJsonList) throws IOException {
        List<RescueEvent> rescueEvents = new ArrayList<>();
        for (String jsonRescueEvent : rescueEventJsonList) {
            double latitude = 0.0D;
            double longitude = 0.0D;
            String title = "";
            String snippet = "";
            StringReader reader = new StringReader(jsonRescueEvent);
            JsonReader jsonReader = new JsonReader(reader);
            jsonReader.beginObject();
            while (jsonReader.hasNext()) {
                switch (jsonReader.nextName()) {
                    case EVENT_LATITUDE_FIELD_NAME:
                        latitude = jsonReader.nextDouble();
                        break;
                    case EVENT_LONGITUDE_FIELD_NAME:
                        longitude = jsonReader.nextDouble();
                        break;
                    case EVENT_TITLE_FIELD_NAME:
                        title = jsonReader.nextString();
                        break;
                    case EVENT_SNIPPET_FIELD_NAME:
                        snippet = jsonReader.nextString();
                }
            }
            rescueEvents.add(new RescueEvent(latitude, longitude, title, snippet));
            jsonReader.endObject();
        }

        return rescueEvents;
    }
}

package org.havis.rescuemissions;

import android.app.AlertDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.text.method.ScrollingMovementMethod;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.common.GoogleApiAvailability;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

public class RescueMissionsActivity extends AppCompatActivity {
    private static final String TAG = "RescueMissionsActivity";
    private static final Double FINLAND_LATITUDE = 62.369120D;
    private static final Double FINLAND_LONGITUDE = 26.434715D;
    private static final LatLng FINLAND_LOCATION = new LatLng(FINLAND_LATITUDE, FINLAND_LONGITUDE);
    private static final float INITIAL_ZOOM_LEVEL = 6.0F;
    private static final String SAVED_RESCUE_EVENTS_JSON = "rescueEventsJson";

    private GoogleMap googleMap;
    private EventTypeResolver eventTypeResolver;
    private List<RescueEvent> rescueEvents = new ArrayList<>();
    private AlertDialog acknowledgementDialog = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.app_layout);
        if (savedInstanceState != null) {
            ArrayList<String> jsonRescueEvents = savedInstanceState.getStringArrayList(SAVED_RESCUE_EVENTS_JSON);
            if (jsonRescueEvents != null && !jsonRescueEvents.isEmpty()) {
                initializeRescueEventsFromJson(jsonRescueEvents);
            }
        }
        else {
            runEventReaderTask();
        }
        eventTypeResolver = new EventTypeResolver(this.getResources());
        setUpMapIfNeeded();
        setupAcknowledgementDialog();
    }

    @Override
    protected void onResume() {
        super.onResume();
        setUpMapIfNeeded();
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        try {
            outState.putStringArrayList(
                    SAVED_RESCUE_EVENTS_JSON,
                    RescueEventHelper.getRescueEventsAsStringArrayList(rescueEvents));
        }
        catch (IOException e) {
            Log.e(TAG, "Failed to serialize rescue events for instance state", e);
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.options_menu, menu);
        menu.findItem(R.id.help_about).setOnMenuItemClickListener(new MenuItem.OnMenuItemClickListener() {
            @Override
            public boolean onMenuItemClick(MenuItem item) {
                showAcknowledgements();
                return true;
            }
        });

        return true;
    }

    private void initializeRescueEventsFromJson(ArrayList<String> rescueEventJsonList) {
        rescueEvents.clear();
        try {
            rescueEvents.addAll(RescueEventHelper.getRescueEventsFromJsonList(rescueEventJsonList));
        } catch (IOException e) {
            Toast.makeText(this, getResources().getString(R.string.feed_failure), Toast.LENGTH_LONG).show();
            Log.e(TAG, "Failed to restore rescue events", e);
        }
    }

    private void setupAcknowledgementDialog() {
        final String openSourceLicence = GoogleApiAvailability.getInstance().getOpenSourceSoftwareLicenseInfo(this);

        StringBuilder message = new StringBuilder()
                .append(getResources().getString(R.string.acknowledgements_message))
                .append("\n")
                .append(openSourceLicence);

        final View dialogView = LayoutInflater.from(this).inflate(R.layout.help_about_layout, null);
        TextView textView = (TextView) dialogView.findViewById(R.id.helpAboutTextView);
        textView.setText(message.toString());
        textView.setMovementMethod(new ScrollingMovementMethod());

        acknowledgementDialog = new AlertDialog.Builder(this)
                .setTitle(getResources().getString(R.string.acknowledgements_title))
                .setView(dialogView)
                .setCancelable(true)
                .create();
    }

    private void showAcknowledgements() {
        if (acknowledgementDialog == null) {
            setupAcknowledgementDialog();
        }
        acknowledgementDialog.show();
    }

    private void runEventReaderTask() {
        final Context context = this;
        Toast.makeText(context, context.getResources().getString(R.string.feed_start_toast), Toast.LENGTH_LONG).show();
        final EventReaderService eventReaderService = new EventReaderService();

        new AsyncTask<String, Void, List<RescueEvent>>() {
            @Override
            protected List<RescueEvent> doInBackground(String... params) {
                return eventReaderService.readEventsFeed(context.getResources().getString(R.string.feed_url));
            }
            @Override
            protected void onPostExecute(List<RescueEvent> items) {
                rescueEventsReady(items);
                Toast.makeText(context, context.getResources().getString(R.string.feed_success_toast), Toast.LENGTH_SHORT).show();
            }
        }.execute();
    }

    private void setUpMapIfNeeded() {
        if (googleMap == null) {
            ((SupportMapFragment) getSupportFragmentManager().findFragmentById(R.id.map))
                    .getMapAsync(new OnMapReadyCallback() {
                        @Override
                        public void onMapReady(GoogleMap map) {
                            googleMap = map;
                            map.animateCamera(CameraUpdateFactory.newLatLngZoom(FINLAND_LOCATION, INITIAL_ZOOM_LEVEL));
                            rescueEventsReady(rescueEvents);
                        }
                    });
        }
    }

    private void rescueEventsReady(List<RescueEvent> rescueEvents) {
        googleMap.clear();
        this.rescueEvents = rescueEvents;
        for (RescueEvent rescueEvent : rescueEvents) {
            rescueEventReady(rescueEvent);
        }
    }

    private void rescueEventReady(RescueEvent rescueEvent) {
        final String snippet = rescueEvent.getSnippet();
        final int iconResourceId = eventTypeResolver.resolveIconResourceId(snippet);
        googleMap.addMarker(new MarkerOptions()
                .position(new LatLng(rescueEvent.getLatitude(), rescueEvent.getLongitude()))
                .title(rescueEvent.getTitle())
                .snippet(snippet)
                .icon(BitmapDescriptorFactory.fromResource(iconResourceId)));
    }
}

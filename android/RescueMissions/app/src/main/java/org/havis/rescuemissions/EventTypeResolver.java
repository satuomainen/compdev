package org.havis.rescuemissions;

import android.content.res.Resources;

public class EventTypeResolver {

    protected Resources resources;

    public EventTypeResolver(Resources resources) {
        this.resources = resources;
    }

    public int resolveIconResourceId(String event) {

        if (event.contains(getString(R.string.alarm))) {
            return R.drawable.alarm;
        }
        else if(event.contains(getString(R.string.animal_rescue))) {
            return R.drawable.animal_rescue;
        }
        else if(event.contains(getString(R.string.car))) {
            return R.drawable.car;
        }
        else if(event.contains(getString(R.string.boat))) {
            return R.drawable.boat;
        }
        else if(event.contains(getString(R.string.train))) {
            return R.drawable.train;
        }
        else if(event.contains(getString(R.string.fire))) {
            return R.drawable.fire;
        }
        else if(event.contains(getString(R.string.fire_generic))) {
            return R.drawable.fire;
        }
        else if(event.contains(getString(R.string.forest_fire))) {
            return R.drawable.forest_fire;
        }
        else if(event.contains(getString(R.string.car_fire))) {
            return R.drawable.car_fire;
        }
        else if(event.contains(getString(R.string.human_rescue))) {
            return R.drawable.human_rescue;
        }
        else if(event.contains(getString(R.string.tree))) {
            return R.drawable.tree;
        }

        return R.drawable.alarm;
    }

    private String getString(int resource_id) {
        return resources.getString(resource_id);
    }
}


InteractiveMap.Navigation = {
    LOCATION_UPDATE_FREQUENCY: 1000,
    locationInterval: null,
    e: {
        btnEndNavigation: null
    }
};

/* To be executed on interval. */
InteractiveMap.Navigation.updateUserLocation = function() {};

/* Draws a path to the provided destinationLatLng and begins
the interval that updates the user location. */
InteractiveMap.Navigation.startNavigation = function(destinationLatLng) {};

/* Removes the path from the map, clears the interval and removes
the 'End Navigation' button. */
InteractiveMap.Navigation.endNavigation = function() {};

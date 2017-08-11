let RestroomInput = {
    map: null,
    /* list of elements */
    e: {
        map: document.getElementById("ri_map")
    },
    /* a zoom of 20 is close enough to see the streets clearly */
    STARTING_ZOOM: 20
};

RestroomInput.init = function(startingLocation) {
    /* load the map */
    this.map = new google.maps.Map(this.e.map, {
        zoom: this.STARTING_ZOOM,
        center: startingLocation
    })

    /* set a marker in the center position */
    new google.maps.Marker({
        position: startingLocation,
        map: this.map
    });
};

/* object literal to be replaced by actual user location */
RestroomInput.init({
    lat: -37.816,
    lng: 144.969
});

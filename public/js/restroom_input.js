let RestroomInput = {
    map: null,
    marker: null,
    latInp: document.getElementById("rr_lat"),
    lngInp: document.getElementById("rr_lng"),
    /* list of elements */
    e: {
        map: document.getElementById("ri_map")
    },
    /* a zoom of 15 is close enough to see the streets clearly */
    STARTING_ZOOM: 18
};

/* always use bind(RestroomInput) when using these */
RestroomInput.evtCallbacks = {
    updateLatLngInput: function() {
        let centerPos = this.map.getCenter();

        /* move the marker to the center of map */
        this.marker.setPosition(centerPos);

        this.latInp.value = centerPos.lat();
        this.lngInp.value = centerPos.lng();
    },
    useLocBtnClicked: function() {
        /* call tnav.location.getCurrentPosition here */
    }
};

RestroomInput.init = function(startingLocation) {
    /* map options */
    let mapOptions = {
        zoom: this.STARTING_ZOOM,
        maxZoom: this.STARTING_ZOOM,
        minZoom: this.STARTING_ZOOM,
        center: startingLocation,
        streetViewControl: false,
        scaleControl: false,
        rotateControl: false,
        zoomControl: false,
        scrollwheel: false,
        clickableIcons: false
    };

    /* load the map */
    this.map = new google.maps.Map(this.e.map, mapOptions);

    /* set a marker in the center position */
    this.marker = new google.maps.Marker({
        position: startingLocation,
        map: this.map
    });

    this.addListeners();
    this.checkForOldLatLng();
};

RestroomInput.addListeners = function() {
    /* when the center position of the map is changed */
    this.map.addListener("center_changed", 
        this.evtCallbacks.updateLatLngInput.bind(this));
};

/* if the user has been redirected back to the page and
is provided with lat/lng data as part of the <input> field,
move the map to that position immediately */
RestroomInput.checkForOldLatLng = function() {
    if (this.latInp.value && this.lngInp.value) {
        this.map.setCenter({
            lat: parseFloat(this.latInp.value),
            lng: parseFloat(this.lngInp.value)
        });
    }
};

/* object literal to be replaced by actual user location */
RestroomInput.init({
    lat: -37.816,
    lng: 144.969
});

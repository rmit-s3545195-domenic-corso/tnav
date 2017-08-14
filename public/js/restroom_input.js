let RestroomInput = {
    map: null,
    marker: null,
    latInp: document.getElementById("rr_lat"),
    lngInp: document.getElementById("rr_lng"),
    /* list of elements */
    e: {
        map: document.getElementById("ri_map"),
        useLocBtn: document.getElementById("ri_use_loc_btn")
    },
    /* a zoom of 18 is close enough to see the streets clearly */
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

        let success = function(geoPos) {
            /* grab lat/lng data from the Geoposition object
            returned from browser */
            let lat = geoPos.coords.latitude;
            let lng = geoPos.coords.longitude;

            /* move the center position of the map to match the
            lat/lng values returned from browser */
            this.map.setCenter({
                lat: lat,
                lng: lng
            });
        };

        let failure = function() {
            alert("A request for your location has failed");
        };

        tnav.location.getPosition(success.bind(this), 
            failure.bind(this));
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

    this.e.useLocBtn.addEventListener("click", 
        this.evtCallbacks.useLocBtnClicked.bind(this));
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

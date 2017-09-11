tnav.restroomInput = {
    map: null,
    marker: null,
    latInp: document.getElementById("rr_lat"),
    lngInp: document.getElementById("rr_lng"),
    e: {
        map: document.getElementById("ri_map"),
        useLocBtn: document.getElementById("ri_use_loc_btn")
    },
    /* A zoom of 18 is close enough to see the streets clearly. */
    STARTING_ZOOM: 18
};

/* always use bind(tnav.restroomInput) when using these */
tnav.restroomInput.evtCallbacks = {
    updateLatLngInput: function() {
        let centerPos = this.map.getCenter();
        this.marker.setPosition(centerPos);

        this.latInp.value = centerPos.lat();
        this.lngInp.value = centerPos.lng();
    },
    useLocBtnClicked: function() {
        let success = function(geoPos) {
            this.map.setCenter({
                lat: geoPos.coords.latitude,
                lng: geoPos.coords.longitude
            });
        };

        let failure = function() {
            alert("A request for your location has failed.");
        };

        tnav.location.getPosition(success.bind(this),
            failure.bind(this));
    }
};

tnav.restroomInput.init = function(startingLocation) {
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

    this.map = new google.maps.Map(this.e.map, mapOptions);
    this.marker = new google.maps.Marker({
        position: startingLocation,
        map: this.map
    });

    this.addListeners();
    this.checkForOldLatLng();
};

tnav.restroomInput.addListeners = function() {
    this.map.addListener("center_changed",
        this.evtCallbacks.updateLatLngInput.bind(this));

    this.e.useLocBtn.addEventListener("click",
        this.evtCallbacks.useLocBtnClicked.bind(this));
};

tnav.restroomInput.checkForOldLatLng = function() {
    if (this.latInp.value && this.lngInp.value) {
        this.map.setCenter({
            lat: parseFloat(this.latInp.value),
            lng: parseFloat(this.lngInp.value)
        });
    }
};

tnav.restroomInput.init({
    lat: -37.816,
    lng: 144.969
});
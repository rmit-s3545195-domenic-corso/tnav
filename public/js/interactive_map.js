let InteractiveMap = {
    map: null,
    youAreHereMarker: null,
    youAreHereInfoWindow: null,
    e: {
        map: document.getElementById("map"),
        findNearMeBtn: document.getElementById("btn_use_loc")
    },
    STARTING_ZOOM: 14
};

InteractiveMap.evtCallbacks = {
    findNearMe: function() {
        tnav.location.getPosition(
            /* Success */
            function(geoPos) {
                InteractiveMap.setCenterPos(geoPos);
                InteractiveMap.setYouAreHere(geoPos);
            },
            /* Failure */
            function() {

            }
        );
    }
};

InteractiveMap.setCenterPos = function(geoPos) {
    this.map.setCenter({
        lat: geoPos.coords.latitude,
        lng: geoPos.coords.longitude
    });
};

InteractiveMap.setYouAreHere = function(geoPos) {
    this.youAreHereMarker.setPosition({
        lat: geoPos.coords.latitude,
        lng: geoPos.coords.longitude
    });

    this.youAreHereInfoWindow.open(this.map, this.youAreHereMarker);
};

/* Make a request to database to find restrooms around a particular
latLng location, then fill results and show markers on map */
InteractiveMap.fetchRestroomList = function(latLng) {

};

InteractiveMap.init = function() {
    let mapOptions = {
        zoom: this.STARTING_ZOOM,
        center: tnav.DEFAULT_MAPS_POS,
        streetViewControl: false,
        scaleControl: false,
        rotateControl: false,
        zoomControl: false,
        scrollwheel: false,
        clickableIcons: false
    };

    /* Load map */
    this.map = new google.maps.Map(this.e.map, mapOptions);

    /* Initialize 'You are here' marker */
    this.youAreHereMarker = new google.maps.Marker({
        map: this.map,
        animation: google.maps.Animation.DROP
    });

    this.youAreHereInfoWindow = new google.maps.InfoWindow({
         content: 'You are here'
    });

    this.addListeners();
};

InteractiveMap.addListeners = function() {
    this.e.findNearMeBtn.addEventListener("click",
    this.evtCallbacks.findNearMe.bind(this));
};

InteractiveMap.init();

let InteractiveMap = {
    map: null,
    youAreHereMarker: null,
    youAreHereInfoWindow: null,
    resultsMarkers: [],
    e: {
        map: document.getElementById("map"),
        findNearMeBtn: document.getElementById("btn_use_loc")
    },
    STARTING_ZOOM: 17
};

InteractiveMap.evtCallbacks = {
    findNearMe: function() {
        tnav.location.getPosition(
            /* Success */
            function(geoPos) {
                geoPos = {
                    coords: {
                        latitude: -37.815957621953075,
                        longitude: 144.96910728836068
                    }
                }
                InteractiveMap.setCenterPos(geoPos);
                InteractiveMap.setYouAreHere(geoPos);
                InteractiveMap.fetchRestroomList({
                    lat: geoPos.coords.latitude,
                    lng: geoPos.coords.longitude
                });
            },
            /* Failure */
            function() {

            }
        );
    },
    /* When the response from the database given a geoPos has responded
    with a list of restrooms */
    resultsReturned: function(response) {
        let restroomResults = JSON.parse(response);

        this.ui.addNewResultSet(restroomResults);
        this.newMarkerSet(restroomResults);
    }
};

InteractiveMap.hideAllResultMarkers = function() {
    for (let i = 0; i < this.resultsMarkers.length; i++) {
        this.resultsMarkers[i].setMap(null);
    }
};

InteractiveMap.newResultMarker = function(result) {
    let marker = new google.maps.Marker({
        position: {
            lat: parseFloat(result.lat),
            lng: parseFloat(result.lng)
        },
        map: this.map,
        label: result.name
    })
};

InteractiveMap.newMarkerSet = function(results) {
    this.hideAllResultMarkers();
    this.resultsMarkers = [];

    for (let i = 0; i < results.length; i++) {
        this.newResultMarker(results[i]);
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
    BL.httpGET("/search-query-geo", {
        lat: latLng.lat,
        lng: latLng.lng
    }, this.evtCallbacks.resultsReturned.bind(this));
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

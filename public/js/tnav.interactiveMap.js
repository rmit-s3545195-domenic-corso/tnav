tnav.interactiveMap = {
    REPORT_THRESHOLD: 10,
    map: null,
    youAreHereMarker: null,
    youAreHereInfoWindow: null,
    resultsMarkers: [],
    e: {
        map: document.getElementById("map"),
        findNearMeBtn: document.getElementById("btn_use_loc"),
        tagCheckboxes: document.querySelectorAll(".tag_checkbox")
    },
    DESKTOP_ZOOM: 17,
    MOBILE_ZOOM: 15
};

tnav.interactiveMap.evtCallbacks = {
    findNearMe: function() {
        tnav.location.getPosition(
            /* Success */
            function(geoPos) {
                tnav.interactiveMap.navigation.endNavigation();
                tnav.interactiveMap.setCenterPos(geoPos);
                tnav.interactiveMap.setYouAreHere(geoPos);

                tnav.location.recentLoc.lat = geoPos.coords.latitude;
                tnav.location.recentLoc.lng = geoPos.coords.longitude;

                tnav.interactiveMap.fetchRestroomList({
                    lat: geoPos.coords.latitude,
                    lng: geoPos.coords.longitude
                });
            },
            /* Failure */
            function() {}
        );
    },
    /* When the response from the database given a geoPos has responded
    with a list of restrooms */
    resultsReturned: function(response) {
        let restroomResults = JSON.parse(response);

	for (let r of restroomResults) {
            if (r.reports >= this.REPORT_THRESHOLD) {
                continue;
            }        
	    this.ui.addNewResultSet(restroomResults);
	    this.newMarkerSet(restroomResults);
        }

        this.navigation.scrollToMap();
    }
};

tnav.interactiveMap.hideAllResultMarkers = function() {
    for (let i = 0; i < this.resultsMarkers.length; i++) {
        this.resultsMarkers[i].setMap(null);
    }
};

tnav.interactiveMap.newResultMarker = function(result, resultNum) {
    let marker = new google.maps.Marker({
        position: {
            lat: parseFloat(result.lat),
            lng: parseFloat(result.lng)
        },
        map: this.map,
        label: resultNum.toString()
    });

    this.resultsMarkers.push(marker);
};

tnav.interactiveMap.newMarkerSet = function(results) {
    this.hideAllResultMarkers();
    this.resultsMarkers = [];

    for (let i = 0; i < results.length; i++) {
        this.newResultMarker(results[i], i + 1);
    }
};

tnav.interactiveMap.setCenterPos = function(geoPos) {
    this.map.setCenter({
        lat: geoPos.coords.latitude,
        lng: geoPos.coords.longitude
    });
};

tnav.interactiveMap.setYouAreHere = function(geoPos) {
    this.youAreHereMarker.setPosition({
        lat: geoPos.coords.latitude,
        lng: geoPos.coords.longitude
    });

    this.youAreHereInfoWindow.open(this.map, this.youAreHereMarker);
};

/* Make a request to database to find restrooms around a particular
latLng location, then fill results and show markers on map */
tnav.interactiveMap.fetchRestroomList = function(latLng) {
    let tagIds = [];
    for (let i = 0; i < this.e.tagCheckboxes.length; i++) {
        if (this.e.tagCheckboxes[i].checked) {
            tagIds.push(this.e.tagCheckboxes[i].getAttribute("data-id"));
        }
    }

    BL.httpGET("/search-query-geo", {
        lat: latLng.lat,
        lng: latLng.lng,
        filter: tagIds
    }, this.evtCallbacks.resultsReturned.bind(this));
};

tnav.interactiveMap.reportRestroom = function(id) {
    BL.httpGET("/report-restroom/" + id, {}, console.log);
};


tnav.interactiveMap.init = function() {
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
    this.navigation.init(this.map);
    this.reviews.init(this.map.getDiv());
    this.customSearch.init();
    this.ui.adjustAll();
};

tnav.interactiveMap.addListeners = function() {
    this.e.findNearMeBtn.addEventListener("click",
    this.evtCallbacks.findNearMe.bind(this));
};

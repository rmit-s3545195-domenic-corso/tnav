tnav.interactiveMap.navigation = {
    LOCATION_UPDATE_FREQUENCY: 2000,
    locationInterval: null,
    e: {
        btnEndNavigation: null
    },
    
    map: null,
    directionsService: null,
    directionsDisplay: null,
    trackingCircle: null
};

tnav.interactiveMap.navigation.init = function(map) {
    this.map = map;
    this.directionsService = new google.maps.DirectionsService();
    this.e.btnEndNavigation = document.getElementById("end_nav_btn");
    
    this.e.btnEndNavigation.addEventListener("click", this.endNavigation.bind(this));
};

tnav.interactiveMap.navigation.setEndNavigationBtnVisible = function(b) {
    let dispValue;
    switch (b) {
        case true:
            dispValue = "block";
            break;
        case false:
        default:
            dispValue = "none";
            break;
    }
    
    this.e.btnEndNavigation.style.display = dispValue;
};

/* To be executed on interval. */
tnav.interactiveMap.navigation.updateUserLocation = function() {
    if (!this.trackingCircle) return;
    
    tnav.location.getPosition(function(geoLoc) {
        this.trackingCircle.setPosition({
            lat: geoLoc.coords.latitude,
            lng: geoLoc.coords.longitude
        });
    }.bind(this), function() {});
    
};

tnav.interactiveMap.navigation.scrollToMap = function() {
    window.scrollTo(0, this.map.getDiv().offsetTop);
};

/* Draws a path to the provided destinationLatLng and begins
the interval that updates the user location. */
tnav.interactiveMap.navigation.startNavigation = function(destinationLatLng) {
    this.endNavigation();
    this.scrollToMap();
    
    tnav.location.getPosition(function(geoLoc) {
        let currentLocLatLng = new google.maps.LatLng(geoLoc.coords.latitude, 
                                                     geoLoc.coords.longitude);
        this.directionsService.route({
            origin: currentLocLatLng,
            destination: destinationLatLng,
            travelMode: "WALKING"
        }, function(response, status) {
            if (status == "OK") {
                tnav.interactiveMap.hideAllResultMarkers();
                tnav.interactiveMap.youAreHereInfoWindow.setMap(null);
                tnav.interactiveMap.youAreHereMarker.setMap(null);
                
                this.directionsDisplay = new google.maps.DirectionsRenderer();
                this.directionsDisplay.setMap(this.map);
                this.directionsDisplay.setDirections(response);
                
                this.trackingCircle = new google.maps.Marker({
                    position: currentLocLatLng,
                    map: this.map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        strokeColor: "",
                        strokeOpacity: 1,
                        strokeWeight: 1,
                        fillColor: "#F0F",
                        fillOpacity: 1,
                        scale: 7
                    }
                });
                
                this.locationInterval = setInterval(this.updateUserLocation.bind(this), 
                                                    this.LOCATION_UPDATE_FREQUENCY);
                
                this.setEndNavigationBtnVisible(true);
            }
        }.bind(this));
    }.bind(this), function() {
        alert("A request for your location has failed.")
    });
};

/* Removes the path from the map, clears the interval and removes
the 'End Navigation' button. */
tnav.interactiveMap.navigation.endNavigation = function() {
    clearInterval(this.locationInterval);
    
    if (this.trackingCircle) {
        this.trackingCircle.setMap(null);
        this.trackingCircle = null;
    }
    
    if (this.directionsDisplay) {
        this.directionsDisplay.setMap(null);
    }
    
    this.directionsDisplay = null;
    
    this.setEndNavigationBtnVisible(false);
};

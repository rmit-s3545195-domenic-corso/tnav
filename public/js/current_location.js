let CurrentLocation = {
  map: null,
  marker: null,
  e: {
    map: document.getElementById("map")
  },
};

CurrentLocation.functionReturns = {
  setCenterPos: function() {
    let centerPos = this.map.getCenter();

    /* move the marker to the center of map */
    this.marker.setPosition(centerPos);
  },

  getGeoPos: function() {
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

CurrentLocation.init = function(startingLocation) {
  let mapOptions = {
    zoom: 18;
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
};

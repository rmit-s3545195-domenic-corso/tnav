tnav.location = {
	recentLoc: {
		lat: 0,
		lng: 0
	}
};

tnav.location.getPosition = function(success, failure) {
    navigator.geolocation.getCurrentPosition(success,failure);
};

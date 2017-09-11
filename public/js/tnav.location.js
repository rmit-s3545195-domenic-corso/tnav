tnav.location = {};

tnav.location.getPosition = function(success, failure) {
    navigator.geolocation.getCurrentPosition(success,failure);
};

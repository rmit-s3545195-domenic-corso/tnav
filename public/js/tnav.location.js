tnav.location = {};

tnav.location.getPosition = function(success, failure) {
    /*get's location, success and failure commands determined by external code*/
    navigator.geolocation.getCurrentPosition(success,failure);
};

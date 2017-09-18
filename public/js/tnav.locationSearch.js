function initialize() {
  var address = (document.getElementById('inp_search'));
  var autocomplete = new google.maps.places.Autocomplete(address);
  autocomplete.setTypes(['geocode']);
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }
  });
}

function returnAddress() {
  geocoder = new google.maps.Geocoder();
  var address = document.getElementById("inp_search").value;
  geocoder.geocode({
    'address': address
  }, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {

      var latitude = results[0].geometry.location.lat());
      var longitude = results[0].geometry.location.lng());

      /* testing to make sure it returns lat/lng */
      alert(latitude);
      alert(longitude);

    } else {
      alert("An error was encountered due to:" + status);
    }
  });
}
google.maps.event.addDomListener(window, 'load', initialize);

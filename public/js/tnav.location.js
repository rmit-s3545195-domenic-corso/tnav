geoloc = navigator.geolocation;

geoloc.getCurrentPosition(success, failure);

/*
If the function succeeds
*/
function success(position)
{
  /*
  Gets longitude and latitude
  */
  var lat = position.coords.latitude;
  var long = position.coords.longitude;

  /*
  Returns googles API LatLng
  */
  var googleCord = new google.maps.LatLng(lat, long);
}

/*
When the function fails
*/
function failure()
{
  infoWindow.setContent('Error: Geolocation Service Failed');
}

geoloc = navigator.geolocation;

tnav.location = geoloc.getCurrentPosition(success, failure);

/*
If the function succeeds
*/
function success(position)
{
  /*
  Gets co-ordinates
  */
    var coords = {
      lat: position.coords.latitude,
      long: position.coords.longitude
  };
}


function failure()
{
}

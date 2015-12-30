function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Fehler: Standortfindung ausgeschaltet.' :
                        'Fehler: Ihr Browser unterstützt die Standortfindung nicht.');
}

function initAutocomplete() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 50.7753455, lng: 6.083886799999959},
    zoom: 18,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  
  var txtLat = document.getElementById("txtLat");
  var txtLng = document.getElementById("txtLng");
  
  var infoWindow = new google.maps.InfoWindow({map: map});
  var foundPlace = {};
    // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
	  
	  foundPlace = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
	  
	  txtLat.value = foundPlace.lat;
	  txtLng.value = foundPlace.lng;

//	  alert(foundPlace.lat, foundPlace.lng);
	  
	  infoWindow.setPosition(pos);
      infoWindow.setContent('Ihre Position.');
      map.setCenter(pos);
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation // TODO: do something else
    handleLocationError(false, infoWindow, map.getCenter());
  }

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // [START region_getplaces]
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
	  //document.getElementById("pac-input").setAttribute("value", place.geometry.location);
	  //alert(place.geometry.location);
	  foundPlace.lat = place.geometry.location.lat();
	  foundPlace.lng = place.geometry.location.lng();
	  
	  txtLat.value = foundPlace.lat;
	  txtLng.value = foundPlace.lng;
	  
	  //alert(foundPlace.lat);
	  map.setCenter(foundPlace);
	  infoWindow.setMap(null);
	  infoWindow = null;
    });
    map.fitBounds(bounds);
  });
  // [END region_getplaces]
}
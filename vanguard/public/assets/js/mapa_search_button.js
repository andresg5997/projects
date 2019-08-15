document.getElementById("pac-button").addEventListener("click", function (){
  GMaps.geocode({
    address: $('#pac-input').val(),
    callback: function(results, status) {
      if (status == 'OK') {
        var latlng = results[0].geometry.location;
        mapCreate.setCenter(latlng.lat(), latlng.lng());
        mapCreate.addMarker({
          lat: latlng.lat(),
          lng: latlng.lng()
        });
      }
    }
  });
});
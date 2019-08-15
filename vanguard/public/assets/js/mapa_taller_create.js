$(function () {
    //Mapa de Create
    coordsArray = coords.split(',');
    mapCreate = new GMaps({
        el: '#map-create',
        lat: coordsArray[0],
        lng: coordsArray[1],
        zoomControl: true,
        zoomControlOpt: {
            style: 'SMALL',
            position: 'TOP_LEFT'
        },
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false,
        overviewMapControl: false,
        disableDoubleClickZoom: true,
        zoom:10
    });
    if (coordsArray.length == 3) {
        mapCreate.addMarker({
            lat: coordsArray[0],
            lng: coordsArray[1]
        });
    }


  
    mapCreate.addListener('dblclick', function (e) {
        deleteMarkers();
        $('#coordenadas').val(e.latLng.lat()+","+e.latLng.lng());
        placeMarker(e.latLng);
        getCity(e.latLng);
    });

 


    function deleteMarkers(){
        mapCreate.removeMarkers();
    }

    function placeMarker(location) {
        var lat = location.lat();
        var long = location.lng();
        mapCreate.addMarker({
            lat: lat,
            lng: long
        });
    }

    function getCity(location) {
        GMaps.geocode({
          address: $('#coordenadas').val(),
          callback: function(results, status) {
            if (status == 'OK') {
              var length = Object.keys(results).length; 
              $('#ciudad').val(results[length-3].address_components[0].long_name);
              $('#pais').val(results[length-1].address_components[0].long_name);
            }
          }
        });
    }
});
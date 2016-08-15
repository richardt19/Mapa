<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
      html, body { height: 100%; margin: 0; padding: 0; }
      #map { height: 100%; }
      #floating-panel {
  position: absolute;
  top: 10px;
  left: 25%;
  z-index: 5;
  background-color: #fff;
  padding: 5px;
  border: 1px solid #999;
  text-align: center;
  font-family: 'Roboto','sans-serif';
  line-height: 30px;
  padding-left: 10px;
}
    </style>
  </head>
  <body>
    <div id="floating-panel">
      <b>Iniciar: </b>
      <input id="iniciar" type="button" value=Iniciar>
    </div>
    <div id="map"></div>
    <script type="text/javascript">

var map;
//Posición  inicial
  var myLatLng = {lat: 19.5163, lng: -99.1100};
  //Posición final
  var SLat = {lat: 19.5161, lng: -99.1061};
function initMap() {
  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer;

  

  var map = new google.maps.Map(document.getElementById('map'), {
    //center: {lat: 19.5163, lng: -99.1100},
    center : myLatLng,
    zoom: 16
  });

  directionsDisplay.setMap(map);
  var onChangeHandler = function(){
    calculateAndDisplayRoute(directionsService,directionsDisplay);
  };
  document.getElementById('iniciar').addEventListener('click', onChangeHandler);
}

  function calculateAndDisplayRoute(directionsService,directionsDisplay){
    directionsService.route({
      origin: myLatLng,
      destination: SLat,
      travelMode: google.maps.TravelMode.WALKING
    }, function(response,status){
      if (status === google.maps.DirectionsStatus.OK){
        directionsDisplay.setDirections(response);
      } else {
        window.alert('Error' + status);
      }
    });
  }

  /*var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      title: 'Hello World!'
  });

  var marker1 = new google.maps.Marker({
    position: {lat: 19.5161, lng: -99.1061},
    map: map,
    title: 'Second Marker'
  });
}*/

    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjz-j6vDU7A7QO61OOugJ1e5pqVH2KJ7c&callback=initMap">
    </script>
  </body>
</html>
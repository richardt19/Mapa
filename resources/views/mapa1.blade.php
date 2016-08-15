@extends('layout')
@section('content_map')
	<?php 
	/*
	*Recibimos por el número de conductores y clientes que se mostrarán
	*/
	$conductores = $_POST['N_Conductores'] ;
	$clientes = $_POST['N_Clientes'] ;

	/*
	*Calculamos las coordenadas aleatoriamente, siempre teniendo en cuenta que deben aparecer en el pedazo de mapa que determinamos
	*/
	for ($i=0; $i < $conductores; $i++) { 
		$X_Conductores[$i] = (rand(1,17090)/1000000)+19.507558 ;
		$Y_Conductores[$i] = (rand(1,37315)/1000000)-99.118796 ;
	}
	for ($j=0; $j < $clientes; $j++) { 
		$Y_Clientes[$j] = (rand(1,37315)/1000000)-99.118796 ;
		$X_Clientes[$j] = (rand(1,17090)/1000000)+19.507558 ;
	}

	/*
	*Determinamos si hay mas conductores o clientes
	*/
	if($i>$j){
		$min = $j;
		$max = $i;
	}
	else{
		$min = $i;
		$max = $j;
	}
	?>
	<!--Agregamos un panel flotante donde se mostrará la relación entre conductores y clientes-->
	<div id="floating-panel">
      <b>Selecciona para mostrar ruta </b><br>
      Conductores: <img src="http://www.altre.org/images/transporte/iconos-posicion-gradiente/marker_1.png"> - 
      Clientes: <img src="http://media.oregonlive.com/weather_impact/images/google-placemark-purple.jpg">
      <form>
      <?php
      	for ($k=0; $k<=$min; $k++) { 
      ?>
      	<input id="relacion{{$k}}" name="relacion" type="radio" value="{{$k}}">Conductor {{$k}} - Cliente {{$k}}<br>
  	  <?php
  	  	}
  	  ?>
  	  </form>
    </div>
    <!--Div donde se despliega el mapa (en este caso ocupa toda la pantalla)-->
	<div id="map"></div>
	<!--Panel para la información del trayecto a seguir por el conductor a pie para llegar al cliente-->
	<div id="floating-panel-right">
		<b>Información de trayecto</b>
	</div>
    <script type="text/javascript">

    	/*
    	*Función para saber que relación está seleccionada entre las opciones, para mostrar la ruta específica
    	*/
    	function detector() {
			var formulario = document.forms[0];
			for (var i = 0; i < formulario.relacion.length; i++) {
				if (formulario.relacion[i].checked) {
					break;
				}
			}
			return i;
		}

		/*
		*Cargamos las coordenadas aleatorias de los conductores en un arreglo en javascript para facilidad de uso futura
		*/
    	var conductores = [
    		<?php
      			for ($x=0; $x<$i; $x++) { 
      		?>
      			[{{$X_Conductores[$x]}},{{$Y_Conductores[$x]}}],
      		<?php
  	  			}
  	  		?>
    		];
    	 /*
    	 *Cargamos las coordenadas aleatorias de los clientes en un arreglo en javascript para facilidad de uso futura
    	 */
    	var clientes = [
    		<?php
      			for ($x=0; $x<$j; $x++) { 
      		?>
      			[{{$X_Clientes[$x]}},{{$Y_Clientes[$x]}}],
      		<?php
  	  			}
  	  		?>
    		];
    
		var map;
		//Posición central del mapa
  		var myLatLng = {lat: 19.5163, lng: -99.1100};

  		/*
  		*Función que despliega el mapa en la página
  		*/
		function initMap() {
		
	  		var directionsService = new google.maps.DirectionsService;
  			var directionsDisplay = new google.maps.DirectionsRenderer;

  			var map = new google.maps.Map(document.getElementById('map'), {
	    		center : myLatLng,
    			zoom: 15
  			});

  			var image = 'http://media.oregonlive.com/weather_impact/images/google-placemark-purple.jpg'; //icono diferente para distinguir a los clientes
  			/*
  			*Generamos todos los marcadores de los conductores
  			*/
  			var marcadores_conductores = [
  				<?php
      				for ($x=0; $x<$i; $x++) { 
      			?>
  					new google.maps.Marker({
    					position: {lat: conductores[{{$x}}][0], lng: conductores[{{$x}}][1]},
    					map: map,
    					title: 'Conductor ' + {{$x}},
    					label: {{$x}} + '',
    					//animation: google.maps.Animation.BOUNCE //Animación para los íconos, en caso de querer que se vean divertidos
  					}),
  				<?php
  	  				}
  	  			?>
  			];

  			/*
  			*Generamos todos los marcadores de los clientes
  			*/
  			var marcadores_clientes = [
  				<?php
      				for ($x=0; $x<$j; $x++) { 
      			?>
  					new google.maps.Marker({
    					position: {lat: clientes[{{$x}}][0], lng: clientes[{{$x}}][1]},
    					map: map,
    					title: 'Cliente ' + {{$x}},
    					label: {{$x}} + '',
    					icon: image,
    					//animation: google.maps.Animation.BOUNCE
  					}),
  				<?php
  	  				}
  	  			?>
  			];

	  		directionsDisplay.setMap(map);

	  		/*
	  		*Agregamos un evento onclick para saber cuando una ruta diferente es seleccionada
	  		*/
	  		var onChangeHandler = function(){
	    		calculateAndDisplayRoute(directionsService,directionsDisplay);
  			};
  			<?php
			   	for ($l=0; $l<=$min; $l++) { 
	  		?>
	  	 		document.getElementById('relacion{{$l}}').addEventListener('click', onChangeHandler);
  			<?php
	  			}
  			?>
		}

		/*
		*Función que calcula y muestra en el mapa la ruta a seguir por el conductor, hacia el cliente
		*/
  		function calculateAndDisplayRoute(directionsService,directionsDisplay){
		    var indice = detector();	//llamamos a la funcion para saber que ruta debemos mostrar
    		directionsService.route({
      			origin: {lat: conductores[indice][0], lng: conductores[indice][1]}, //punto de origen
      			destination: {lat: clientes[indice][0], lng: clientes[indice][1]}, //punto de destino
      			travelMode: google.maps.TravelMode.WALKING	//indicamos que la forma de viajar será caminando
    		}, function(response,status){
		    	if (status === google.maps.DirectionsStatus.OK){	//comprobamos que la ruta se haya generado exitosamente
			        directionsDisplay.setDirections(response);
			        var route = response.routes[0];
			        /*
			        *Desplegamos la información del trayecto en el panel derecho
			        */
			        var summaryPanel = document.getElementById('floating-panel-right');
			        summaryPanel.innerHTML = '';
			        for (var i = 0; i < route.legs.length; i++) {
			     		var routeSegment = i+1;
			     		summaryPanel.innerHTML += '<b>Información de trayecto</b><br>'
			     		summaryPanel.innerHTML += 'Distancia entre el conductor y el cliente: ' + route.legs[i].distance.text + '<br>'; //distancia a recorrer
			     		summaryPanel.innerHTML += 'Tiempo estimado que tardará en llegar:' + route.legs[i].duration.text + '<br>'; 	//Tiempo estimado en llegar
			        }
		    	} else {
	        		window.alert('Error' + status); //error al generar la ruta
      			}
    		});
  		}

    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjz-j6vDU7A7QO61OOugJ1e5pqVH2KJ7c&callback=initMap">
    </script>
@endsection
<!doctype html>
<html>
<head>
	<title>Hello World!</title>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script src="js/jquery.js"></script>
	<script src="js/keyboard-focus-hack.js"></script>
	<script src="js/math3d.js"></script>
	<script src="js/game.js"></script>
	<script src="js/maps.js"></script>
	<script src="js/cam.js"></script>
	<script src="js/socket.js"></script>
</head>
<body onload="init()" onkeydown="return keyDown(event)" onkeyup="return keyUp(event)" id="body" cz-shortcut-listen="true">
	<div id="map3d"></div>
	<div id="street"><h1 class="white-heading">Street view!</h1><br><img id="main-img" src=""></div>
	<script type="text/javascript">
		google.load("earth", "1");
		google.load("maps", "2");

		var ge = null;
		var cam;

		function init() {
			window.adr = 'San Fransisco';
			window.total_count = 10;
			window.view = 'earth';
			window.streetimg = document.getElementById('main-img');
		  google.earth.createInstance("map3d", initCB, failureCB);

		}

		function initCB(object) {
		  ge = object;
		  ge.getOptions().setFlyToSpeed(100);
		  conn = {}, window.WebSocket = window.WebSocket || window.MozWebSocket;
			var address = window.adr;
			<?php
				if(!isset($_GET['lat'])) { 
					?>
					$.ajax({
					  url:"http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
					  type: "POST",
					  success: function(res){
					    var lat = res.results[0].geometry.location.lat;
					    var lng = res.results[0].geometry.location.lng;
					    ge.getLayerRoot().enableLayerById(ge.LAYER_BUILDINGS, true);
					  	ge.getLayerRoot().enableLayerById(ge.LAYER_TERRAIN, true);
							createCheckpoints(ge, 10, lat, lng, 0);
						  cam = new FirstPersonCam(lat, lng);
						  getNearbyPlaces(lat, lng, function (nearby) {
						  	for (var i in nearby) {
						  		var place = nearby[i];
						  		generatePlace(ge, place.latitude, place.longitude, place.name);
						  	}
						  });
						  cam.updateCamera();
			        // openConnection();
						  ge.getWindow().setVisibility(true);
						  // generateCheckpoint(ge, lat, lng, 100);
						  keyboardFocusHack(ge);
					  }
					});	
			<?php
				} else {
					?>
					    var lat = <?= $lat ?>;
					    var lng = <?= $lng ?>;
					    ge.getLayerRoot().enableLayerById(ge.LAYER_BUILDINGS, true);
					  	ge.getLayerRoot().enableLayerById(ge.LAYER_TERRAIN, true);
							createCheckpoints(ge, 10, lat, lng, 0);
						  cam = new FirstPersonCam(lat, lng);
						  getNearbyPlaces(lat, lng, function (nearby) {
						  	for (var i in nearby) {
						  		var place = nearby[i];
						  		generatePlace(ge, place.latitude, place.longitude, place.name);
						  	}
						  });
						  cam.updateCamera();
			        // openConnection();
						  ge.getWindow().setVisibility(true);
						  // generateCheckpoint(ge, lat, lng, 100);
						  keyboardFocusHack(ge);
					<?php
				}	  

				?>

		var reset = function() {
  		document.getElementById('map3d').innerHTML = '';
  		google.earth.createInstance('map3d', initCB, failureCB);
		}

		  
		}

		function failureCB(object) {
		  /***
		   * This function will be called if plugin fails to load, in case
		   * you need to handle that error condition.
		   ***/
		   console.log(object);
		}

  </script>
</body>
</html>
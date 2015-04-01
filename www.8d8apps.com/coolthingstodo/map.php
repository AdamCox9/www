<?PHP

	//Should lay directions over map...
	//https://developers.google.com/maps/documentation/directions/
	//https://developers.google.com/maps/documentation/javascript/directions

	$lat = $_GET['lat'];
	$lng = $_GET['lng'];
	$title = $_GET['title'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Cool Things To Do</title>
  <meta name="description" content="Cool Things To Do">
  <meta name="author" content="Adam cox">
  <link rel="stylesheet" href="style.css?v=1.0">

  <script src="script.js"></script>

  <link href="style.css" rel="stylesheet" type="text/css" />


  <!-- Getting Started with JQuery: http://docs.jquery.com/Tutorials:Getting_Started_with_jQuery -->
  <!-- AJAX with JQuery: http://www.developertutorials.com/tutorials/ajax/getting-started-with-ajax-in-jquery-874/ -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDuCpFFXnVlH11SOXyQVVZH3ObNsWLfw3U&sensor=true"></script>

  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

  <style type="text/css">
    html { height: 100% }
    body { height: 100%; margin: 0; padding: 0 }
	td { border: 1px solid #888; }
	a { padding: 15px; line-height: 2; font-size: 16pt; font-family: arial; }
    #map_canvas { height: 100% }
  </style>


  <script type="text/javascript">


	$(document).ready(function() {

		initializeMap();

	});


	function initializeMap() {
		$("#map_canvas").show();
		
		var myLatLng = new google.maps.LatLng(<?PHP echo $lat; ?>,<?PHP echo $lng; ?>);

		var myOptions = {
		  center: myLatLng,
		  zoom: 14,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		//Probably shouldn't create a new map everytime...maybe just move the map and update the markers...good enough for now...
		var map = new google.maps.Map(document.getElementById("map_canvas"),
			myOptions);

		var marker = new google.maps.Marker({
			position: myLatLng,
			title: '<?PHP echo $title; ?>'
		});

		// To add the marker to the map, call setMap();
		marker.setMap(map);
	}



  </script>



  <!-- Google Maps Stuff: https://developers.google.com/maps/documentation/javascript/tutorial -->
  <!-- Search Nearby: https://developers.google.com/maps/documentation/places/ -->



</head>
<body>

	<h1>Cool Things to Do Nearby!</h1>
	<img src="powered-by-google-on-white.png">


	<!-- This is where the map will be -->
	<div id="map_canvas" style="text-align:center; display: none; width:90%; height:60%"></div>


</body>
</html>
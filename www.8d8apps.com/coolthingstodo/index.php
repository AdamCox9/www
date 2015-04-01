<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Cool Things To Do</title>
  <meta name="description" content="Cool Things To Do">
  <meta name="author" content="Adam cox">
  <link rel="stylesheet" href="style.css?v=1.0">

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

	//Could make another setInterval that will check every minute or so for new GPS location and update the content...

	//This is the currently set 'type' or category...
	var current = "";
	var defaultradius = 1000;
	var radius = defaultradius;

	//This will be the array of results so the map can be set properly...
	var mycontent = new Array(100);

	$(document).ready(function() {

		//This allows users to link to the sections on the website, also...
		var hash = location.hash;

		//Need to make a controller that will use the hash to display the correct content...

		//while(true){sleep(1)execute}
		setInterval(function()
		{
			//alert( window.location.hash.substring(1) + " - " + current );
			if( current != "WAIT" ) {
				if (location.hash == "" )
				{
					$("#links").show();
					$("#status p").hide();
				} else if ( window.location.hash.substring(1) != current )
				{
					ShowThingsToDo( window.location.hash.substring(1) );
				}
			}
		}, 100);

		$('#loadingDiv')
			.hide()  // hide it initially
			.ajaxStart(function() {
				$(this).show();
			})
			.ajaxStop(function() {
				$(this).hide();
			});

		//We only want to do something if we got their location...
		if ( ! navigator.geolocation )
		{

		  alert("I'm sorry, but geolocation services are not supported by your browser or you do not have a GPS device in your computer.");

		  //Hide the map...tell user to give permission or go away...
		  //Give a link to a modern browser?

		}  

	});

	function showLinks()
	{
		radius = defaultradius; //Let's reset the radius...terrible hacky way to do things...
		$("#links").show();
		$("#status p").hide();
		$("#map_canvas").hide();
	}

	function showMore()
	{
		radius = radius + 5000;
		ShowThingsToDo();
	}

	function ShowThingsToDo()
	{
		$("#loadingDiv").show();
		$("#links").hide();

		//Let's make the while(true) loop wait until the location is recieved and the JSON response is returned...
		current = "WAIT";

		//Need to make ajax call to get json...
		navigator.geolocation.getCurrentPosition( function (position) {  
			
			// Did we get the position correctly?
			//alert (position.coords.latitude);
			
			// To see everything available in the position.coords array:
			//for (key in position.coords) {alert(key)}

			//Let's get the hash value from the URL and set global 'current' value to it...
			//This is so 'current' will only get set when this function is executed...
			current = window.location.hash.substring(1);


			//Sample request that works:
			//"places.php?latitude=42.360262&longitude=-71.054839&type="+current

			//Make an AJAX call...
			$.getJSON("places.php?latitude="+position.coords.latitude+"&longitude="+position.coords.longitude+"&type="+current+"&radius="+radius,
				function(data) {
					var items = [];
					var thelink = "";

					/* This table would probably more easily be built utilizing the DOM instead of generating the HTML this way... */


					//Loop through the results...
					$.each(data.results, function(key, val) {

						//for ( key in val ){alert(key)}


						//Let's share these values:
						mycontent[key] = new Object();
						mycontent[key]['latitude'] = val.geometry.location.lat; //latitude
						mycontent[key]['longitude'] = val.geometry.location.lng; //longitude
						mycontent[key]['title'] = val.name; //title
						mycontent[key]['vicinity'] = val.vicinity; //vicinity
						

						thelink = "<a href='map.php?title="+escape(val.name)+"&lng="+val.geometry.location.lng+"&lat=" + val.geometry.location.lat + "'>Show Map</a>";
						items.push('<tr><td>' + val.name + '<br/>' + val.vicinity + '<br/>' + thelink + '</td></tr>');
					});

					//Don't forget to make a Zero Results message...
					$("#status p").show();
					if( items.length > 0 )
					{
						var displaymessage = '<a href="#" onclick="showLinks()">Show Categories</a><br>'+"<table>"+items.join("")+"</table>";
					} else {
						var displaymessage = '<a href="#" onclick="showLinks()">Show Categories</a><br>';
						radius = radius + 5000; //Increase radius
						ShowThingsToDo(); //Recursively call itself if nothing found with previous radius...
					}
					$("#links").hide(); //Let's hide the links...seems like a bad way to do things...
					$("#status p").html(displaymessage + "<a href='#" + current + "' onclick='showMore();'>Show More</a>");

			});
			
		}, // next function is the error callback
			function (error)
			{
				switch(error.code) 
				{
					case error.TIMEOUT:
						alert ('Timeout');
						break;
					case error.POSITION_UNAVAILABLE:
						alert ('Position unavailable');
						break;
					case error.PERMISSION_DENIED:
						alert ('Permission denied');
						break;
					case error.UNKNOWN_ERROR:
						alert ('Unknown error');
						break;
				}
			}
		);

		return true;
	}



  </script>



  <!-- Google Maps Stuff: https://developers.google.com/maps/documentation/javascript/tutorial -->
  <!-- Search Nearby: https://developers.google.com/maps/documentation/places/ -->



</head>
<body>

	<h1>Cool Things to Do Nearby!</h1>
	<img src="powered-by-google-on-white.png">

	<!-- This is where the loading message will be shown while making AJAX requests -->
	<div id="loadingDiv">Loading...<br/><img src="ajax-loader.gif"></div>

	<!-- This is where the map will be -->
	<div id="map_canvas" style="text-align:center; display: none; width:90%; height:60%"></div>

	<!-- Not sure why it's still named status but this is where the results will be displayed -->
	<div id="status"><p></p></div>

	<!-- This is the default set of links that visitors and search engines will find -->
	<!-- Probably couldn't be followed by search engines, but content changes based on Lat/Long -->
	<!-- To make an SEO friendly site, a static page would be generated on the server for each of these -->
	<div style="display: none;" id="links">
		<!-- HREF get's set first and ShowThingsToDo function reads that value, so it knows what #type to use -->
		<a onclick="ShowThingsToDo();" href="#accounting">Accounting</a><br>
		<a onclick="ShowThingsToDo();" href="#airport">Airport</a><br>
		<a onclick="ShowThingsToDo();" href="#amusement_park">Amusement Park</a><br>
		<a onclick="ShowThingsToDo();" href="#aquarium">Aquarium</a><br>
		<a onclick="ShowThingsToDo();" href="#art_gallery">Art Gallery</a><br>
		<a onclick="ShowThingsToDo();" href="#atm">ATM</a><br>
		<a onclick="ShowThingsToDo();" href="#bakery">Bakery</a><br>
		<a onclick="ShowThingsToDo();" href="#bank">Bank</a><br>
		<a onclick="ShowThingsToDo();" href="#bar">Bar</a><br>
		<a onclick="ShowThingsToDo();" href="#beauty_salon">Beauty Salon</a><br>
		<a onclick="ShowThingsToDo();" href="#bicycle_store">Bicycle Store</a><br>
		<a onclick="ShowThingsToDo();" href="#book_store">Book Store</a><br>
		<a onclick="ShowThingsToDo();" href="#bowling_alley">Bowling Alley</a><br>
		<a onclick="ShowThingsToDo();" href="#bus_station">Bus Station</a><br>
		<a onclick="ShowThingsToDo();" href="#cafe">Cafe</a><br>
		<a onclick="ShowThingsToDo();" href="#campground">Campground</a><br>
		<a onclick="ShowThingsToDo();" href="#car_dealer">Car Dealer</a><br>
		<a onclick="ShowThingsToDo();" href="#car_rental">Car Rental</a><br>
		<a onclick="ShowThingsToDo();" href="#car_repair">Car Repair</a><br>
		<a onclick="ShowThingsToDo();" href="#car_wash">Car Wash</a><br>
		<a onclick="ShowThingsToDo();" href="#casino">Casino</a><br>
		<a onclick="ShowThingsToDo();" href="#cemetery">Cemetery</a><br>
		<a onclick="ShowThingsToDo();" href="#church">Church</a><br>
		<a onclick="ShowThingsToDo();" href="#city_hall">City Hall</a><br>
		<a onclick="ShowThingsToDo();" href="#clothing_store">Clothing Store</a><br>
		<a onclick="ShowThingsToDo();" href="#convenience_store">Convenience Store</a><br>
		<a onclick="ShowThingsToDo();" href="#courthouse">Courthouse</a><br>
		<a onclick="ShowThingsToDo();" href="#dentist">Dentist</a><br>
		<a onclick="ShowThingsToDo();" href="#department_store">Department Store</a><br>
		<a onclick="ShowThingsToDo();" href="#doctor">Doctor</a><br>
		<a onclick="ShowThingsToDo();" href="#electrician">Electrician</a><br>
		<a onclick="ShowThingsToDo();" href="#electronics_store">Electronics Store</a><br>
		<a onclick="ShowThingsToDo();" href="#embassy">Embassy</a><br>
		<a onclick="ShowThingsToDo();" href="#establishment">Establishment</a><br>
		<a onclick="ShowThingsToDo();" href="#finance">Finance</a><br>
		<a onclick="ShowThingsToDo();" href="#fire_station">Fire Station</a><br>
		<a onclick="ShowThingsToDo();" href="#florist">Florist</a><br>
		<a onclick="ShowThingsToDo();" href="#food">Food</a><br>
		<a onclick="ShowThingsToDo();" href="#funeral_home">Funeral Home</a><br>
		<a onclick="ShowThingsToDo();" href="#furniture_store">Furniture Store</a><br>
		<a onclick="ShowThingsToDo();" href="#gas_station">Gas Station</a><br>
		<a onclick="ShowThingsToDo();" href="#general_contractor">General Contractor</a><br>
		<a onclick="ShowThingsToDo();" href="#geocode">Geocode</a><br>
		<a onclick="ShowThingsToDo();" href="#grocery_or_supermarket">Grocery or Supermarket</a><br>
		<a onclick="ShowThingsToDo();" href="#gym">Gym</a><br>
		<a onclick="ShowThingsToDo();" href="#hair_care">Hair Care</a><br>
		<a onclick="ShowThingsToDo();" href="#hardware_store">Hardware Store</a><br>
		<a onclick="ShowThingsToDo();" href="#health">Health</a><br>
		<a onclick="ShowThingsToDo();" href="#hindu_temple">Hindu Temple</a><br>
		<a onclick="ShowThingsToDo();" href="#home_goods_store">Home Goods Store</a><br>
		<a onclick="ShowThingsToDo();" href="#hospital">Hospital</a><br>
		<a onclick="ShowThingsToDo();" href="#insurance_agency">Insurance Agency</a><br>
		<a onclick="ShowThingsToDo();" href="#jewelry_store">Jewelry</a><br>
		<a onclick="ShowThingsToDo();" href="#laundry">Laundry</a><br>
		<a onclick="ShowThingsToDo();" href="#lawyer">Lawyer</a><br>
		<a onclick="ShowThingsToDo();" href="#library">Library</a><br>
		<a onclick="ShowThingsToDo();" href="#liquor_store">Liquor Store</a><br>
		<a onclick="ShowThingsToDo();" href="#local_government_office">Local Government Office</a><br>
		<a onclick="ShowThingsToDo();" href="#locksmith">Locksmith</a><br>
		<a onclick="ShowThingsToDo();" href="#lodging">Lodging</a><br>
		<a onclick="ShowThingsToDo();" href="#meal_delivery">Meal Delivery</a><br>
		<a onclick="ShowThingsToDo();" href="#meal_takeaway">Meal Takeaway</a><br>
		<a onclick="ShowThingsToDo();" href="#mosque">Mosque</a><br>
		<a onclick="ShowThingsToDo();" href="#movie_rental">Movie Rental</a><br>
		<a onclick="ShowThingsToDo();" href="#movie_theater">Movie Theater</a><br>
		<a onclick="ShowThingsToDo();" href="#moving_company">Moving Company</a><br>
		<a onclick="ShowThingsToDo();" href="#museum">Museum</a><br>
		<a onclick="ShowThingsToDo();" href="#night_club">Night Club</a><br>
		<a onclick="ShowThingsToDo();" href="#painter">Painter</a><br>
		<a onclick="ShowThingsToDo();" href="#park">Park</a><br>
		<a onclick="ShowThingsToDo();" href="#parking">Parking</a><br>
		<a onclick="ShowThingsToDo();" href="#pet_store">Pet Store</a><br>
		<a onclick="ShowThingsToDo();" href="#pharmacy">Pharmacy</a><br>
		<a onclick="ShowThingsToDo();" href="#physiotherapist">Physiotherapist</a><br>
		<a onclick="ShowThingsToDo();" href="#place_of_worship">Place of Worship</a><br>
		<a onclick="ShowThingsToDo();" href="#plumber">Plumber</a><br>
		<a onclick="ShowThingsToDo();" href="#police">Police</a><br>
		<a onclick="ShowThingsToDo();" href="#post_office">Post Office</a><br>
		<a onclick="ShowThingsToDo();" href="#real_estate_agency">Real Estate Agency</a><br>
		<a onclick="ShowThingsToDo();" href="#restaurant">Restaurant</a><br>
		<a onclick="ShowThingsToDo();" href="#roofing_contractor">Roofing Contractor</a><br>
		<a onclick="ShowThingsToDo();" href="#rv_park">RV Park</a><br>
		<a onclick="ShowThingsToDo();" href="#school">School</a><br>
		<a onclick="ShowThingsToDo();" href="#shoe_store">Shoe Store</a><br>
		<a onclick="ShowThingsToDo();" href="#shopping_mall">Shopping Mall</a><br>
		<a onclick="ShowThingsToDo();" href="#spa">Spa</a><br>
		<a onclick="ShowThingsToDo();" href="#stadium">Stadium</a><br>
		<a onclick="ShowThingsToDo();" href="#storage">Storage</a><br>
		<a onclick="ShowThingsToDo();" href="#store">Store</a><br>
		<a onclick="ShowThingsToDo();" href="#subway_station">Subway Station</a><br>
		<a onclick="ShowThingsToDo();" href="#synagogue">Synagogue</a><br>
		<a onclick="ShowThingsToDo();" href="#taxi_stand">Taxi Stand</a><br>
		<a onclick="ShowThingsToDo();" href="#train_station">Train Station</a><br>
		<a onclick="ShowThingsToDo();" href="#travel_agency">Travel Agency</a><br>
		<a onclick="ShowThingsToDo();" href="#university">University</a><br>
		<a onclick="ShowThingsToDo();" href="#veterinary_care">Veterinary Care</a><br>
		<a onclick="ShowThingsToDo();" href="#zoo">Zoo</a><br>
	</div>

</body>
</html>
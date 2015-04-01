$(document).ready(function() {

	//Go ahead and click a link!!!
   $("a").click(function() {
	 alert("Begin Sync!");

	/**
	 *	Let's make a function that will grab the variables,
	 *	write the variables to the server via AJAX...
	 *
	 *	While we are at the server, 
	 *	let's get variables that other clients put on the server via AJAX
	 *
	 */

		var obj;

		//Sync happens every 5 seconds...
		setInterval( function() {
			 //alert("Syncing!");

			//Send variables via AJAX...

			//If this is a "driver", then send variables to the server...
			//Otherwise, just read the variables from the server...

			//Let's get the drag 'n' drop to work:
			//view-source:http://mrdoob.github.com/three.js/examples/webgl_interactive_cubes.html

			


			$.ajax({
			  type: "POST",
			  url: 'js/ajax/variables.php',
			  data: { _variable: cube.position.x }
			}).done(function( msg ) {
			  //alert( "Data Saved: " + msg );
			  obj = $.parseJSON( msg );

			  //This is the cube in the WebGL canvas...
			  camera.position.x = obj.x;
			  camera.position.y = obj.y;
			  camera.position.z = obj.z;

			});

		}, 1000 ) ;//5 seconds...


   });


 });
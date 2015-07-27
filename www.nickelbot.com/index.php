<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Hello World in Backbone.js</title>
</head>
<body>
  <!-- ========= -->
  <!-- Your HTML -->
  <!-- ========= -->

  <h1>NickelBot - Bitcoin trading bot in the cloud.</h1>
  <p>This is the home of NickelBot. NickelBot does stuff with Bitcoin such as trading and investing.</p>
  <p>Check back sometime to see if there are tools that will be open to the public.</p>
  <p>There will probably be be some Bitcoin bots living here in the cloud sometime soon that will be accessible to everyone.
  <p>Contact <a href="mailto:adam.cox9@gmail.com">Adam.Cox9@gmail.com</a> with questions.</p>

  <div id="container">Loading...</div>

  <!-- ========= -->
  <!-- Libraries -->
  <!-- ========= -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
  
  <!-- =============== -->
  <!-- Javascript code -->
  <!-- =============== -->
  <script type="text/javascript">

	$.ajax({
	  url: "data/index.php",
	  context: document.body
	}).done(function(data) {
	  console.log( JSON.stringify( data ) );
	  $( 'div' ).html( "" );
	  for( i = 0; i < data.length; i++ )
		$( 'div' ).append( "<br>" + data[i] );
	});

  </script>
  
</body>
</html>
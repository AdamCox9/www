<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>About</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
    <meta name="description" content="Your description">
    <meta name="keywords" content="Your keywords">
    <meta name="author" content="Your name">
 	<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen">
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/preview.css" type="text/css">
	<script type="text/javascript" src="js/include_script.js"></script>
  	<!--[if lt IE 9]>
	   	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	    <link href="css/ie.css" rel="stylesheet" type="text/css">
    <![endif]-->
</head>
<body>
<!--content wrapper-->
	<div id="wrapper">
		<section>
			<div class="container">
				<div class="dynamicContent">
					<!--content-->
	                <div class="row">

	                    <div class="span9">
	                    	<img src="img/page1_pic1.jpg" alt=""  class="img1">
	                        <div class="box">
	                        	<h2>welcome</h2>
    	                        <h3>Qualitative professional roofing services</h3>
    	                        <p>Cubilipende sollicitudineied leo pharea aumennec in velit veaugun erase diam lor innia est. Proin dictum elemum velit terdpibus sceleue vitaepeecon eracinia ferment.<br><br>Donec in vel ipsum auctorulvinaroin ullamcorper u et feum iaculis lacinia est. Proin dictum elemenelit. Fusce euismod consequat antrem ipsm dolor sitmet consectetuer adipiscing elit. Pellesque sed dolor. Aliquam congue fermentum nisl.</p>
	                        </div>
	                    </div>

	                    <div class="span3">
	                    	<div class="slider_box">
	                    		<h2 class="v2">what<br>people<br>say</h2>

	                    		<a href="#" class="button1 prev" data-type="prevPage"></a>
								<a href="#" class="button1 next" data-type="nextPage"></a>

								<div class="slider">
									<ul>
										<li>
									    	<p class="slider_txt1"><a href="#">Proin dictum elemum velit terdp ibus sceleue vitaepeecon eracinia fermennec in vel ipsum auctorulvinaroin ullamcor.</a></p>
											<p class="slider_txt2">Jack Farrel</p>
											<p class="slider_txt1">Engineer</p>
									  	</li>
									  	<li>
									    	<p class="slider_txt1"><a href="#">Fusce euismod consequat antrem ipsm dolor sitmet consectetuer adipiscing elit. Pellesque sed dolor. Aliquam congue</a></p>
											<p class="slider_txt2">John Smith</p>
											<p class="slider_txt1">Programmer</p>
									  	</li>
									</ul>
								</div>
	                    	</div>
	                    </div>

	                </div>
				</div>
			</div>
		</section>
	</div>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script>
        // slider 
			 $('.slider')
				.uCarousel({
					show:1
					,shift:0
					,rows:1
					,buttonClass:'button1'			
					,axis:'x'
					,blockAtEnd:true
					,clickable:false
				})
				
			 $('.prev, .next, .btn_icon1, .btn_icon2, .btn_icon3')
				.sprites({
					method:'simple',
					hover:true
				})
			//end slider

    </script>
</body>
</html>
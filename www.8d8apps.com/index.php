<?PHP

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );

	if( sizeof( $_GET ) > 0 ) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://www.8d8apps.com/");
	}

	require 'local.php';
	$MicroAmazonList = microSearchForItems('All','Android',1);

	$template = file_get_contents( 'template.html' );

	$head = "";
	
	$content = <<<HTML

			$MicroAmazonList

			<br><br>

			<p>Hello Android! This website is being born. Visit the <a href="https://market.android.com/developer?pub=8D8+Apps">Apps by 8D8 Apps</a> page on Android Market.</p>

			<p>The first application, which was to test the application building & publishing process, is the <a href="https://market.android.com/details?id=com.adamcox.guesstheletter">Children's Learning Games</a> application. It is a very, very simple application aimed at helping parents to teach their kids.</p>
			
			<p>Another application was just launched! <a href="https://market.android.com/details?id=com.adamcox.rapbeats">Rap Beats</a> is an application with professional rap beats that a person can add their own lyrics to. There is also a professional paid version! <a href="https://market.android.com/details?id=com.adamcox.rapbeatspro">Rap Beats Pro</a> has over 125 beats to rap to.</p>
			
			<p>The latest launched is <a href="https://market.android.com/details?id=com.adamcox.drunktest">Drunk Test</a> which uses a device's orientation sensors to make a circle based on the tilt (roll & pitch) of a device. The user is supposed to try to hold the device as flat & steady as possible and a message will display how well they are doing. The circle will get larger the more they tilt the phone. Another similar app is <a href="https://market.android.com/details?id=com.adamcox.lietest">Lie Test</a>.</p>
			
			<p>Are you bored? Find some <a href="http://www.8d8apps.com/coolthingstodo/">Cool Things to do Nearby</a>! It will find things that you are interested in near your current location.</p>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Home", $template );


	echo $template;

?>

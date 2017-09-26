<?PHP

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

			<p>Visit the <a href="https://market.android.com/developer?pub=8D8+Apps">Apps by 8D8 Apps</a> page on Android Market.</p>

			<p><a href="https://market.android.com/details?id=com.adamcox.guesstheletter">Children's Learning Games</a> is an app aimed at helping parents teach their kids.</p>
			
			<p><a href="https://market.android.com/details?id=com.adamcox.rapbeats">Rap Beats</a> is an app with professional rap beats that a person can add their own lyrics to. There is also a professional paid version! <a href="https://market.android.com/details?id=com.adamcox.rapbeatspro">Rap Beats Pro</a> has over 125 beats to rap to.</p>
			
			<p><a href="https://market.android.com/details?id=com.adamcox.drunktest">Drunk Test</a> is an app that uses a device's orientation sensors to make a circle based on the tilt (roll & pitch) of a device. The user is supposed to try to hold the device as flat & steady as possible and a message will display how well they are doing.</p>
			
			<p><a href="http://www.8d8apps.com/coolthingstodo/">Cool Things to do Nearby</a> is an app that helps find interesting things near your current location.</p>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Home", $template );


	echo $template;

?>

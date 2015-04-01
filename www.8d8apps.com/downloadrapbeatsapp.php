<?PHP

	$template = file_get_contents( 'template.html' );

	$head = "<link rel='canonical' href='http://www.8d8apps.com/credits.php' />";

	require '../php/common.php';
	$MicroAmazonList = microSearchForItems('All','Android',1);

	$content = <<<HTML

	$MicroAmazonList

<p>Download the free Rap Beats app for Android phones to record your lyrics over high quality rap beats. Start making professional rap songs in minutes!</p>

<br>

<div style='float:left;margin:25px;'>
	<br>
	<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeats"><img src="/images/rapbeats.png"></a>
	<br>
	<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeats">Download Android Rap Beats App</a>
	<br><br>
	<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeats"><img src="/images/download.png"></a>
	<br>
</div>
<div style='float:left;margin:25px;'>
	<br>
	<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeatspro"><img src="/images/rapbeats.png"></a>	
	<br>
	<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeatspro">Download Android Rap Beats Pro App</a>
	<br><br>
	<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeatspro"><img src="/images/download.png"></a>	
	<br>
</div>

<br>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Download Rap Beats App", $template );

	echo $template;

?>
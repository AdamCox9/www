<?PHP

	require_once 'local.php';

	$DB = new DatabaseConnection();
	$conn = $DB->getConn();

	/*
		Let's get the correct MP3...
	*/

	if( isset( $_GET['id'] ) ) {
		$id = $_GET['id'];
		
		if( is_numeric( $id ) ) {
			$rap = GetRapById($id);
			if( ! $rap == false ) {
				$filename = $rap['filename'];
				$ogg_filename = str_replace(".mp3", ".ogg", $filename);
				//Update View Count so we know not to delete it:
				UpdateViewCount($id);
			} else {
				$filename = null;
				$ogg_filename = null;
			}
		}

	}

	$MicroAmazonList = microSearchForItems('All','Android',1);

	$template = file_get_contents( 'template.html' );

	$head = "";

	$content = <<<HTML

	$MicroAmazonList

	<br><div><p>This MP3 was created using the Rap Beats app for Android devices. Download the <a href="credits.php">Rap Beats app</a> by clicking the link above and start making professional quality rap beats from your phone for free! Upgrade to the pro version to get 125+ more beats to rap to!</p></div><br>
	<div><a style='font-size:24pt;' href="$filename">Download MP3&gt;&gt;&gt;</a></div>
	<br> <div> <br> <div class="fb-like" data-send="true" data-width="450" data-show-faces="true"></div> <br> <hr> <br> <h3>Media Player:</h3> <br> </div>
	<audio controls="controls">
	  <source src="$ogg_filename" type="audio/ogg">
	  <source src="$filename" type="audio/mpeg">
	Your browser does not support the audio element.
	</audio> 

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Rap MP3 $filename", $template );

	echo $template;

?>

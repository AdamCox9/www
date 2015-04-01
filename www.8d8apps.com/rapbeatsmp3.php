<?PHP

//	ini_set( 'display_errors', 1 );
//	error_reporting( E_ALL );

	require 'library.php';
	require 'generate_library.php';

	set_db_vars();
	$conn = open_db_conn();


/*

<audio controls="controls">
  <source src="horse.ogg" type="audio/ogg">
  <source src="horse.mp3" type="audio/mpeg">
Your browser does not support the audio element.
</audio> 

*/


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


	close_db_conn();

	require 'lib/php/common.php';
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

/*

CREATE TABLE `generatedmp3s` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `filename` VARCHAR(100) UNIQUE,
  `deviceid` INTEGER NOT NULL,
  `ip` VARCHAR(16) NOT NULL,
  `name` VARCHAR(36),
  `time` int(11) default NULL,
  FOREIGN KEY (deviceid) REFERENCES devices(id) ON DELETE CASCADE
) ENGINE=InnoDB;


*/

?>

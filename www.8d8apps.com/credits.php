<?PHP

	require_once 'local.php';

	$DB = new DatabaseConnection();
	$conn = $DB->getConn();

	/*
		Give user a credit for each click through...
	*/

	if( isset( $_GET['ref'] ) ) {
		$ref = $_GET['ref'];
		$ip = GetVisitorIP();
		
		if( is_numeric( $ref ) ) {
			if( CheckValidRef( $ref ) ) {
				//Make sure there is only one click per IP address...
				if( CheckRefForIp( $ref, $ip ) ) {
					//Update the database for ref...
					InsertRef( $ref, $ip );
					//echo "REF OK";
				} else {
					//This IP address already used for Ref for this device...
					//echo "IP ALREADY USED";
				}
				echo file_get_contents( $MDP );
			} else {
				echo file_get_contents( $MDP );
				//echo "DEVICE REF DOES NOT EXIST";
			}
		} else {
			echo file_get_contents( $MDP );
		}

	} else	if( isset( $_GET['getref'] ) ) {

		//Return the number of references for getref
		echo GetRefCountForDevice( $_GET['getref'] );

	} else if( isset( $_GET['uniqueId'] ) ) {
		$uniqueId = $_GET['uniqueId'];

		//Length of the unique id is always 36:
		if( strlen( $uniqueId ) == 36 ) {

			if( ! GetValidRef( $uniqueId ) ) {
				//Need to store this uniqueId to a table if it has not been already...
				echo InsertDevice( $uniqueId );
			}

		} else {
			//Invalid id length:
			echo -1;
		}

	} else {

		$head = "<link rel='canonical' href='http://www.8d8apps.com/credits.php' />";

		$MicroAmazonList = microSearchForItems('All','DJ Equipment',1);
		$YoutubeVideos = GetYoutubeVideos('DJ Equipment');

		$content = <<<HTML

		$MicroAmazonList

			<p>Download the free Rap Beats app for Android phones to record your lyrics over high quality rap beats. Start making professional rap songs in minutes!</p>

			<br>

			<div style='float:left;margin:25px;'>
				<br>
				<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeats"><img src="images/rapbeats.png"></a>
				<br>
				<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeats">Download Android Rap Beats App</a>
				<br><br>
				<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeats"><img src="images/download.png"></a>
				<br>
			</div>
			<div style='float:left;margin:25px;'>
				<br>
				<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeatspro"><img src="images/rapbeats.png"></a>	
				<br>
				<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeatspro">Download Android Rap Beats Pro App</a>
				<br><br>
				<a href="https://play.google.com/store/apps/details?id=com.adamcox.rapbeatspro"><img src="images/download.png"></a>	
				<br>
			</div>

			<div style="clear:both;"/>

			<br>

		$YoutubeVideos

HTML;

		$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
		$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
		$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Download Rap Beats App", $template );

		echo $template;

	}

?>
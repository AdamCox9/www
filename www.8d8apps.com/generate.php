<?PHP

	require_once 'local.php';

	$DB = new DatabaseConnection();
	$conn = $DB->getConn();

	$test = implode( " ", $_POST ) . " ";

	foreach( $_FILES as $_FILE ) {
		$test .= implode( " ", $_FILE ) . " ";
	}

	if( $_FILES['uploadedfile']['error'] == 0 ) {
		$filename = "wavs/".$timestamp."_".$_FILES['uploadedfile']['name'].".3gp";
		$file2 = "mp3s/".str_replace( "r_", "", $_FILES['uploadedfile']['name'] ).".mp3";
		$tmp_name = $_FILES['uploadedfile']['tmp_name'];
		$size = $_FILES['uploadedfile']['size'];
		move_uploaded_file($tmp_name, $filename);

		$new_filename = str_replace( "3gp", "mp3", $filename );

		file_put_contents( "wavs/testing.tes", $test . print_r( $_FILES, 1 ) );

		//Set the rate of uploaded file to 44100:
		$rate = "sox $filename -r 44100 -c 1 $filename";

		//First, convert to pcm:
		$ffmpeg_cmd = "ffmpeg -i $filename -ar 22050 -f wav $filename.pcm";

		//Then, convert to mp3:
		$sox_convert_cmd = "sox $filename.pcm -r 44100 -c 1 $new_filename";

		//Make it two channels:
		$sox_channel_cmd = "sox $new_filename -c 2 $new_filename split";

		//Finally, merge the two mp3's:
		$final_filename = str_replace( "wavs/", "wavs/t_", $new_filename );

		isset( $_GET['offset'] ) ? $pad = $_GET['offset'] / 1000 : $pad = 0.55;

		$sox_merge_cmd = "sox -v 0.4 $file2 -p pad $pad | sox --combine merge - -v 3.0 $new_filename -c 2 $final_filename";

		$ogg_filename = str_replace( ".mp3", ".ogg", $final_filename );

		$sox_ogg = "sox $final_filename $ogg_filename";


		//Execute the commands generated above:
		//echo $ffmpeg_cmd . "; " . $sox_convert_cmd . "; " . $sox_merge_cmd;
		exec( "$ffmpeg_cmd; $sox_convert_cmd; $sox_channel_cmd; $sox_merge_cmd; $sox_ogg " );

		exec( "rm -f $filename; rm -f $filename.pcm; rm -f $new_filename" );

		//Now, we must save it to the database...
		$id = InsertNewRap($final_filename, $_GET['DeviceId']);

		//Let's redirect to rapbeatsmp3.php page with ID...
		echo $id;

	} else {
		echo "UPLOAD FAILURE";
	}

?>
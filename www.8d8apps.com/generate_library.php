<?PHP

/*

	These functions are for generating the MP3 with lyrics and beat...

*/

	function CleanGeneratedMp3($entry)
	{
		global $conn;

		$entry = mysql_real_escape_string($entry,$conn);

		//0 is new, 1 is published, 2 is rejected...
		$Date = time() - 7*24*60*60;
		$query = "SELECT id, time, cnt FROM `generatedmp3s` WHERE `filename` = 'wavs/$entry' AND `cnt` < 5 AND `time` < $Date;";
		echo $query . "\n";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "CleanEntryIfNotValid (select): there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		if ( $mp3 = mysql_fetch_assoc($result) ) {
			print_r( $mp3 );
			echo "\n";
			$query = "DELETE FROM `generatedmp3s` WHERE `id` = {$mp3['id']};";
			$result = mysql_query($query,$conn);
			if ( !( $result ) ) {
				echo "CleanEntryIfNotValid (delete): there was a problem with the Database!.<br/>";
				echo mysql_error();
			}

			//Delete this bad file:
			exec("rm wavs/$entry" );
		}
	}


	function InsertNewRap($Filename, $DeviceId)
	{
		global $conn;

		$Filename = mysql_real_escape_string($Filename,$conn);
		$DeviceId = mysql_real_escape_string($DeviceId,$conn);
		$IP = GetVisitorIP();
		$Date = time();

		$query = "INSERT INTO `generatedmp3s` VALUES(NULL, '$Filename', $DeviceId, '$IP', '', $Date, 0);";

		$result = mysql_query($query,$conn) or die( mysql_error() );

		return mysql_insert_id();

	}

	function UpdateViewCount($id)
	{
		global $conn;

		$id = mysql_real_escape_string($id,$conn);

		$query = "UPDATE `generatedmp3s` SET 
			`cnt` = `cnt` + 1
			WHERE `id` = $id;";

		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "UpdateMP3Entry: there was a problem with the Database!";
			echo mysql_error();
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function GetRapById($id)
	{
		global $conn;

		if( is_numeric( $id ) ) {
			$id = mysql_real_escape_string( $id, $conn );
			$query = "SELECT * FROM `generatedmp3s` WHERE `id` = $id;";
			$result = mysql_query($query,$conn);
			if ( !( $result ) ) {
				echo "GetRapById: there was a problem with the Database!.<br/>";
				echo mysql_error();
			}

			if ( mysql_num_rows($result) > 0 ) {
				if( $row = mysql_fetch_assoc( $result ) ) {
					return $row;
				}
				return false;
			}
		}
	}

?>

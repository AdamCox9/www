<?PHP

	function CleanEntryIfNotValid($entry)
	{
		global $conn;

		$entry = mysqli_real_escape_string($conn, $entry);

		//0 is new, 1 is published, 2 is rejected...
		$query = "SELECT id FROM `mp3s` WHERE `filename` = '$entry' AND `verified` > 1;";
		$result = mysqli_query($conn,$query);
		if ( !( $result ) ) {
			error_log( "CleanEntryIfNotValid (select): there was a problem with the Database!." );
			echo mysqli_error($conn);
		}

		$mp3s = Array();
		if ( $mp3 = mysqli_fetch_assoc($result) ) {
			$query = "DELETE FROM `mp3s` WHERE `id` = {$mp3['id']};";
			$result = mysqli_query($conn,$query);
			if ( !( $result ) ) {
				error_log( "CleanEntryIfNotValid (delete): there was a problem with the Database!." );
				echo mysqli_error($conn);
			}

			//Delete this bad file:
			unlink( 'files/' . $entry );
		}
	}

	/*
		Generic function...
	*/
	function GetVisitorIP()
    {
		if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$TheIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else  {
			$TheIp = $_SERVER['REMOTE_ADDR'];
		}

		return trim($TheIp);
    }

	/*
		This is for beats that producers want to publish...
		It is not for generated songs with lyrics and beats!!!
	*/
	function SaveMP3()
	{
		if( isset( $_FILES['mp3']['tmp_name'] ) && isset( $_FILES['mp3']['tmp_name'] ) ) {
			move_uploaded_file( $_FILES['mp3']['tmp_name'], "../files/".$_FILES['mp3']['name']) or die("MOVE ERROR");
		} else {
			die( "MP3 ERROR" );
		}
	}

	function SaveMP3Data()
	{
		global $conn;

		$Date = time();
		$Filename = $Date . '__|__' . mysqli_real_escape_string($conn, $_FILES['mp3']['name']);
		$Title = mysqli_real_escape_string($conn, $_POST['title']);
		$Author = mysqli_real_escape_string($conn, $_POST['author']);
		$Website = mysqli_real_escape_string($conn, $_POST['website']);
		$Email = mysqli_real_escape_string($conn, $_POST['email']);
		$Verified = 0;

		$query = "INSERT INTO `mp3s` VALUES(NULL, '$Filename', '$Title', '$Author', '$Website', '$Email', '$Verified', $Date);";

		$result = mysqli_query($conn,$query) or die( mysqli_error($conn) );
	}

	/*
		verified=0 - user submitted
		verified=1 - published to phones
		verified=2 - denied access
	*/

	function GetAllVerifiedMP3Data()
	{
		global $conn;

		//0 is new, 1 is published, 2 is rejected...
		$query = "SELECT * FROM `mp3s` WHERE `verified` = 1 ORDER BY `title`;";
		$result = mysqli_query($conn,$query);
		if ( !( $result ) ) {
			error_log( "GetAllVerifiedMP3Data: there was a problem with the Database!." );
			echo mysqli_error($conn);
		}

		$mp3s = Array();
		while ( $mp3 = mysqli_fetch_assoc($result) ) {
			$mp3s[] = $mp3;
		}
		return $mp3s;
	}

	//_____THIS IS BY ACTUAL DEVICE ID!

	function GetRefCountForDevice( $getref )
	{
		global $conn;

		$getref = mysqli_real_escape_string( $conn, $getref );

		$query = "SELECT `id` FROM `refs` WHERE `deviceid` = '$getref';";
		$result = mysqli_query($conn,$query);
		if ( !( $result ) ) {
			error_log( "GetRefCountForDevice: there was a problem with the Database!." );
			echo mysqli_error($conn);
		}

		return mysqli_num_rows($result);
	}

	//_____The id exists in the table...
	function CheckValidRef( $ref )
	{
		global $conn;

		$ref = mysqli_real_escape_string( $conn, $ref );
		$query = "SELECT `id` FROM `devices` WHERE `id` = $ref;";
		$result = mysqli_query($conn,$query);
		if ( !( $result ) ) {
			error_log( "CheckValidRef: there was a problem with the Database!." );
			echo mysqli_error($conn);
		}

		if ( mysqli_num_rows($result) > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	function GetValidRef( $ref )
	{
		global $conn;

		$ref = mysqli_real_escape_string( $conn, $ref );
		$query = "SELECT `id` FROM `devices` WHERE `key` = '$ref';";
		$result = mysqli_query($conn,$query);
		if ( !( $result ) ) {
			error_log( "GetValidRef: there was a problem with the Database!." );
			echo mysqli_error($conn);
		}

		if ( mysqli_num_rows($result) > 0 ) {
			if( $row = mysqli_fetch_assoc( $result ) ) {
				echo $row['id'];
			}
			return true;
		} else {
			return false;
		}
	}


	/*
	 *	$ref is id from table...
	 */
	function CheckRefForIp( $ref, $ip )
	{
		global $conn;

		$ref = mysqli_real_escape_string( $conn, $ref );
		$ip = mysqli_real_escape_string( $conn, $ip );

		$query = "SELECT `id` FROM `refs` WHERE `deviceid` = '$ref' AND `ip` = '$ip';";
		$result = mysqli_query($conn,$query);
		if ( !( $result ) ) {
			error_log( "CheckRefForIp: there was a problem with the Database!." );
			echo mysqli_error($conn);
		}

		if ( mysqli_num_rows($result) == 0 ) {
			return true;
		}
		return false;
	}

	function InsertRef( $ref, $ip )
	{
		global $conn;

		$ref = mysqli_real_escape_string( $conn, $ref );
		$ip = mysqli_real_escape_string( $conn, $ip );
		$time = time();

		$query = "INSERT INTO `refs` VALUES(NULL, '$ref', '$ip', $time);";

		$result = mysqli_query($conn,$query) or die( mysqli_error($conn) );
	}

	//_____duplicates return string -1 which java reads as integer...

	function InsertDevice( $registerid )
	{
		global $conn;

		$registerid = mysqli_real_escape_string( $conn, $registerid );
		$time = time();

		$query = "INSERT INTO `devices` VALUES(NULL, '$registerid', $time);";

		$result = mysqli_query($conn,$query) or die( "-1" );

		return mysqli_insert_id( $conn );
	}

	function GetAllMP3Data()
	{
		global $conn;

		$query = "SELECT * FROM `mp3s` WHERE `verified` = 0 OR `verified` = 1;"; //verified=2 means it was checked and not good...
		$result = mysqli_query($conn,$query);
		if ( !( $result ) ) {
			error_log( "GetAllVerifiedMP3Data: there was a problem with the Database!." );
			echo mysqli_error($conn);
		}

		$mp3s = Array();
		while ( $mp3 = mysqli_fetch_assoc($result) ) {
			$mp3s[] = $mp3;
		}
		return $mp3s;
	}

	/*
		Update a single MP3 Entry by passed in ID...
	*/

	function UpdateMP3Entry()
	{
		global $conn;

		$id = mysqli_real_escape_string($conn, $_GET['id']);
		$filename = mysqli_real_escape_string($conn, $_GET['filename']);
		$title = mysqli_real_escape_string($conn, $_GET['title']);
		$author = mysqli_real_escape_string($conn, $_GET['author']);
		$website = mysqli_real_escape_string($conn, $_GET['website']);
		$email = mysqli_real_escape_string($conn, $_GET['email']);
		$verified = mysqli_real_escape_string($conn, $_GET['verified']);

		$query = "UPDATE `mp3s` SET 
			`filename` = '$filename',
			`title` = '$title',
			`author` = '$author',
			`website` = '$website',
			`email` = '$email',
			`verified` = '$verified'
			WHERE `id` = '$id';";

		$result = mysqli_query($conn,$query);
		if ( !( $result ) ) {
			error_log( "UpdateMP3Entry: there was a problem with the Database!" );
			echo mysqli_error($conn);
			return FALSE;
		} else {
			return TRUE;
		}
	}

?>
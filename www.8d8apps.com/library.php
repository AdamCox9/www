<?PHP

	function CleanEntryIfNotValid($entry)
	{
		global $conn;

		$entry = mysql_real_escape_string($entry,$conn);

		//0 is new, 1 is published, 2 is rejected...
		$query = "SELECT id FROM `mp3s` WHERE `filename` = '$entry' AND `verified` > 1;";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "CleanEntryIfNotValid (select): there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		$mp3s = Array();
		if ( $mp3 = mysql_fetch_assoc($result) ) {
			echo "Bad File: " . $mp3['id'];
			$query = "DELETE FROM `mp3s` WHERE `id` = {$mp3['id']};";
			$result = mysql_query($query,$conn);
			if ( !( $result ) ) {
				echo "CleanEntryIfNotValid (delete): there was a problem with the Database!.<br/>";
				echo mysql_error();
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
		$Filename = $Date . '__|__' . mysql_real_escape_string($_FILES['mp3']['name'],$conn);
		$Title = mysql_real_escape_string($_POST['title'],$conn);
		$Author = mysql_real_escape_string($_POST['author'],$conn);
		$Website = mysql_real_escape_string($_POST['website'],$conn);
		$Email = mysql_real_escape_string($_POST['email'],$conn);
		$Verified = 0;

		$query = "INSERT INTO `mp3s` VALUES(NULL, '$Filename', '$Title', '$Author', '$Website', '$Email', '$Verified', $Date);";

		$result = mysql_query($query,$conn) or die( mysql_error() );
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
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "GetAllVerifiedMP3Data: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		$mp3s = Array();
		while ( $mp3 = mysql_fetch_assoc($result) ) {
			$mp3s[] = $mp3;
		}
		return $mp3s;
	}

	//_____THIS IS BY ACTUAL DEVICE ID!

	function GetRefCountForDevice( $getref )
	{
		global $conn;

		$getref = mysql_real_escape_string( $getref, $conn );

		$query = "SELECT `id` FROM `refs` WHERE `deviceid` = '$getref';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "GetRefCountForDevice: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		return mysql_num_rows($result);
	}

	//_____The id exists in the table...
	function CheckValidRef( $ref )
	{
		global $conn;

		$ref = mysql_real_escape_string( $ref, $conn );
		$query = "SELECT `id` FROM `devices` WHERE `id` = $ref;";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "CheckValidRef: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		if ( mysql_num_rows($result) > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	function GetValidRef( $ref )
	{
		global $conn;

		$ref = mysql_real_escape_string( $ref, $conn );
		$query = "SELECT `id` FROM `devices` WHERE `key` = '$ref';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "GetValidRef: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		if ( mysql_num_rows($result) > 0 ) {
			if( $row = mysql_fetch_assoc( $result ) ) {
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

		$ref = mysql_real_escape_string( $ref, $conn );
		$ip = mysql_real_escape_string( $ip, $conn );

		$query = "SELECT `id` FROM `refs` WHERE `deviceid` = '$ref' AND `ip` = '$ip';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "CheckRefForIp: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		if ( mysql_num_rows($result) == 0 ) {
			return true;
		}
		return false;
	}

	function InsertRef( $ref, $ip )
	{
		global $conn;

		$ref = mysql_real_escape_string( $ref, $conn );
		$ip = mysql_real_escape_string( $ip, $conn );
		$time = time();

		$query = "INSERT INTO `refs` VALUES(NULL, '$ref', '$ip', $time);";

		$result = mysql_query($query,$conn) or die( mysql_error() );
	}

	//_____duplicates return string -1 which java reads as integer...

	function InsertDevice( $registerid )
	{
		global $conn;

		$registerid = mysql_real_escape_string( $registerid, $conn );
		$time = time();

		$query = "INSERT INTO `devices` VALUES(NULL, '$registerid', $time);";

		$result = mysql_query($query,$conn) or die( "-1" );

		return mysql_insert_id( $conn );
	}

	function GetAllMP3Data()
	{
		global $conn;

		$query = "SELECT * FROM `mp3s` WHERE `verified` = 0 OR `verified` = 1;"; //verified=2 means it was checked and not good...
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "GetAllVerifiedMP3Data: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		$mp3s = Array();
		while ( $mp3 = mysql_fetch_assoc($result) ) {
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

		$id = mysql_real_escape_string($_GET['id'],$conn);
		$filename = mysql_real_escape_string($_GET['filename'],$conn);
		$title = mysql_real_escape_string($_GET['title'],$conn);
		$author = mysql_real_escape_string($_GET['author'],$conn);
		$website = mysql_real_escape_string($_GET['website'],$conn);
		$email = mysql_real_escape_string($_GET['email'],$conn);
		$verified = mysql_real_escape_string($_GET['verified'],$conn);

		$query = "UPDATE `mp3s` SET 
			`filename` = '$filename',
			`title` = '$title',
			`author` = '$author',
			`website` = '$website',
			`email` = '$email',
			`verified` = '$verified'
			WHERE `id` = '$id';";

		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "UpdateMP3Entry: there was a problem with the Database!";
			echo mysql_error();
			return FALSE;
		} else {
			return TRUE;
		}
	}


	function set_db_vars()
	{
		$GLOBALS['db_ip'] = 'localhost';
		$GLOBALS['db_user'] = 'root';
		$GLOBALS['db_pass'] = '123233abc';
		$GLOBALS['db_name'] = '8d8apps';
	}

	function open_db_conn()
	{
		$conn = mysql_connect($GLOBALS['db_ip'], $GLOBALS['db_user'], $GLOBALS['db_pass']);
		mysql_select_db($GLOBALS['db_name'], $conn);
		return $conn;
	}

	function close_db_conn()
	{
		global $conn;
		mysql_close($conn);
	}

?>
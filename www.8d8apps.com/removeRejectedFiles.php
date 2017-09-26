<?PHP

die( "careful" );

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );

	require( 'local.php' );

	$conn = open_db_conn();

	//List all files in 'files' directory:

	if ($handle = opendir('files')) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				//echo "$entry<br>\n";
				//See if it is a verified file in the database and delete if it is not:
				CleanEntryIfNotValid($entry);
			}
		}
		closedir($handle);
	}

	close_db_conn();

	echo "OK";

?>
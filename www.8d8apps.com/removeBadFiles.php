<?PHP

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );

	require 'library.php';
	require( 'generate_library.php' );

	set_db_vars();
	$conn = open_db_conn();

	//List all files in 'files' directory:

	if ($handle = opendir('wavs')) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				CleanGeneratedMp3($entry);
			}
		}
		closedir($handle);
	}

	close_db_conn();

	echo "OK";

?>
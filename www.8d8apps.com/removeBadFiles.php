<?PHP

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );

	require_once 'generate_library.php';
	require 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	//List all files in 'files' directory:

	if ($handle = opendir('wavs')) {
		while (false !== ($entry = readdir($handle)))
			if ($entry != "." && $entry != "..")
				CleanGeneratedMp3($entry);
		closedir($handle);
	}


	echo "OK";

?>
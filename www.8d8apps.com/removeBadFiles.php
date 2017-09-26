<?PHP

	die('safety');

	require 'local.php';

	$conn = open_db_conn();

	if ($handle = opendir('wavs')) {
		while (false !== ($entry = readdir($handle)))
			if ($entry != "." && $entry != "..")
				CleanGeneratedMp3($entry);
		closedir($handle);
	}


	echo "OK";

?>
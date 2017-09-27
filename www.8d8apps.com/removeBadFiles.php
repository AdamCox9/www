<?PHP

	die('safety');

	require_once 'local.php';

	$DB = new DatabaseConnection();
	$conn = $DB->getConn();

	if ($handle = opendir('wavs')) {
		while (false !== ($entry = readdir($handle)))
			if ($entry != "." && $entry != "..")
				CleanGeneratedMp3($entry);
		closedir($handle);
	}


	echo "OK";

?>
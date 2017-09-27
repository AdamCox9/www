<?PHP

die( "careful" );

	require_once 'local.php';

	$DB = new DatabaseConnection();
	$conn = $DB->getConn();

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

	echo "OK";

?>
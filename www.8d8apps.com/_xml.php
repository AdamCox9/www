<?PHP

	require_once 'local.php';

	$DB = new DatabaseConnection();
	$conn = $DB->getConn();

	$mp3s = GetAllVerifiedMP3Data();

	$SEPARATOR = "ADAMCOXROCKS";

	foreach( $mp3s as $mp3 ) {
		echo $mp3['filename'] . $SEPARATOR . $mp3['title'] . $SEPARATOR . $mp3['author'] . $SEPARATOR . $mp3['website'] . "\n";
	}

?>

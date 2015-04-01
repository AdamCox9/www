<?PHP

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );


	require 'library.php';

	set_db_vars();
	$conn = open_db_conn();

	$mp3s = GetAllVerifiedMP3Data();

	$SEPARATOR = "ADAMCOXROCKS";

	foreach( $mp3s as $mp3 ) {
		echo $mp3['filename'] . $SEPARATOR . $mp3['title'] . $SEPARATOR . $mp3['author'] . $SEPARATOR . $mp3['website'] . "\n";
	}

	close_db_conn();

?>

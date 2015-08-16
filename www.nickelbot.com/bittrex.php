<?PHP

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
		
?>
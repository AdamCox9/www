<?PHP

	if( isset( $_GET['search'] ) ) {
		$search = ucwords( strtolower( $_GET['search'] ) );
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://randomthoughts.club/aws/storeFront.php?pagenum=1&category=All&displayCat=$search");
		exit;
	}

	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://randomthoughts.club/");

	exit;

?>
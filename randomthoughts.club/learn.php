<?PHP

	if( isset( $_GET['item'] ) ) {
		$item = ucwords( strtolower( $_GET['item'] ) );
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://randomthoughts.club/aws/storeFront.php?pagenum=1&category=All&displayCat=$item");
		exit;
	}

	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://randomthoughts.club/");

?>
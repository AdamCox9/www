<?PHP

	if( isset( $_GET['item'] ) ) {
		$item = $_GET['item'];
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://randomthoughts.club/aws/itemPage.php?item=$item");
		exit;
	}


	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://randomthoughts.club/");

	exit;

?>
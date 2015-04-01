<?PHP

	if( isset( $_GET['item'] ) ) {
		$item = $_GET['item'];
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://www.8d8apps.com/aws/itemPage.php?item=$item");
		exit;
	}


	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://www.8d8apps.com/");

	exit;

?>
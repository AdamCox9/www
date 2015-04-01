<?PHP

	if( isset( $_GET['lim'] ) ) {
		$lim = $_GET['lim'];
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://randomthoughts.club/page.php?lim=$lim");
		exit;
	}

	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://randomthoughts.club/");

	exit;

?>
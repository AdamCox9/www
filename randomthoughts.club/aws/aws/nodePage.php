<?PHP

	if( isset( $_GET['node'] ) ) {
		$node = $_GET['node'];
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://randomthoughts.club/aws/nodePage.php?node=$node");
		exit;
	}


	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://randomthoughts.club/");

	exit;

?>
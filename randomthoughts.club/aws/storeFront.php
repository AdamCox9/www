<?PHP

	$pagenum = isset( $_GET['pagenum'] ) ? $_GET['pagenum'] : 1;
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://www.8d8apps.com/aws/storeFront.php?pagenum=$pagenum&category={$_GET['category']}&displayCat={$_GET['displayCat']}");

?>
<?PHP

	$pagenum = isset( $_GET['pagenum'] ) ? $_GET['pagenum'] : 1;
	$category = isset( $_GET['category'] ) ? $_GET['category'] : '';
	$displayCat = isset( $_GET['displayCat'] ) ? $_GET['displayCat'] : '';

	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://www.8d8apps.com/storeFront.php?pagenum=$pagenum&category=$category&displayCat=$displayCat");

	exit;

?>
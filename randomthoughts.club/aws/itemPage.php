<?PHP

	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://www.8d8apps.com/aws/itemPage.php?item={$_GET['item']}");

?>
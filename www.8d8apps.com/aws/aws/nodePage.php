<?PHP

	header("HTTP/1.1 301 Moved Permanently");
 	header("Location: http://www.8d8apps.com/nodePage.php?node={$_GET['node']}");

	exit;

?>
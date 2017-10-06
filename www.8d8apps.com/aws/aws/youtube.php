<?PHP
 
 	if( isset( $_GET['video'] ) ) {
 		$video = $_GET['video'];
 		header("HTTP/1.1 301 Moved Permanently"); 
 		header("Location: http://www.8d8apps.com/youtube.php?video=$video");
 		exit;
 	}
 
 	if( isset( $_GET['q'] ) ) {
 		$q = ucwords( strtolower( $_GET['q'] ) );
 		header("HTTP/1.1 301 Moved Permanently"); 
 		header("Location: http://www.8d8apps.com/storeFront.php?pagenum=1&category=All&displayCat=$q");
 		exit;
 	}
 
 	header("HTTP/1.1 301 Moved Permanently"); 
 	header("Location: http://http://www.8d8apps.com/");
 
 	exit;

?>
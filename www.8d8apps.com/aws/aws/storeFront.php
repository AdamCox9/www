<?PHP

	if( isset( $_GET['displayCat'] ) ) {
		$displayCat = $_GET['displayCat'];

		if( isset( $_GET['category'] ) )
			$category = $_GET['category'];
		else
			$category = 'All';

		if( isset( $_GET['pagenum'] ) )
			$pagenum = $_GET['pagenum'];
		else
			$pagenum = 1;

		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://www.8d8apps.com/aws/storeFront.php?pagenum=$pagenum&category=$category&displayCat=$displayCat");
		exit;
	}


	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://www.8d8apps.com/aws/storeFront.php");

	exit;

?>
<?PHP

function getCategorizedThoughts($category)
{
	global $db;
	$category = $db->getConn()->real_escape_string($category);
	$arr = Array();
	$query = "SELECT `id`, `quote`, `date` FROM `quotes` WHERE `category` = '$category' ORDER BY `date` DESC LIMIT 0, 45;";
	$result = mysqli_query($db->getConn(),$query);
	if ( ! $result  )
		error_log( "thought_cateogry_lib.php:getCategorizedThoughts", 0 );
	else
		while ( $row = mysqli_fetch_array($result) )
			array_push($arr, $row);
	return $arr;
}

?>
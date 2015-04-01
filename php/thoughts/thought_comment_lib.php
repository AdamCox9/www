<?PHP

function getComments($id)
{
	global $db;

	$id = $db->getConn()->real_escape_string($id);
	$arr = Array();
	$query = "SELECT `id`, `comment`, `date`, `user` FROM `comments` WHERE `qid` = '$id' ORDER by `date`;";
	$result = mysqli_query($db->getConn(),$query);
	if ( ! $result )
		error_log( "thought_lib.php:getComments()" . mysql_error() );
	else
		while ( $row = mysqli_fetch_array($result) )
			array_push($arr, $row);
	return $arr;
}

function getComment($lim)
{
	global $db;

	$lim = mysql_real_escape_string( $lim, $conn );
	$query = "SELECT `qid`, `comment`, `date` FROM `comments` WHERE `id` = '$lim';";
	$result = mysqli_query($db->getConn(),$query);
	if ( $result )
		if ( $row = mysql_fetch_row($result) )
			return $row;
}

function cntComments($id)
{
	global $db;
	$id = mysql_real_escape_string( $id, $conn );

	$query = "SELECT count(*) FROM `comments` WHERE `qid` = '$id';";
	$result = mysql_query($query,$conn);
	if ( $result && $row = mysql_fetch_row( $result ) )
		return $row[0];

	return 0;
}

function getRecentComments($x=0)
{
	global $db;

	$x = $db->getConn()->real_escape_string($x);
	$arr = Array();
	$query = "SELECT `qid`, `comment`, `date` FROM `comments` ORDER BY `date` DESC LIMIT $x, 45;";
	$result = mysqli_query($db->getConn(),$query);
	if ( ! $result )
		error_log( "thought_comment_lib.php:getComments()" . mysql_error() );
	else
		while ( $row = mysqli_fetch_array($result) )
			array_push($arr, $row);
	return $arr;
}

function addComment($lim,$comment)
{
	global $db;
	global $user;

	$comment = html2txt($_POST['comment']);

	$comment = $db->getConn()->real_escape_string( $comment );
	$user = $db->getConn()->real_escape_string( $user );
	$lim = $db->getConn()->real_escape_string( $lim );
	$ip = $db->getConn()->real_escape_string( $_SERVER['REMOTE_ADDR'] );
	$date = time();

	$query = "SELECT `id` FROM `comments` WHERE `comment` = '$comment' AND `qid` = '$lim';";
	$result = mysqli_query($db->getConn(),$query);
	if( ! $result )
		error_log( "thought_comment_lib.php:getComments()" . mysql_error() );
	else
		if ( ! ( $row = mysqli_fetch_array( $result ) ) ) {
			$query = "INSERT INTO `comments` VALUES(NULL,$lim,'$comment','$ip','$date','$user');";
			$result = mysqli_query($db->getConn(),$query);
			if ( ! $result )
				error_log( "thought_comment_lib.php:getComments()" . mysql_error() );
			else
				return true;
		}

	return false;
}

?>
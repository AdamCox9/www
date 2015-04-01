<?PHP

	function getCommentCount()
	{
		global $db;

		$query = "SELECT count(*) as num FROM `comments`;";
		$result = mysqli_query($db->getConn(),$query);
		if ( ! $result  )
			error_log( "thought_lib.php:getCommentCount" . mysql_error(), 0 );
		else if ( $row = mysqli_fetch_array($result) )
			return stripslashes($row['num']);
		
		return 4687;
	}

	function getThoughtCount()
	{
		global $db;

		$query = "SELECT count(*) as num FROM `quotes`;";
		$result = mysqli_query($db->getConn(),$query);

		if ( ! $result  )
			error_log( "thought_lib.php:getThoughtCount" . mysql_error(), 0 );
		else if ( $row = mysqli_fetch_array($result) )
			return stripslashes($row['num']);
		
		return 16513;
	}

	function getThought($id)
	{
		global $db;
		
		$id = $db->getConn()->real_escape_string($id);

		$query = "SELECT * FROM `quotes` WHERE `id` = '$id';";
		$result = mysqli_query($db->getConn(),$query);

		if ( ! $result  )
			error_log( "DB Could Not Get Thought in thought_lib.php", 0 );
		else if ( $row = mysqli_fetch_array($result) )
			return stripslashes($row['quote']);

		return "I am a placeholder for places yet explored";
	}

	function getRandomId()
	{
		global $db;

		$query = "SELECT `id` FROM `quotes` ORDER BY RAND() LIMIT 0, 1;";
		$result = mysqli_query($db->getConn(),$query);
		
		if ( ! $result )
			error_log( "thought_lib.php:getRandomId()" . mysql_error(), 0 );
		else if ( $row = mysqli_fetch_array($result) )
			return $row['id'];

		return 1;
	}

	function getPageQuotes($lim)
	{
		global $db;
		$arr = Array();
		$lim = mysql_real_escape_string( $lim, $conn );

		$query = "SELECT quote, id, user,
					( SELECT MAX(id) FROM quotes WHERE id < t.id ) AS prev, 
					( SELECT MIN(id) FROM quotes WHERE id > t.id ) AS next,
					date, ip, category
				FROM quotes AS t WHERE id = '$lim' ORDER BY id;";

		$result = mysql_query($query,$conn);
		if ( ! $result )
			error_log( "thought_lib.php:getPageQuotes", 0 );
		else while ( $row = mysql_fetch_row($result) )
				array_push( $arr, $row );

		return $arr;
	}


	function getRecent($x=0)
	{
		global $db;

		$x = $db->getConn()->real_escape_string($x);
		$arr = Array();

		$query = "SELECT `id`, `quote`, `date` FROM `quotes` ORDER BY `date` DESC LIMIT $x, 45;";
		$result = mysqli_query($db->getConn(),$query);

		if ( ! $result  )
			error_log( "thought_lib.php:getRecent($lim)" . mysql_error(), 0 );
		else while ( $row = mysqli_fetch_array($result) )
			array_push($arr, $row);
		return $arr;
	}

	function setRate()
	{
		global $conn;

		if ( isset( $_GET['value'] ) && isset( $_GET['id'] ) ) {
			$value = mysql_real_escape_string($_GET['value'],$conn);
			$id = mysql_real_escape_string($_GET['id'],$conn);
			$time = time();

			$query = "SELECT `value`, `cnt` FROM `ratings` WHERE `q_id` = '$id';";
			$result = mysql_query($query,$conn);
			if ( !( $result ) ) {
				echo "setRate: (select) there was a problem with the Database!.<br/>";
				echo mysql_error();
				return FALSE;
			} else {

				if ( $row = mysql_fetch_row($result) ) {
					$cnt = $row[1];
					$oldValue = $row[0];

					$SumOldValues = $cnt*$oldValue;
					$newValue = ($SumOldValues+$value)/($cnt+1);

					$newValue = mysql_real_escape_string($newValue,$conn);

					$query = "UPDATE `ratings` SET `value` = $newValue, `cnt` = `cnt` + 1 WHERE `q_id` = '$id';";
					$result = mysql_query($query,$conn);
					if ( !( $result ) ) {
						echo "setRate: (update) there was a problem with the Database!";
						echo mysql_error();
						return FALSE;
					}
				} else {
					$date = time();
					$query = "INSERT INTO `ratings` VALUES(NULL, '$value', 1, '$id', $date);";

					$result = mysql_query($query,$conn);
					if ( !( $result ) ) {
						echo "setRate: (insert) there was a problem with the Database!";
						echo mysql_error();
						return FALSE;
					} else {
						return TRUE;
					}
				}
			}
		}
	}

	function getRate($lim=NULL)
	{
		global $conn;

		if ( isset( $_GET['lim'] ) || $lim != NULL ) {
			if( $lim != NULL ) {
				$lim = mysql_real_escape_string($lim,$conn);
			} else {
				$lim = mysql_real_escape_string($_GET['lim'],$conn);
			}
			$query = "SELECT `value`, `cnt` FROM `ratings` WHERE `q_id` = '$lim';";
			$result = mysql_query($query,$conn);
			if ( !( $result ) ) {
				echo "getRate: there was a problem with the Database!.<br/>";
				echo mysql_error();
			}

			if ( $row = mysql_fetch_row($result) ) {
				return $row;
			} else {
				return Array(0=>0,1=>0);
			}
		}
	}

	function getRatedPosts()
	{
		global $db;

		if( isset( $_GET['page'] ) ) {
			if( $_GET['page'] == 'value' ) {
				$order = 'value';
				$order2 = 'cnt';
				$order3 = 'date';
			} else if( $_GET['page'] == 'cnt' ) {
				$order = 'cnt';
				$order2 = 'value';
				$order3 = 'date';
			} else if( $_GET['page'] == 'date' ) {
				$order = 'date';
				$order2 = 'value';
				$order3 = 'cnt';
			}
		} else {
			$order = 'value';
			$order2 = 'cnt';
			$order3 = 'date';
		}

		if( isset( $_GET['limit'] ) && $_GET['limit'] != NULL ) {
			$limit = $db->getConn()->real_escape_string($_GET['limit']);
		} else {
			$limit = 0;
		}
		
		if( is_numeric( $limit ) ) {
			$query = "SELECT `q_id`, `value`, `cnt` AS `num`, ( SELECT `quote` FROM `quotes` WHERE `id` = t.q_id ) AS q FROM `ratings` AS `t` GROUP BY `q_id` ORDER BY `$order` DESC,`$order2` DESC, `$order3` DESC LIMIT $limit, 45;";
		} else {
			die( "Bastard!" );
		}
		
		$result = mysqli_query($db->getConn(),$query);

		$arr = Array();
		if ( ! $result  )
			error_log( "thought_lib.php:getRatedPosts()" . mysql_error(), 0 );
		else while ( $row = mysqli_fetch_array($result) )
			array_push($arr, $row);
		return $arr;
	}

	function getPopularThoughts($b=0)
	{
		global $db;
		$b = $db->getConn()->real_escape_string($b);
		if( is_numeric( $b ) ) {
			$query = "SELECT `qid` AS id, COUNT(*) AS `num`, ( SELECT `quote` FROM `quotes` WHERE `id` = t.qid ) AS q FROM `comments` AS `t` GROUP BY `qid` ORDER BY `num` DESC LIMIT $b, 45;";
		} else {
			die( "Bastard" );
		}
		$result = mysqli_query($db->getConn(),$query);
		$arr = Array();
		if ( ! $result  )
			error_log( "thought_lib.php:getPopularThoughts()" . mysql_error(), 0 );
		else while ( $row = mysqli_fetch_array($result) )
			array_push($arr, $row);
		return $arr;
	}

	function setQuote($quote,$category)
	{
		global $db;
		global $user;

		$quote = $db->getConn()->real_escape_string($quote);
		$ip = $db->getConn()->real_escape_string($_SERVER['REMOTE_ADDR']);
		$user = $db->getConn()->real_escape_string($user);
		$category = $db->getConn()->real_escape_string($category);
		$date = time();

		$query = "SELECT `id` FROM `quotes` WHERE `quote` = '$quote';";
		$result = mysqli_query($db->getConn(),$query);
		if ( ! $result  )
			error_log( "thought_lib.php:getCommentCount" . mysql_error(), 0 );
		else
			if ( ! ( $row = mysqli_fetch_array( $result ) ) ) {
				$query = "INSERT INTO `quotes` VALUES(NULL,'$quote','$ip','$date','$user','$category');";
				$result = mysqli_query($db->getConn(),$query);

				if ( ! $result )
					error_log( "thought_lib.php:getCommentCount" . mysql_error(), 0 );
				else
					return mysqli_insert_id($db->getConn());
			} else
				return $row['id']; //duplicate thought...
	}

	/*function updateQuote()
	{
		global $conn;
		$id = mysql_real_escape_string( $_GET['id'], $conn );
		$quote = mysql_real_escape_string( $_POST['quote'], $conn );
		$category = mysql_real_escape_string( $_POST['category'], $conn );

		if( isset( $_POST['hidden'] ) && isset( $_GET['id'] ) ) {
			$query = "UPDATE `quotes` SET 
				`quote` = '$quote',
				`category` = '$category'
				WHERE `id` = '$id';";

			$result = mysql_query($query,$conn);
			if ( !( $result ) ) {
				echo "getSetQutoe: there was a problem with the Database!";
				echo mysql_error();
			}
		}

	}*/

	/*****

		Search related functions

	 *****/

	function search()
	{
		global $conn;
		global $search;
		$search = mysql_real_escape_string( trim($search), $conn );

		$query = "SELECT `quote`, `id` FROM `quotes` WHERE UPPER(`quote`) LIKE UPPER(\"%$search%\") LIMIT 0, 45";
		$result = mysql_query($query,$conn);
		$arr = Array();
		if ( $result ) {
			while ( $row = mysql_fetch_row($result) ) {
				array_push( $arr, $row );
			}
		}
		$result = NULL;

		$query = "SELECT `comment`, `qid` FROM `comments` WHERE UPPER(`comment`) LIKE UPPER(\"%$search%\") LIMIT 0, 45";

		$result = mysql_query($query,$conn);
		if ( $result ) {
			while ( $row = mysql_fetch_row($result) ) {
				array_push( $arr, $row );
			}
		}

		if( sizeof( $arr ) > 45 ) {
			return array_slice($arr, 0, 45);
		}

		return $arr;
	}

	function multiKeywordSearch($keywords)
	{
		global $conn;
		global $search;
		$search = mysql_real_escape_string( trim($search), $conn );

		$query = "SELECT `quote`, `id` FROM `quotes` WHERE MATCH (`quote`) AGAINST ('$keywords');";

		
		$result = mysql_query($query,$conn);
		$arr = Array();
		if ( $result ) {
			while ( $row = mysql_fetch_row($result) ) {
				array_push( $arr, $row );
			}
		}
		$result = NULL;

		$query = "SELECT `comment`, `qid` FROM `comments` WHERE UPPER(`comment`) LIKE UPPER(\"%$search%\") LIMIT 0, 45";

		$result = mysql_query($query,$conn);
		if ( $result ) {
			while ( $row = mysql_fetch_row($result) ) {
				array_push( $arr, $row );
			}
		}

		if( sizeof( $arr ) > 45 ) {
			return array_slice($arr, 0, 45);
		}


		return $arr;
	}

	function cntSearch($search)
	{
		global $conn;
		$search = mysql_real_escape_string( $search, $conn );
		$query = "SELECT count(*) FROM `quotes` WHERE UPPER(`quote`) LIKE UPPER('%$search%');";
		$result = mysql_query($query,$conn);
		$arr = Array();
		if ( $result ) {
			if ( $row = mysql_fetch_row($result) ) {
				return $row[0];
			}
		}
	}

?>
<?php

// base class with member properties and methods
class Users {


	function logoutUser()
	{
		session_start();
		session_destroy();
	}

	function getUsersPosts($user)
	{
		global $db;

		$user = $db->getConn()->real_escape_string($user);

		$query = "SELECT `id`, `quote` FROM `quotes` WHERE `user` = '$user' ORDER BY `date` DESC LIMIT 0, 45;";
		$result = mysqli_query($db->getConn(),$query);
		$arr = Array();
		if ( ! $result  )
			error_log( "DB Could Not Get Thought in thought_lib.php", 0 );
		else
			while ( $row = mysqli_fetch_array($result) )
				array_push( $arr, $row );
		return $arr;
	}

	function getUsersComments()
	{
		global $conn;

		if( isset( $_GET['user'] ) ) {
			$username = mysql_real_escape_string($_GET['user'],$conn);
		} else if ( isset( $_SESSION['username'] ) ) {
			$username = mysql_real_escape_string($_SESSION['username'],$conn);
		} else {
			return NULL;
		}

		$time = time();
		$query = "SELECT `id`, `qid`, `comment` FROM `comments` WHERE `user` = '$username' ORDER BY `date` DESC LIMIT 0, 45;";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "getUsersComments: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		$arr = Array();
		while ( $row = mysql_fetch_row($result) ) {
			array_push( $arr, $row );
		}
		return $arr;
	}

	function addFollower($follower,$followed)
	{
		global $conn;
		$time = time();

		$follower = mysql_real_escape_string($follower,$conn);
		$followed = mysql_real_escape_string($followed,$conn);
		$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR'],$conn);

		$query = "SELECT count(*) FROM `follow` WHERE `followed` = '$followed' AND `follower` = '$follower';";
		$result = mysql_query($query,$conn);

		if ( $result ) {
			if ( $row = mysql_fetch_row($result) ) {
				if ( $row[0] == 0 ) {
					$query = "INSERT INTO `follow` VALUES(NULL, '$followed', '$follower', '$time', '$ip');";
					$result = mysql_query($query,$conn);
					if ( !( $result ) ) {
						echo "addFollower: there was a problem with the Database!";
						echo mysql_error();
						return FALSE;
					} else {
						return TRUE;
					}
				} else {
					//Current user is already following this user
				}
			}
		}
	}

	function getUsersFollowing()
	{
		global $conn;

		if( isset( $_GET['user'] ) ) {
			$username = mysql_real_escape_string($_GET['user'],$conn);
		} else if ( isset( $_SESSION['username'] ) ) {
			$username = mysql_real_escape_string($_SESSION['username'],$conn);
		} else {
			return Array(0=>Array(0=>"Anonymous"));
		}

		$time = time();
		$query = "SELECT `follower` FROM `follow` WHERE `followed` = '$username' ORDER BY `date` DESC LIMIT 0, 45;";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "getUsersFollowing: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		$arr = Array();
		while ( $row = mysql_fetch_row($result) ) {
			array_push( $arr, $row );
		}
		return $arr;
	}

	function getUsersFollowers()
	{
		global $conn;

		if( isset( $_GET['user'] ) ) {
			$username = mysql_real_escape_string($_GET['user'],$conn);
		} else if ( isset( $_SESSION['username'] ) ) {
			$username = mysql_real_escape_string($_SESSION['username'],$conn);
		} else {
			return Array(0=>Array(0=>"Anonymous"));
		}

		$time = time();
		$query = "SELECT `followed` FROM `follow` WHERE `follower` = '$username' ORDER BY `date` DESC LIMIT 0, 45;";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "getUsersFollowers: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		$arr = Array();
		while ( $row = mysql_fetch_row($result) ) {
			array_push( $arr, $row );
		}
		return $arr;
	}

	function dbGetUser($username,$password)
	{
		global $conn;

		$username = mysql_real_escape_string($username,$conn);
		$password = mysql_real_escape_string($password,$conn);

		$time = time();
		$query = "SELECT `id` FROM `users` WHERE `id` = '$username' AND `pass` = '$password';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "dbGetUser: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}
		if ( mysql_num_rows($result) > 0 ) {
			return TRUE; //User is logged in
		} else {
			return FALSE; //User is not logged in
		}
	}

	function dbTestUsername($username)
	{
		global $conn;

		$username = mysql_real_escape_string($username,$conn);

		$time = time();
		$query = "SELECT `id` FROM `users` WHERE `id` = '$username'";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "dbTestUsername: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}
		if ( mysql_num_rows($result) > 0 ) {
			return TRUE; //Username already exists
		} else {
			return FALSE; //Username does not already exist
		}
	}

	function dbTestEmail($email)
	{
		global $conn;

		$email = mysql_real_escape_string($email,$conn);

		$time = time();
		$query = "SELECT `email` FROM `users` WHERE `email` = '$email'";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "dbTestEmail: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}
		if ( mysql_num_rows($result) > 0 ) {
			return TRUE; //Email already exists
		} else {
			return FALSE; //Email does not already exist
		}
	}

	function dbRegisterUser()
	{
		global $_POST;
		global $conn;

		$id = mysql_real_escape_string($_POST['username'],$conn);
		$pass = mysql_real_escape_string($_POST['password'],$conn);
		$email = mysql_real_escape_string($_POST['email'],$conn);
		$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR'],$conn);
		$time = time();

		//Select e-mail, pass, & id. If id is -1 and pass is -1, they are responding to sendIntroEmail()
		$query = "SELECT `id`, `pass`, `email` FROM `users` WHERE `email` = '$email';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "dbRegisterUser: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		if ( $row = mysql_fetch_row($result) ) {
			if( $row['0'] == -1 && $row['1'] == -1 ) {
				$query = "UPDATE `users` SET `id` = '$id', `pass` = '$pass' WHERE `email` = '$email';";
				$result = mysql_query($query,$conn);
				if ( !( $result ) ) {
					echo "dbRegisterUser: there was a problem with the Database!";
					echo mysql_error();
					return FALSE;
				} else {
					return TRUE;
				}
			}
		} else {
			$query = "INSERT INTO `users` VALUES('$id', '$pass', '$email', '$ip', $time);";
			$result = mysql_query($query,$conn);
			if ( !( $result ) ) {
				echo "dbRegisterUser: there was a problem with the Database!";
				echo mysql_error();
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	function getRecentUsers()
	{
		global $db;
		$arr = Array();

		$query = "SELECT * FROM `users` ORDER BY `time` DESC LIMIT 0, 45;";
		$result = mysqli_query($db->getConn(),$query);

		if ( ! $result  )
			error_log( "users.class.php:getRecentUsers" . mysql_error(), 0 );
		else 
			while ( $row = mysqli_fetch_array($result) )
				array_push($arr, $row);
		
		return $arr;
	}

	function getActiveUsers()
	{
		global $db;
		$arr = Array();

		$query = "SELECT user, count(user) AS num FROM `quotes` GROUP BY `user` ORDER BY `num` DESC LIMIT 0, 45;";
		$result = mysqli_query($db->getConn(),$query);

		if ( ! $result  )
			error_log( "users.class.php:getActiveUsers" . mysql_error(), 0 );
		else 
			while ( $row = mysqli_fetch_array($result) )
				array_push($arr, $row);
		
		return $arr;
	}
}

?>
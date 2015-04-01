<?PHP
	function sendIntroEmail()
	{
		$to = $_POST['email'];
		$subject = "Random Thoughts!";
		$body = "You are subscribed to be notified for comments on a post at http://randomthoughts.club/! Please enjoy reading other people's thoughts. Feel free to add as many of your own thoughts and comments as you please! Come back soon!\n\nRegister for a full account where you can access all of your thoughts and comments by visiting http://randomthoughts.club/login.php?page=register.\n\nVisit http://randomthoughts.club/index.php?unsubscribe=$to to stop all e-mails from this website.\n\n";
		$headers = 'From: admin@randomthoughts.club' . "\r\n";

		if( testUnsubscribe($to) == 1 ) {
			//mail($to, $subject, $body, $headers);
		}
	}

	function testUnsubscribe($to)
	{
		global $conn;
		$email = mysql_real_escape_string($to,$conn);

		$query = "SELECT `id` FROM `unsubscribe` WHERE `email` = '$email';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "testUnsubscribe: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		if ( $row = mysql_fetch_row($result) ) {
			return 0;
		} else {
			return 1;
		}
	}

	function sendForgotEmail()
	{
		global $conn;
		$email = mysql_real_escape_string($_POST['email'],$conn);

		$query = "SELECT `id`, `pass` FROM `users` WHERE `email` = '$email';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "sendForgotEmail: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		if ( $row = mysql_fetch_row($result) ) {
			$username = $row['0'];
			$password = $row['1'];
			$subject = "Random Thoughts - User/Pass";
			$body = "\nUsername: $username\nPassword: $password\n\nhttp://randomthoughts.club/\n\nVisit http://randomthoughts.club/index.php?unsubscribe=$to to stop all e-mails from this website.\n\n";
			$headers = 'From: admin@randomthoughts.club' . "\r\n";
			if( testUnsubscribe($email) == 1 ) {
				//mail($email, $subject, $body, $headers);
			}
		}
	}

	function sendEmail()
	{
		$to = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$subject = "Random Thoughts!";
		$body = "Congratulations!\n\n You have signed up at http://randomthoughts.club/! New features are added all the time. Please enjoy reading other people's thoughts. Feel free to add as many of your own thoughts and comments as you please! Come back soon!\n\nUsername: $username\nPassword: $password\n\nVisit http://randomthoughts.club/index.php?unsubscribe=$to to stop all e-mails from this website.\n\n";
		$headers = 'From: admin@randomthoughts.club' . "\r\n";

		if( testUnsubscribe($to) == 1 ) {
			//mail($to, $subject, $body, $headers);
		}
	}

	function sendCommentEmail($user,$id)
	{
		global $conn;
		
		$user = mysql_real_escape_string($user,$conn);

		$query = "SELECT `email` FROM `users` WHERE `id` = '$user';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "sendCommentEmail: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		if ( $row = mysql_fetch_row($result) ) {
			$to = $row[0];
			if ( $to != NULL ) {
				$subject = "Random Thoughts - comment added!";
				$body = "$user,\n\nSomeone has added a comment to one of your thoughts. The comment can be viewed here: http://randomthoughts.club/page.php?lim=$id.\n\nVisit http://randomthoughts.club/index.php?unsubscribe=$to to stop all e-mails from this website.\n\n";
				$headers = 'From: RandomThoughts@randomthoughts.club' . "\r\n";
				if( testUnsubscribe($to) == 1 ) {
					//mail($to, $subject, $body, $headers);

				}
			}
		}
	}

	function sendSubscribeEmail($id)
	{
		global $conn;

		$id = mysql_real_escape_string($id,$conn);

		$query = "SELECT `email` FROM `subscriptions` WHERE `post` = '$id';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "sendSubscribeEmail: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}

		while ( $row = mysql_fetch_row($result) ) {
			$to = $row[0];
			if ( $to != NULL ) {
				$subject = "Random Thoughts - comment added!";
				$body = "\nSomeone has added a comment to one of the thoughts that you subscribed to. The comment can be viewed here: http://randomthoughts.club/page.php?lim=$id.\n\nVisit http://randomthoughts.club/index.php?unsubscribe=$to to stop all e-mails from this website.\n\n";
				$headers = 'From: RandomThoughts@randomthoughts.club' . "\r\n";
				if( testUnsubscribe($to) == 1 ) {
					//mail($to, $subject, $body, $headers);
				}
			}
		}
	}
?>
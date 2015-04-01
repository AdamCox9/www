<?php

// base class with member properties and methods
class UserProfiles {


	function handleImage()
	{
		global $_FILES;
		global $_POST;
		//echo "Handling Image...<br/>";

		if ( isset( $_FILES['photo_url']['name'] ) && $_FILES['photo_url']['name'] != NULL ) {
			if ( $_FILES['photo_url']['size'] > 500000 ) {
				die( "<br/>Image Size is two large!<br/>" );
			}

			$haystack = explode(".", $_FILES['photo_url']['name']);

			if( count($haystack) > 2 ) {
				die( "<br/>Invalid Image Name!<br/>" );
			}

			if ( ( strpos(strtoupper($haystack[count($haystack)-1]), "GIF") === FALSE )
			  && ( strpos(strtoupper($haystack[count($haystack)-1]), "JPG") === FALSE )
			  && ( strpos(strtoupper($haystack[count($haystack)-1]), "JPEG") === FALSE ) ) {
				die( "<br/>1: Invalid Image Extension<br/>" );
			} else {
				$x = time();
				copy ($_FILES['photo_url']['tmp_name'], "files/".$x.$_FILES['photo_url']['name']) or die ("Could not copy");
				dbInsertImage( "files/".$x.$_FILES['photo_url']['name'] );
			}
		}
	}

	function viewGeneralProfile($user)
	{
		global $_GET;
		$generalProfile = $this->dbGetGeneralProfile($user);

		$title = "Profile";
		if ( $generalProfile['ispublic'] == "R" ) {
			$isPublic = "Private";
		} else {
			$isPublic = "Public";
		}

		if( $generalProfile['photo_url'] != NULL ) {
			$photo = "<img alt=\"$user's Profile Picture\" class='profilepic' src=\"{$generalProfile['photo_url']}\"><br><br>";
		} else {
			$photo = NULL;
		}

		$content = <<<PROFILE
		<div>
			$photo
			<div><a href="owner.php?user=$user">View Posts</a>
			<br><br>
			<table border="0" style='padding:25px;'>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Display Name:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['display_name']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Personal Website:</span></td><td valign="top"><a href="{$generalProfile['website']}">{$generalProfile['website']}</a></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Interests:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['interests']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Activities:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['activities']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Job:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['job']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Education:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['education']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">School:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['school']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Message Client Type:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['message_client_type']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Message Client Username:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['message_client']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Favorite Quote:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['quote']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Favorite Music:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['music']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Favorite Movies:</span></td><td valign="top"><span class="profile_cont">{$generalProfile['movies']}</span></td>
					</tr>
					<tr>
						<td width="125px" valign="top"><span class="profile_desc">Favorite Websites:</span></td><td width="125px" valign="top"><span class="profile_cont">{$generalProfile['fav_websites']}</span></td>
					</tr>
			</table>
		</div>

PROFILE;

		return $content;
	}

	function generalProfile()
	{
		global $_POST;
		$generalProfile = dbGetGeneralProfile($_SESSION['username']);

		if ( $generalProfile['ispublic'] == "U" ) {
			$public = "CHECKED";
		} else if ( $generalProfile['ispublic'] == "R" ) {
			$private = "CHECKED";
		} else {
			$public = "CHECKED";
		}

		$content = <<<PROFILE

		<div>
			<form method="post" action="?" enctype="multipart/form-data">
				<table border="0">
					<input type="hidden" name="hidden" value="hidden" id="hidden" />
					<input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
					<tr>
						<td>Display Name:</td><td><input class="text" type="text" name="display_name" value="{$generalProfile['display_name']}" /></td>
					</tr>
					<tr>
						<td>Photo:</td><td><input class="text" type="file" name="photo_url" value="{$generalProfile['photo_url']}" /></td>
					</tr>
					<tr>
						<td>Personal Website:</td><td><input class="text" type="text" name="website" value="{$generalProfile['website']}" /></td>
					</tr>
					<tr>
						<td>Interests:</td><td><input class="text" type="text" name="interests" value="{$generalProfile['interests']}" /></td>
					</tr>
					<tr>
						<td>Activities:</td><td><input class="text" type="text" name="activities" value="{$generalProfile['activities']}" /></td>
					</tr>
					<tr>
						<td>Job:</td><td><input class="text" type="text" name="job" value="{$generalProfile['job']}" /></td>
					</tr>
					<tr>
						<td>Education:</td><td><input class="text" type="text" name="education" value="{$generalProfile['education']}" /></td>
					</tr>
					<tr>
						<td>School:</td><td><input class="text" type="text" name="school" value="{$generalProfile['school']}" /></td>
					</tr>
					<tr>
						<td>Message Client Type:</td><td><input class="text" type="text" name="message_client_type" value="{$generalProfile['message_client_type']}" /></td>
					</tr>
					<tr>
						<td>Message Client Username:</td><td><input class="text" type="text" name="message_client" value="{$generalProfile['message_client']}" /></td>
					</tr>
					<tr>
						<td>Favorite Quote:</td><td><input class="text" type="text" name="quote" value="{$generalProfile['quote']}" /></td>
					</tr>
					<tr>
						<td>Favorite Music:</td><td><input class="text" type="text" name="music" value="{$generalProfile['music']}" /></td>
					</tr>
					<tr>
						<td>Favorite Movies:</td><td><input class="text" type="text" name="movies" value="{$generalProfile['movies']}" /></td>
					</tr>
					<tr>
						<td>Favorite Websites:</td><td><input class="text" type="text" name="fav_websites" value="{$generalProfile['fav_websites']}" /></td>
					</tr>
					<tr>
						<td>Profile Type:</td><td>Public: <input type="radio" name="ispublic" value="U" $public /> Private: <input type="radio" name="ispublic" value="R" $private /></td>
					</tr>
					<tr>
						<td></td><td><input type="submit" class="formbutton" value="Save" /></td>
					</tr>
				</table>
			</form>
		</div>

PROFILE;

		if ( isset( $_POST['hidden'] ) ) {
			//Register user in DB:
			if ( dbIsGeneralProfileRegistered() ) {
				//Update
				if ( dbUpdateGeneralProfile() ) {
					handleImage();
				}
			} else {
				//Register
				if ( dbRegisterGeneralProfile() ) {
					handleImage();
				}
			}
			$content = "
			<br/>
			You have succesfully updated your profile information.
			<br/><br/>
			<META HTTP-EQUIV=\"refresh\" content=\"2; URL=profile.php?page=view\"> ";
		}

		return $content;

	}

	function dbInsertImage($imageName)
	{
		global $_SESSION;
		global $conn;

		//echo "Inserting image...$imageName<br/><br/>";

		$id = mysql_real_escape_string($_SESSION['username'],$conn);
		
		if ( isset( $imageName ) && $imageName != NULL ) {
			$imageName = mysql_real_escape_string($imageName,$conn);
			$time = time();

			$query = "UPDATE `generalprofile` SET 
				`photo_url` = '$imageName',
				`time` = '$time'
				WHERE `id` = '$id';";

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

	function dbIsGeneralProfileRegistered()
	{
		global $conn;

		$username = mysql_real_escape_string($_SESSION['username'],$conn);

		$time = time();
		$query = "SELECT `id` FROM `generalprofile` WHERE `id` = '$username';";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "dbIsGeneralProfileRegistered: there was a problem with the Database!.<br/>";
			echo mysql_error();
		}
		if ( mysql_num_rows($result) > 0 ) {
			return TRUE; //User is logged in
		} else {
			return FALSE; //User is not logged in
		}
	}

	function dbGetGeneralProfile($username)
	{
		global $db;
		$arr = Array();

		$query = "SELECT * FROM `generalprofile` WHERE `id` = '$username';";
		$result = mysqli_query($db->getConn(),$query);

		if ( ! $result  )
			error_log( "users.class.php:getActiveUsers" . mysql_error(), 0 );
		else if ( $row = mysqli_fetch_array($result) )
			return $row;
		
		return null;
	}


	function dbRegisterGeneralProfile()
	{
		global $_POST;
		global $conn;
		global $_SESSION;

		$id = mysql_real_escape_string($_SESSION['username'],$conn);
		$display_name = mysql_real_escape_string($_POST['display_name'],$conn);
		$photo_url = mysql_real_escape_string($_POST['photo_url'],$conn);
		$website = mysql_real_escape_string($_POST['website'],$conn);
		$interests = mysql_real_escape_string($_POST['interests'],$conn);
		$activities = mysql_real_escape_string($_POST['activities'],$conn);
		$job = mysql_real_escape_string($_POST['job'],$conn);
		$education = mysql_real_escape_string($_POST['education'],$conn);
		$school = mysql_real_escape_string($_POST['school'],$conn);
		$message_client_type = mysql_real_escape_string($_POST['message_client_type'],$conn);
		$message_client = mysql_real_escape_string($_POST['message_client'],$conn);
		$quote = mysql_real_escape_string($_POST['quote'],$conn);
		$music = mysql_real_escape_string($_POST['music'],$conn);
		$movies = mysql_real_escape_string($_POST['movies'],$conn);
		$fav_websites = mysql_real_escape_string($_POST['fav_websites'],$conn);
		$ispublic = mysql_real_escape_string($_POST['ispublic'],$conn);
		$time = time();

		$query = "INSERT INTO `generalprofile` VALUES('$id', '$display_name', '$photo_url', '$website', '$interests', '$activities', '$job', '$education', '$school', '$message_client_type', '$message_client', '$quote', '$music', '$movies', '$fav_websites',  '$ispublic',  $time);";
		$result = mysql_query($query,$conn);
		if ( !( $result ) ) {
			echo "dbRegisterUser: there was a problem with the Database!";
			echo mysql_error();
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function dbUpdateGeneralProfile()
	{
		global $_POST;
		global $conn;
		global $_SESSION;

		$id = mysql_real_escape_string($_SESSION['username'],$conn);
		$display_name = mysql_real_escape_string($_POST['display_name'],$conn);
		$photo_url = mysql_real_escape_string($_POST['photo_url'],$conn);
		$website = mysql_real_escape_string($_POST['website'],$conn);
		$interests = mysql_real_escape_string($_POST['interests'],$conn);
		$activities = mysql_real_escape_string($_POST['activities'],$conn);
		$job = mysql_real_escape_string($_POST['job'],$conn);
		$education = mysql_real_escape_string($_POST['education'],$conn);
		$school = mysql_real_escape_string($_POST['school'],$conn);
		$message_client_type = mysql_real_escape_string($_POST['message_client_type'],$conn);
		$message_client = mysql_real_escape_string($_POST['message_client'],$conn);
		$quote = mysql_real_escape_string($_POST['quote'],$conn);
		$music = mysql_real_escape_string($_POST['music'],$conn);
		$movies = mysql_real_escape_string($_POST['movies'],$conn);
		$fav_websites = mysql_real_escape_string($_POST['fav_websites'],$conn);
		$ispublic = mysql_real_escape_string($_POST['ispublic'],$conn);
		$time = time();

		$query = "UPDATE `generalprofile` SET 
			`display_name` = '$display_name',
			`photo_url` = '$photo_url',
			`website` = '$website',
			`interests` = '$interests',
			`activities` = '$activities',
			`job` = '$job',
			`education` = '$education',
			`school` = '$school',
			`message_client_type` = '$message_client_type',
			`message_client` = '$message_client',
			`quote` = '$quote',
			`music` = '$music',
			`movies` = '$movies',
			`fav_websites` = '$fav_websites',
			`ispublic` = '$ispublic',
			`time` = '$time'
			WHERE `id` = '$id';";

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

?>
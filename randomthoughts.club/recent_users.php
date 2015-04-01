<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$title = "Recent Users";
	$head = null;

	$Users = new Users();

	$RecentUsers = $Users->getRecentUsers();

	$RecentUsersOut = null;
	foreach( $RecentUsers as $RecentUser ) {
		$urlParam = urlencode( $RecentUser['id'] );

		$epoch = $RecentUser['time'];
		date_default_timezone_set("America/Los_Angeles");
		$dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
		$date = $dt->format('Y-m-d H:i:s'); // output = 2012-08-15 00:00:00 

		$RecentUsersOut .= <<<HTML
			<a href="profile.php?user=$urlParam">{$RecentUser['id']}</a><br>
			$date<br>
			<br>
HTML;
	}

	$content = <<<HTML

			<h1>$title</h1>

			$RecentUsersOut

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
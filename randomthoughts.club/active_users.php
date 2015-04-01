<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$Users = new Users();

	$ActiveUsers = $Users->getActiveUsers();

	$ActiveUsersOut = null;
	foreach( $ActiveUsers as $ActiveUser ) {
		$urlParam = urlencode( $ActiveUser['user'] );
		$ActiveUsersOut .= <<<HTML
			<a href="profile.php?user=$urlParam">{$ActiveUser['user']}</a> ({$ActiveUser['num']})
			<br>
HTML;
	}

	$title = "Active Users";
	$head = null;

	$content = <<<HTML

			<h1>$title</h1>

			$ActiveUsersOut

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
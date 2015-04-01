<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$UserProfiles = new UserProfiles();

	$profile = isset( $_GET['user'] ) ? $_GET['user'] : 'Anonymous';

	$UserProfileOut = $UserProfiles->viewGeneralProfile($profile);

	$title = "Profile";
	$head = null;


	$content = <<<HTML

			<h1>$title</h1>

			$UserProfileOut

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
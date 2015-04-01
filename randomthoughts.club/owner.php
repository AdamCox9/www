<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$title = "Owner";
	$head = null;

	$Users = new Users();

	if( isset( $_GET['user'] ) ) {
		$thoughts = $Users->getUsersPosts($_GET['user']);
		$quotes = "<hr><div style='text-align:left;'>";
		foreach( $thoughts as $thought ) {
			if ( strlen( $thought[1] ) > 300 ) {
				$quote = strip_tags(substr(stripslashes($thought[1]),0,300))."...";
			} else {
				$quote = strip_tags(stripslashes($thought[1]));
			}

			$quotes .= "<div style='padding:15px;'>$quote <a href='page.php?lim=".$thought[0]."'>view&gt;&gt;&gt;</a></div><hr>";
		}
		$content = $quotes . "</div>";

	}

	$content = "<h1>$title</h1>$content";

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
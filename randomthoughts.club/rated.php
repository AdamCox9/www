<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$title = "Rated Thoughts";
	$head = null;

	$x = isset( $_GET['x'] ) ? $_GET['x'] : 0;
	$thoughts = getRatedPosts();
	$quotes = "<div style='text-align:left;'>";
	foreach( $thoughts as $thought ) {
		if ( strlen( $thought['q'] ) > 100 ) {
			$quote = htmlentities(substr(stripslashes($thought['q']),0,100))."...";
		} else {
			$quote = htmlentities(stripslashes($thought['q']));
		}

		$quotes .= "<div style='padding-bottom:15px;'>$quote <a href='page.php?lim=".$thought[0]."'>view&gt;&gt;&gt;</a></div>";
	}
	$content = $quotes . "</div>";

	$content = "<h1>$title</h1>$content";

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
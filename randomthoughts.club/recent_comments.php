<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$title = "Recent Comments";
	$head = null;

	$x = isset( $_GET['x'] ) ? $_GET['x'] : 0;
	$thoughts = getRecentComments($x);
	$quotes = "<div style='text-align:left;'>";
	foreach( $thoughts as $thought ) {
		if ( strlen( $thought[1] ) > 100 ) {
			$quote = htmlentities(substr(stripslashes($thought[1]),0,100))."...";
		} else {
			$quote = htmlentities(stripslashes($thought[1]));
		}

		$quotes .= "<div style='padding:15px;'>$quote <a href='page.php?lim=".$thought[0]."'>view&gt;&gt;&gt;</a></div>";
	}
	$content = $quotes . "</div>";

	$content = "<h1>$title</h1>$content";

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
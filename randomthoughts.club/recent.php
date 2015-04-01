<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$title = "Recent Thoughts";
	$head = null;

	$x = $y = isset( $_GET['x'] ) ? $_GET['x'] : 0;

	$thoughtCount = getThoughtCount();
	$commentCount = getCommentCount();
	$end = $x+450;

	$pagination = null;
	while( $x < $thoughtCount && $x < $end ) {
		$pagination = $pagination."<a style='font-size:8pt;' href='recent.php?x=$x'>$x - ".($x+45)."</a> | ";
		$x = $x + 45;
	}
	$pagination = $pagination."...";

	$thoughts = getRecent($y);
	$quotes = "<div style='text-align:left;'>";
	foreach( $thoughts as $thought ) {
		if ( strlen( $thought['quote'] ) > 100 )
			$quote = htmlentities(substr(stripslashes($thought['quote']),0,100))."...";
		else
			$quote = htmlentities(stripslashes($thought['quote']));

		$quotes .= "<div style='padding-bottom:15px;'>$quote <a href='page.php?lim=".$thought['id']."'>view&raquo;</a></div>";
	}
	$content = $quotes . "</div>";

	$content = "<h1>$title</h1>$content<br>$pagination";

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
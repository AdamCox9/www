<?PHP

	require_once 'local.php';

	$letter = isset( $_GET['letter'] ) ? $_GET['letter'] : "A"; // A...

	$page = isset( $_GET['cnt'] ) ? $_GET['cnt'] : 0;
	$letter = ucwords( $letter );
	$title = "Letter $letter - Page $page";
	$head = null;


	$letters = file_get_contents( "http://127.0.0.1/iw/html/lib/php/twl/org/$letter.txt" );
	$letters = ucwords( strtolower( $letters ) );
	$words = explode( "\n", $letters );


	$link_cnt = intval(count($words)/75);

	$pagination = null;
	while( $link_cnt-- > 0 ) {
		$pagination = "<a href='letter.php?letter=$letter&cnt=$link_cnt'>$link_cnt</a> ".$pagination;
	}

	$word_links = null;

	$words = array_slice($words, $page*75, 75);

	foreach( $words as $word ) { 
		$word_links .= "<a href='word.php?word=$word'>$word</a>";
	}

	$content = <<<HTML

			<h1>Letter $letter - Page $page</h1>
			<p>
				$word_links
			</p>
			<p>
				$pagination
			</p>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
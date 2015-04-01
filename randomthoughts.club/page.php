<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$lim = isset( $_GET['lim'] ) ? $_GET['lim'] : 0;

	$title = "A Random Thought";
	$head = <<<HTML

		<script src='https://www.google.com/recaptcha/api.js'></script>

HTML;

	$submission = null;

	if( isset( $_POST['comment'] ) && isset( $_POST['g-recaptcha-response'] ) ) {
		$secret = '6Le6i_4SAAAAAHJehPYo-r2lPAYGLfKjALW4Uo31';
		$remoteip = $_SERVER['REMOTE_ADDR'];
		$response = $_POST['g-recaptcha-response'];
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
		$response = json_decode( file_get_contents( $url ) );
	
		if( $response->success === true ) {
			$id = addComment($lim,$_POST['comment']);
			$submission = "<div style='margin:20px;font-weight:bold;color:red;'>Success! Comment Added!</div>";
		}
	}


	$RandomThought = makePresentable( getThought($lim) );
	$Comments = getComments( $lim );

	$SearchKey = implode( 0, array_slice( explode(" ", $RandomThought ), 0, 10 ) );

	$SearchKey = substr($RandomThought,0,75);

	$MicroAmazonList = microSearchForItems('All',$SearchKey,1);
//	die( $MicroAmazonList );
	if( ! $MicroAmazonList )
		$MicroAmazonList = microSearchForItems('All',"Random Thoughts",1);



	$YoutubeVideos = GetYoutubeVideos($SearchKey,null);

	$labels = getLabels($RandomThought);


	$CommentOut = null;
	$x = 1;
	foreach( getComments($lim) as $Comment ) {
		$CommentOut .= "<p>$x) " . makePresentable( $Comment['comment'] ) . " <!--{$Comment['date']}--> <a href='profile.php?user={$Comment['user']}'>{$Comment['user']}</a></p>";
		$x++;
	}

	if( $CommentOut != null )
		$CommentOut = "<h2>Comments</h2>$CommentOut";

	$content = <<<HTML

			<div>
				$MicroAmazonList
			</div>
			<div style='clear:both;'>
				<h1 style='margin-top:20px;'>A Random Thought</h1>
			</div>
			<p>
				$RandomThought
			</p>
			<div>
				$CommentOut
			</div>
			<div>
				<form method='post' action='page.php?lim=$lim'>
					Comment on this Thought:<br>
					<textarea name='comment'></textarea>
					<br><br>
					<div class="g-recaptcha" data-sitekey="6Le6i_4SAAAAAO2ngOqT4bz7Jyy5cbDWKbSEEk-g"></div>
					<br>
					<button>Submit</button>
				</form>
			</div>
			<div>
				$YoutubeVideos
			</div>
			<div>
				$labels
			</div>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
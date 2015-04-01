<?PHP

	require_once 'local.php';

	$title = "Random Thoughts";
	$head = <<<HTML

		<script src='https://www.google.com/recaptcha/api.js'></script>

HTML;

/*

When your users submit the form where you integrated reCAPTCHA, you'll get as part of the payload a string with the name "g-recaptcha-response". In order to check whether Google has verified that user, send a GET request with these parameters:
URL: https://www.google.com/recaptcha/api/siteverify
secret(required)	6Le6i_4SAAAAAHJehPYo-r2lPAYGLfKjALW4Uo31
response(required)	The value of 'g-recaptcha-response'.
remoteip	The end user's ip address.

*/

	$quote = isset( $_POST['quote'] ) ? $_POST['quote'] : null;
	$submission = null;
	if( ! is_null( $quote ) && isset( $_POST['category'] ) && isset( $_POST['g-recaptcha-response'] ) ) {
		$secret = '6Le6i_4SAAAAAHJehPYo-r2lPAYGLfKjALW4Uo31';
		$remoteip = $_SERVER['REMOTE_ADDR'];
		$response = $_POST['g-recaptcha-response'];
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
		$response = json_decode( file_get_contents( $url ) );
	
		if( $response->success === true ) {
			if( ! $db )
				$db = new DatabaseConnection();
			$id = setQuote($_POST['quote'],$_POST['category']);
			$submission = "<div style='margin:20px;font-weight:bold;color:red;'><a href='page.php?lim=$id' style='color:red;'>Success! View this thought&raquo;</a></div>";
			$quote = null;
		}
	}

	$content = <<<HTML

			<h1>Random Thoughts</h1>
			$submission
			<p>
				Add a Random Thought:
			</p>
			<div>
				<form method='post' action='index.php'>
					Category: 
					<select name='category'>
						<option value='Random Thought'>Random Thought</option>
						<option value='Funny Thought'>Funny Thought</option>
						<option value='Serious Thought'>Serious Thought</option>
						<option value='Delicious Thought'>Delicious Thought</option>
						<option value='Random Recipe'>Random Recipe</option>
						<option value='Random Quote'>Random Quote</option>
					</select>
					<br><br>
					Enter Thought:<br>
					<textarea name='quote'>$quote</textarea>
					<br><br>
					<div class="g-recaptcha" data-sitekey="6Le6i_4SAAAAAO2ngOqT4bz7Jyy5cbDWKbSEEk-g"></div>
					<br>
					<button>Submit</button>
				</form>
			</div>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
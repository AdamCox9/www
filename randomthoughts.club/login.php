<?PHP

	require_once 'local.php';

	$title = "Login";
	$head = null;
	$invalid = null;

	if( isset( $_POST['username'] ) || isset( $_POST['password'] ) )
		$invalid = "<div style='color:red;'>Invalid Login</div>";

	$content = <<<HTML

			<h1>$title</h1>

			<div>$invalid</div>

			<form method="post">
				<table>
					<tr>
						<td>Username:</td>
						<td><input type='text' name='username'></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type='password' name='password'></td>
					</tr>
				</table>
				<button>Login</button>
			</form>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
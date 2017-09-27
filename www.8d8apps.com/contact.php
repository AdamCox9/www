<?PHP

	require_once 'local.php';

	$content = <<<HTML

<br>
<br>	E-Mails can be sent to <a href="Adam.Cox9@gmail.com">Adam.Cox9@gmail.com</a>
<br>
<br>	8D8 Apps
<br>	358 Main Street
<br>	Haverhill, MA 01830
<br>	
<br>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Contact", $template );

	echo $template;

?>
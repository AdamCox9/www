<?PHP

	$template = file_get_contents( 'template.html' );

	$head = "";

	$content = <<<HTML

<br>
<br>
<br>
<br>	E-Mails can be sent to <a href="Adam.Cox9@gmail.com">Adam.Cox9@gmail.com</a>
<br>
<br>	Adam Cox - 8D8 Apps
<br>	106 Chadwick Street
<br>	Haverhill, MA 01835
<br>	
<br>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Contact", $template );

	echo $template;

?>
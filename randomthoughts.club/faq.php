<?PHP

	require_once 'local.php';

	$title = "FAQ";
	$head = null;

	$content = <<<HTML

			<h1>$title</h1>

			<div style='padding:20px;'>
				<span style='font-weight:bold;'>Question:</span> How do I use this website?
				<br>
				<span style='font-weight:bold;'>Answer:</span> Click on the 'Next Random Thought' link to see a random thought and click on the 'Add New Thought' link to add a new thought!
			</div>

			<div style='padding:20px;'>
				<span style='font-weight:bold;'>Question:</span> How do I report copyright or file DCMA?
				<br>
				<span style='font-weight:bold;'>Answer:</span> Send an e-mail to <a href='mailto:adam.cox9@gmail.com'>Adam.Cox9@gmail.com</a>! <br>8D8 Apps, c/o Adam Cox, 106 Chadwick Street, Unit #1, Haverhill, MA 01835
			</div>

			<div style='padding:20px;'>
				<span style='font-weight:bold;'>Question:</span> Do I have to register to post thoughts and comments?
				<br>
				<span style='font-weight:bold;'>Answer:</span> No! You can post without having to register or log-in!
			</div>

			<div style='padding:20px;'>
				<span style='font-weight:bold;'>Question:</span> Do I need to submit my e-mail to post thoughts and comments?
				<br>
				<span style='font-weight:bold;'>Answer:</span> No! You can post without giving us your e-mail address!
			</div>

			<div style='padding:20px;'>
				<span style='font-weight:bold;'>Question:</span> I have uploaded a video on Youtube and do not want to allow it to be embedded on this site:
				<br>
				<span style='font-weight:bold;'>Answer:</span> Here is how to disable this option:
				<ul>
					<li>Visit your Video Manager on Youtube <a href='http://www.youtube.com/my_videos'>http://www.youtube.com/my_videos</a></li>
					<li>Find the video you wouldd like to change and click Edit.</li>
					<li>Click Advanced Settings under the video.</li>
					<li>Uncheck the Allow Embedding checkbox under the "Distribution Options" section.</li>
					<li>Click Save changes at the bottom of the page.</li>
				</ul>
			</div>
HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
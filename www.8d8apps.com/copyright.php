<?PHP

	require_once 'local.php';

	$content = <<<HTML

<br>
<br>
<br>	Copyright Infringement Notification
<br>
<br>	To file a copyright infringement notification with us, you will need to send a written communication that includes substantially the following (please consult your legal counsel or see Section 512(c)(3) of the Digital Millennium Copyright Act to confirm these requirements):
<br>
<br>    A physical or electronic signature of a person authorized to act on behalf of the owner of an exclusive right that is allegedly infringed.
<br>    Identification of the copyrighted work claimed to have been infringed, or, if multiple copyrighted works at a single online site are covered by a single notification, a representative list of such works at that site.
<br>    Identification of the material that is claimed to be infringing or to be the subject of infringing activity and that is to be removed or access to which is to be disabled, and information reasonably sufficient to permit the service provider to locate the material. Providing URLs in the body of an email is the best way to help us locate content quickly.
<br>    Information reasonably sufficient to permit the service provider to contact the complaining party, such as an address, telephone number, and, if available, an electronic mail address at which the complaining party may be contacted.
<br>    A statement that the complaining party has a good faith belief that use of the material in the manner complained of is not authorized by the copyright owner, its agent, or the law.
<br>    A statement that the information in the notification is accurate, and under penalty of perjury, that the complaining party is authorized to act on behalf of the owner of an exclusive right that is allegedly infringed.
<br>
<br>	Digital copies can be sent to: Adam.Cox9@gmail.com
<br>
<br>	Adam Cox - 8D8 Apps
<br>	106 Chadwick Street
<br>	Haverhill, MA 01835
<br>	
<br>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Report Copyright", $template );

	echo $template;

?>
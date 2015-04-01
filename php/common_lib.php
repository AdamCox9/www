<?PHP

function wtf($var, $arrayOfObjectsToHide=null, $fontSize=11)
{
    $text = print_r($var, true);

    if (is_object($arrayOfObjectsToHide)) {
        foreach ($arrayOfObjectsToHide as $objectName) {
            $searchPattern = '#('.$objectName.' Object\n(\s+)\().*?\n\2\)\n#s';
            $replace = "$1<span style=\"color: #FF9900;\">--&gt; HIDDEN - courtesy of wtf() &lt;--</span>)";
            $text = preg_replace($searchPattern, $replace, $text);
        }
    }

    // color code objects
    $text = preg_replace('#(\w+)(\s+Object\s+\()#s', '<span style="color: #079700;">$1</span>$2', $text);
    // color code object properties
    $text = preg_replace('#\[(\w+)\:(public|private|protected)\]#', '[<span style="color: #000099;">$1</span>:<span style="color: #009999;">$2</span>]', $text);
    
    echo '<pre style="font-size: '.$fontSize.'px; line-height: '.$fontSize.'px;">'.$text.'</pre>';
}

/*****
	
	This will make labels that link to word.php out of array of words.
	TODO return max of 75 labels, ordered by number of occurences and then random

 *****/
function getLabels($labels,$lCnt=50)
{
	//Clean up data
	$labels = strtoupper($labels);
	$labels = preg_replace("/[^a-zA-Z0-9\s]/", " ", $labels);
	$labels = preg_replace("/\s+/", " ", $labels);
	$labels = ucwords( strtolower( $labels ) );
	$labels = explode(" ",$labels);

	$common = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
					"Has","In","On","Senses","Http","Href","Www","If","For","Https","From","You","My","Com","Or",
					"It","The","Is","And","Of","To","By","Be","An","That","Also","Can","With","As","Is","Than","Adj","Where","More",
					"Will","At","Same","Are","He","Was","Its","Ii","Them","Keep","Take","Verb");

	$labels = array_diff($labels,$common);

	foreach( $labels as $key => $val )
		if( is_numeric( $val ) )
			unset( $labels[$key] );

//	wtf( $common );

	//count duplicate labels
	$labels = array_count_values($labels);
	asort( $labels );

	$labels = array_reverse ( $labels );

	$labels = array_slice( $labels, 0, $lCnt );

	$labelLinks = null;
	foreach( $labels as $label => $value ) {
		if( $label != "" )
			$labelLinks .= "[<a href='aws/storeFront.php?pagenum=1&amp;category=All&amp;displayCat=$label'>$label</a>] ";
	}

	return "<br><br>$labelLinks";
}

function getArrLabels($labels,$lCnt=50)
{
	
}


/*****
	Does $content reside at /cache/$lib/$type/$name
 *****/
function checkCache($lib,$type,$name,$pagenum='',$category='')
{
	$name = preg_replace("/[^a-zA-Z0-9\s]/", "", $pagenum.$category.$name);
	$name = substr( "cache/$lib/$type/$name", 0, 250 );
	$filename = "$name.xml";

	if( file_exists( $filename ) ) {
/*		if( time() - filemtime($filename) > 60*60*24*30 ) { //sec*min*hour*days
			return null;
		}*/
		return simplexml_load_file( $filename );
	}
	return null;
}

/*****
	Give $content home at /cache/$lib/$type/$name
	/cache/amazon/search/DOG.xml
	/cache/amazon/item/DOG.xml
 *****/
function addToCache($lib,$type,$name,$pagenum='',$category='',$content)
{
	$name = preg_replace("/[^a-zA-Z0-9\s]/", "", $pagenum.$category.$name);
	$name = substr( "cache/$lib/$type/$name", 0, 250 );
	$filename = "$name.xml";

	file_put_contents( $filename, $content );
}

function removeCommonWords($arr)
{
	//Remove common words
	$common = Array("TO","OF","AND","WHEN","IDK","BFF","ARE",
					"BE","NO","ONE","WANTS","IS","IF","OF","THE","IT","AN","WHAT",
					"DO","YOU","WOULD","WE","WILL","NEED","THAT","UR","AND","OR",
					"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q",
					"R","S","T","U","V","W","X","Y","Z","MY","TO","IVE","BEEN","THERE");

	return $arr;
}

function addSmileys($text)
{
	/*
	if ($handle = opendir('/Library/WebServer/Documents/randomthoughts.club/web/public/smile/')) {

		//This is the correct way to loop over the directory.
		while (false !== ($file = readdir($handle))) {
			if( $file == "smile.php" || $file == "." || $file == NULL || $file == ".." ) {
			} else {
				$text = str_ireplace(" ".substr($file,0,strlen($file)-4)." ", " <img alt='smiley' src='smile/$file' title='".substr($file,0,strlen($file)-4)."'> ", $text);
			}
		}

		closedir($handle);
	}*/

	$text = str_ireplace(":)","<img alt='happy smiley' src='smile/lol.png'>",$text);
	$text = str_ireplace(":]","<img alt='happy smiley' src='smile/lol.png'>",$text);

	$text = str_ireplace(":(","<img alt='sad smiley' src='smile/sad.png'>",$text);
	$text = str_ireplace(":[","<img alt='sad smiley' src='smile/sad.png'>",$text);

	return $text;
}

function html2txt($document){
	/*$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
				   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
				   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
				   '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
	);
	*/

	return $document;

}

function makePresentable($string)
{
	$string = ucfirst($string);

	trim($string);

	if( strlen( $string ) == 0 )
		return null;

	//Use regular expressions to replace only when there is NO character first
	/*$string = preg_replace("\bdont ","don't",$string);
	$string = preg_replace("\bDont ","Don't",$string);
	$string = preg_replace("ive ","I've",$string);
	$string = preg_replace("dont ","don't",$string);
	$string = preg_replace("Dont ","Don't",$string);
	$string = preg_replace("im ","I'm",$string);*/

	$string = str_replace( " i ", " I ", $string );
	$string = str_replace(" don;t "," don't ",$string);
	$string = str_replace(" dont "," don't ",$string);
	$string = str_replace(" Dont "," Don't ",$string);
	$string = str_replace(" dont "," don't ",$string);
	$string = str_replace(" doesnt "," doesn't ",$string);
	$string = str_replace(" Doesnt "," Doesn't ",$string);
	$string = str_replace(" shes "," she's ",$string);
	$string = str_replace(" Shes "," She's ",$string);
	$string = str_replace(" hes "," he's ",$string);
	$string = str_replace(" Hes "," He's ",$string);
	$string = str_ireplace(" im "," I'm ",$string);
	$string = str_replace(" i'm "," I'm ",$string);
	$string = str_ireplace(" ive "," I've ",$string);

	$lower = Array(". a",". b",". c",". d",". e",". f",". g",". h",". i",". j",". k",". l",". m",". n",". o",". p",". q",". r",". s",". t",". u",". v",". w",". x",". y",". z");
	$upper = Array(". A",". B",". C",". D",". E",". F",". G",". H",". I",". J",". K",". L",". M",". N",". O",". P",". Q",". R",". S",". T",". U",". V",". W",". X",". Y",". Z");

	$string = str_replace($lower,$upper,$string);

	$lower = Array("! a","! b","! c","! d","! e","! f","! g","! h","! i","! j","! k","! l","! m","! n","! o","! p","! q","! r","! s","! t","! u","! v","! w","! x","! y","! z");
	$upper = Array("! A","! B","! C","! D","! E","! F","! G","! H","! I","! J","! K","! L","! M","! N","! O","! P","! Q","! R","! S","! T","! U","! V","! W","! X","! Y","! Z");

	$string = str_replace($lower,$upper,$string);

	$lower = Array("? a","? b","? c","? d","? e","? f","? g","? h","? i","? j","? k","? l","? m","? n","? o","? p","? q","? r","? s","? t","? u","? v","? w","? x","? y","? z");
	$upper = Array("? A","? B","? C","? D","? E","? F","? G","? H","? I","? J","? K","? L","? M","? N","? O","? P","? Q","? R","? S","? T","? U","? V","? W","? X","? Y","? Z");

	$string = str_replace($lower,$upper,$string);

	$string = trim($string);
	
	$strlen = strlen($string);
	if( $strlen > 0 && $string[$strlen-1] != "." && $string[$strlen-1] != "?" && $string[$strlen-1] != "!" && $string[$strlen-1] != ">" && $string[$strlen-1] != "'" && $string[$strlen-1] != '"' ) {
		$string = $string.".";
	}
	$string = addSmileys($string);
	$string = str_replace("<a ", "<a rel='nofollow' ", $string);
	$string = stripslashes($string);

	$string = nl2br( $string );
	$string = str_replace( '<br/>', '<br>', $string );
	$string = str_replace( '<br />', '<br>', $string );

	if ( $strlen > 0 && substr_count( "<", $string ) % 2 != 0 || substr_count( ">", $string ) % 2 != 0 ) {
		$string = htmlentities($string);
	}

	$string = str_replace( "<a ", "<a rel='nofollow' ", $string );

	return $string;
}

?>
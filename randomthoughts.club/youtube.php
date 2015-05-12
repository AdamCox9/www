<?PHP

	if( isset( $_GET['q'] ) ) {
		$q = ucwords( strtolower( $_GET['q'] ) );
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://randomthoughts.club/aws/storeFront.php?pagenum=1&category=All&displayCat=$q");
		exit;
	}

	require_once 'local.php';

	$title = "Home";

	$head = "";

	$EntryID = isset( $_GET['video'] ) ? $_GET['video'] : 'pxxh25UBxkc';

	if( $EntryID == 'LcfjjCcJOGg' || $EntryID == '5Hvr4wUdsPk' ||  $EntryID == 'FMllELVDWaI' ||  $EntryID == 'SVqhon-bEsI' ||  $EntryID == 'SgofX5op7xI' ||  $EntryID == 'a-Agr6T3kG4' ||  $EntryID == 'cAtR_WEeqUg' ||  $EntryID == 'guyQn69Ry7g' ||  $EntryID == 'x0MQSE3FXJA' || $EntryID == 'y5eKfGnqZGE' || $EntryID == '6zPY7d1rM2w' || $EntryID == 'M5QVAv_pdAE' || $EntryID == 'jB2K25meogw' || $EntryID == '34wx8235vVs' || $EntryID == '2Ksj6NsxjoM' || $EntryID == '4MBUoZCRtww' || $EntryID == 'DjfQWVeiO7M' || $EntryID == 'L-_GHnDyOlo' || $EntryID == 'K6YRlC5mhOw' || $EntryID == '_ZR6CtPklIk' || $EntryID == 'sVAx1dsEt9I' || $EntryID == 'SFY4TGB5O8g' || $EntryID == '29sDpAdHv-M' || $EntryID == 'kOuI_8jHlAg' || $EntryID == 'boTEt7n_-KE' ) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$YoutubeVideo = GetYoutubeVideo($EntryID);
	$SearchTerm = $title;

	//Global to be overwritten
	$labels = "";

	//List a sample of each content on this page...
	$YoutubeVideos = GetYoutubeVideos($SearchTerm,null);
	$SearchTerm = ucwords(strtolower($SearchTerm));
	$AmazonList = searchForItems('All',$SearchTerm,1);
	if( $AmazonList ) {
		$MicroAmazonList = microSearchForItems('All',$SearchTerm,1);
	} else {
		$MicroAmazonList = null;
	}
	if( sizeof( explode(" ", $title) ) == 1 )
		$wordnet_def = getWordnetDef($title);
	else
		$wordnet_def = null;

	if( ! $YoutubeVideo )
		$AmazonList = null;

	$head = null;

	$labels = getLabels($labels);

	$content = <<<HTML
			<div>
				$MicroAmazonList
			</div>
			<div style='clear:both;'>
				<h1 style='margin-top:20px;'>$SearchTerm</h1>
			</div>
			<div>
				$YoutubeVideo
			</div>
			<div>
				$AmazonList
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
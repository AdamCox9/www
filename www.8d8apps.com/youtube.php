<?PHP

	require 'local.php';

	if( isset( $_GET['q'] ) ) {
		$q = ucwords( strtolower( $_GET['q'] ) );
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://www.8d8apps.com/storeFront.php?pagenum=1&category=All&displayCat=$q");
		exit;
	}

	$EntryID = isset( $_GET['video'] ) ? $_GET['video'] : 'pxxh25UBxkc';

	if( 
		$EntryID == '3caobkkcfFU' || 
		$EntryID == 'b2HAJxxAXQ4' || 
		$EntryID == 'BeAMD4mrNP4' || 
		$EntryID == 'c4hExR8h3LA' || 
		$EntryID == 'gbIclcR4VKU' || 
		$EntryID == 'Jp0WcfE-u6U' || 
		$EntryID == 'kT1r1oI_sAM' || 
		$EntryID == 'L3MdbMSi1FM' || 
		$EntryID == 'TKBI8N_WTzE' || 
		$EntryID == 'TRJFeuVto50' || 
		$EntryID == 'UJX5zHzMiIs' || 
		$EntryID == 'ye2STdMhiqo' || 
		$EntryID == 'LcfjjCcJOGg' || 
		$EntryID == '5Hvr4wUdsPk' ||  
		$EntryID == 'FMllELVDWaI' ||  
		$EntryID == 'SVqhon-bEsI' ||  
		$EntryID == 'SgofX5op7xI' ||  
		$EntryID == 'a-Agr6T3kG4' ||  
		$EntryID == 'cAtR_WEeqUg' ||  
		$EntryID == 'guyQn69Ry7g' ||  
		$EntryID == 'x0MQSE3FXJA' || 
		$EntryID == 'y5eKfGnqZGE' || 
		$EntryID == '6zPY7d1rM2w' || 
		$EntryID == 'M5QVAv_pdAE' || 
		$EntryID == 'jB2K25meogw' || 
		$EntryID == '34wx8235vVs' || 
		$EntryID == '2Ksj6NsxjoM' || 
		$EntryID == '4MBUoZCRtww' || 
		$EntryID == 'DjfQWVeiO7M' || 
		$EntryID == 'L-_GHnDyOlo' || 
		$EntryID == 'K6YRlC5mhOw' || 
		$EntryID == '_ZR6CtPklIk' || 
		$EntryID == 'sVAx1dsEt9I' || 
		$EntryID == 'SFY4TGB5O8g' || 
		$EntryID == '29sDpAdHv-M' || 
		$EntryID == 'kOuI_8jHlAg' || 
		$EntryID == 'boTEt7n_-KE' ||
		$EntryID == 'sfX6L1vc4CI' ||
		$EntryID == 'M5QVAv_pdAE' || 
		$EntryID == 'jB2K25meogw' || 
		$EntryID == '34wx8235vVs' || 
		$EntryID == '2Ksj6NsxjoM' || 
		$EntryID == '4MBUoZCRtww' || 
		$EntryID == 'DjfQWVeiO7M' || 
		$EntryID == 'L-_GHnDyOlo' || 
		$EntryID == 'K6YRlC5mhOw' || 
		$EntryID == '_ZR6CtPklIk' || 
		$EntryID == 'sVAx1dsEt9I' || 
		$EntryID == 'SFY4TGB5O8g' || 
		$EntryID == '29sDpAdHv-M' || 
		$EntryID == 'kOuI_8jHlAg' || 
		$EntryID == 'boTEt7n_-KE'
	) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$YoutubeVideo = GetYoutubeVideo($EntryID);
	$SearchTerm = $title;

	//Global to be overwritten
	$labels = "";

	//List a sample of each content on this page...
	$YoutubeVideos = GetYoutubeVideos($SearchTerm,null,"android");
	$SearchTerm = ucwords(strtolower($SearchTerm));
	$AmazonList = searchForItems('All',$SearchTerm,1);
	$MicroAmazonList = microSearchForItems('All',$SearchTerm,1);

	if( sizeof( explode(" ", $title) ) == 1 )
		$wordnet_def = getWordnetDef($title);
	else
		$wordnet_def = null;

	if( ! $YoutubeVideo )
		$AmazonList = null;

	$labels = getLabels($labels,1);

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
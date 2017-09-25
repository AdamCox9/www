<?PHP
	chdir("..");
	require 'local.php';

	$SearchTerm = isset( $_GET['displayCat'] ) ? $_GET['displayCat'] : null;
	$Category = isset( $_GET['category'] ) ? $_GET['category'] : null;
	$pagenum = isset( $_GET['pagenum'] ) ? $_GET['pagenum'] : 1;

	if( $Category == null && $SearchTerm == null )
		$title = "Store Front";
	else
		$title = "Store Front - $SearchTerm - $Category - $pagenum";

	$head = "<base href='..'>";
	$head .= "<link rel='canonical' href='http://www.8d8apps.com/aws/storeFront.php?pagenum=$pagenum&amp;category=$Category&amp;displayCat=".urlencode(ucwords(strtolower($SearchTerm)))."' />";

	//$head .= "\n<META NAME='ROBOTS' CONTENT='NOINDEX, NOFOLLOW'>\n";

	//Will be overwritten in functions
	$labels = "";

	$AmazonList = searchForItems($Category,$SearchTerm,$pagenum);
	$YoutubeVideos = GetYoutubeVideos($SearchTerm,null,"Android");
	$SearchTerm = ucwords(strtolower($SearchTerm));
	$linkCat = getAmazonLinkCat($SearchTerm);

	if( $Category == 'All' && $pagenum == 1 )
		$WikipediaSearch = GetWikipediaSearch($SearchTerm);
	else
		$WikipediaSearch = null;

	if( sizeof( explode(" ", $SearchTerm) ) == 1 && $Category == 'All' && $pagenum == 1 )
		$wordnet_def = getWordnetDef($SearchTerm);
	else
		$wordnet_def = null;

	$labels = getLabels($labels,3);

	if( $pagenum > 1 || $Category != 'All' ) {
		$YoutubeVideos = $WikipediaSearch = $wordnet_def = null;
		$head .= '<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">';
	}

	$customLabels = getArrLabels( Array("Android","8D8","Star Wars","DJ","Rap","Beats","Rap Beats","DJ Equipment","SmartPhone","Apps","Freestyle","Hip Hop","Instrumentals","Head Phones","Synthesizer","Studio","Recording","Recording Studio","Speakers","Microphone"), 25 );


	if( ! isset( $_GET['displayCat'] ) && ! isset( $_GET['category'] ) && ! isset( $_GET['pagenum'] ) ) {
		$content = <<<HTML

		<h1>$title</h1>

		$customLabels

HTML;

	} else if( $AmazonList == null ) {
		$head .= '<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">';
		$content = <<<HTML

			<script>
				function goBack() {
					window.history.back()
				}
			</script>
			<h1>NO RESULTS!</h1>
			<button onclick="goBack()">Go Back</button>

HTML;
	} else 
		$content = <<<HTML

			<h1>$Category &raquo; $SearchTerm &raquo; pg. $pagenum</h1>
			<div>
				$AmazonList
			</div>
			<div style='padding-bottom:30px;'>
				$wordnet_def
			</div>
			<div>
				$WikipediaSearch
			</div>
			<div>
				$YoutubeVideos
			</div>
			<div>
				$linkCat
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
<?PHP

	require_once 'local.php';
	require_once "../php/iplookup/dbip.class.php";

	$asin = isset( $_GET['item'] ) ? $_GET['item'] : '1602609667';
	$head = "<link rel='canonical' href='http://www.8d8apps.com/itemPage.php?item=$asin' />";

	if( $asin == 'B00OD4YG9U' )
		$asin = '1602609667';

	$item = getItem( $asin );

	$labels = "";

	//$head .= "\n<META NAME='ROBOTS' CONTENT='NOINDEX, NOFOLLOW'>\n";

	if( $item == null ) {
		$content = "NOT AVAILABLE";
	} else {

		require_once( 'lib/templates/amazon_item_template.php' );

		//List a sample of each content on this page...
		$AmazonList = searchForItems('All',$title,1);
		$YoutubeVideos = GetYoutubeVideos($title,null,"Android");
		$WikipediaSearch = GetWikipediaSearch($title);

		if( sizeof( explode(" ", $title) ) == 1 )
			$wordnet_def = getWordnetDef($title);
		else
			$wordnet_def = null;

		$labels = getLabels($labels,1);

		/*****
		 Content
		 *****/

		$content = <<<HTML

				<h1>$title <a rel='nofollow' style='margin-left:10px;' class="btn btn-lg btn-primary" href="$DetailPageURL" role="button">View Price &raquo;</a></h1>
				<br>
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
				  <!-- Wrapper for slides -->
				  <div class="carousel-inner">
					$ImageSets
				  </div>

				  <!-- Controls -->
				  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left"></span>
				  </a>
				  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right"></span>
				  </a>
				</div>
				<a rel='nofollow' style='margin-top:25px;margin-bottom:15px;margin-left:0px;' class="btn btn-lg btn-primary" href="$DetailPageURL" role="button">View Price &raquo;</a>
				<p>
					$ItemLinks
					[<a rel='nofollow' href="http://www.amazon.com/gp/aws/cart/add.html?AssociateTag=ezstbu-20&amp;SubscriptionId=15XGD9GB2J5T8BQHYE02&amp;ASIN.1=$asin&amp;Quantity.1=1">Add to Cart &raquo;</a>]
				</p>
				<p>
					$EditorialReview
				</p>
				$MiscellaneousTable<br>
				$Features
				$Tracks
				<div>
					$BrowseNodeLinks
				</div>
				<div>
					$SimilarProducts
				</div>
				<div>
					$AmazonList
				</div>
				<div>
					$WikipediaSearch
				</div>
				<div>
					$YoutubeVideos
				</div>
				<div>
					$wordnet_def
				</div>
				<div>
					$labels
				</div>

				$ipTag
				
HTML;
	}

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
<?PHP
	chdir("..");
	require_once 'local.php';
	require_once "../php/iplookup/dbip.class.php";

	$head = "<base href='..'>";
	$title = null;

	$asin = isset( $_GET['item'] ) ? $_GET['item'] : '1602609667';

	if( $asin == 'B00OD4YG9U' )
		$asin = '1602609667';

	$item = getItem( $asin );

	$labels = "";

	if( $item == null ) {
		$head .= "\n<META NAME='ROBOTS' CONTENT='NOINDEX, FOLLOW'>\n";
		$content = microSearchForItems('All',"Random Thoughts",1);
	} else {

		require_once( 'lib/templates/amazon_item_template.php' );

		//List a sample of each content on this page...
		$AmazonList = searchForItems('All',$title,1);
		$YoutubeVideos = GetYoutubeVideos($title);
		$WikipediaSearch = GetWikipediaSearch($title);

		if( sizeof( explode(" ", $title) ) == 1 )
			$wordnet_def = getWordnetDef($title);
		else
			$wordnet_def = null;
		$labels = getLabels($labels);

		/*****
		 Content
		 *****/


/*

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

*/

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
					[<a href="http://www.amazon.com/gp/aws/cart/add.html?AssociateTag=ezstbu-20&amp;SubscriptionId=15XGD9GB2J5T8BQHYE02&amp;ASIN.1=$asin&amp;Quantity.1=1">Add to Cart &raquo;</a>]
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
		
HTML;
	}

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
<?PHP

/*****
	Return HTML to play video for a specific entry ID
 *****/

function GetYoutubeVideo($EntryID)
{
	//TODO test $EntryID
	global $labels;
	global $title;

	$xml = checkCache("youtube","item",$EntryID);
	$json = null;
	if( ! is_null( $xml ) )
		$json = $xml->root->youtube;


	if( is_null( $json ) ) {
		$feedURL = "https://www.googleapis.com/youtube/v3/videos?id=$EntryID&key=AIzaSyBEyKLOPpZKjGXZtLQxM9cxTqoigSb6a8k&part=snippet,contentDetails,statistics,status&regionCode=US";
		$json = file_get_contents( $feedURL );
		$xml = new SimpleXMLElement('<root/>');
		$xml->addChild("youtube", htmlentities($json));

		addToCache( 'youtube', 'item', $EntryID, '', '', $xml->asXML() );
	}
	$json = json_decode( $json );

	if( ! $json )
		return null;

	if( ! $json->items )
		return null;

	$id = $json->items[0]->id;

	if( isset( $json->items[0]->snippet->title ) )
		$title = $json->items[0]->snippet->title;
	else
		$title = null;

	if( isset( $json->items[0]->snippet->description ) )
		$description = $json->items[0]->snippet->description;
	else
		$description = null;

	$description = str_replace("<a ","<a rel='nofollow' ", $description);

	$labels .= " $title $description";

	$GLOBALS['title'] = $title;

	// print record
	$return = <<<HTML

		<div>
			<img alt='Youtube Videos' style='max-width:100px;' src='img/Youtube.png'>
		</div>

		<div class="videoWrapper">
			<!-- Copy & Pasted from YouTube -->
			<iframe width="560" height="349" src="http://www.youtube.com/embed/$id?rel=0&amp;hd=1" style='border:none;' allowfullscreen></iframe>
		</div>

		<div>
			<p>
				$description
			</p>
		</div>

HTML;

	return $return;

}

/*****
	Return table of videos based on search term
 *****/

//TODO make into small & medium versions

function GetYoutubeVideos($SearchTerm,$PageToken=null,$variation="")
{
	global $labels;
	$search = urlencode("$variation ".$SearchTerm);

	if( ! is_null( $PageToken ) ) {
		$PageToken = "&pageToken=$PageToken";
		$feedURL = "https://www.googleapis.com/youtube/v3/search?q=$search&type=video&key=AIzaSyBEyKLOPpZKjGXZtLQxM9cxTqoigSb6a8k&part=snippet&maxResults=20&videoEmbeddable=true$PageToken&regionCode=US";
	} else {
		$feedURL = "https://www.googleapis.com/youtube/v3/search?q=$search&type=video&key=AIzaSyBEyKLOPpZKjGXZtLQxM9cxTqoigSb6a8k&part=snippet&maxResults=20&videoEmbeddable=true&regionCode=US";
	}

	$json = file_get_contents( $feedURL );

	if( ! $json )
		return null;

	$json = json_decode( $json );

	$urlSearch = str_replace( "%2F", "/", substr( urlencode( ucwords( strtolower( urldecode( $search ) ) ) ), 0, 1000 ));

	$RightPagination = $LeftPagination = null;
	if( isset( $json->nextPageToken ) ) {
		$RightPagination = "<a class='btn btn-lg btn-primary' href='youtube.php?q=$urlSearch&nextPageToken={$json->nextPageToken}' role='button'>More Videos &raquo;</a>";
	}
	if( isset( $json->prevPageToken ) ) {
		$LeftPagination = "<a class='btn btn-lg btn-primary' href='youtube.php?q=$urlSearch&prevPageToken={$json->prevPageToken}' role='button'>&laquo; Previous</a>";
	}

	$return = null;
	foreach ($json->items as $entry) {

		//Id
		$id = $entry->id->videoId;

		// get video thumbnail
		$thumbnail = $entry->snippet->thumbnails->default->url; 

		//Title
		$title = $entry->snippet->title;
		$urlTitle = urlencode( $title );

		$altTitle = str_replace("'", "", substr($title,0,100) );

		$description = $entry->snippet->description;

		$labels .= " $title $description";

		$return .= <<<HTML

				<ul class='list-group'>
					<li class='list-group-item'>
						<div>
							<a href='youtube.php?video=$id'>
								$title
							</a>
						</div>
						<a href='youtube.php?video=$id'>
							<img alt='$altTitle' style='float:left;max-width:50px;' src='$thumbnail'>
						</a>
						$description
						<div style='clear:both;'></div>
					</li>
				</ul>

HTML;

	}

	//$return .= $LeftPagination . $RightPagination;
	if( $return )
		$return = "<div><img alt='Youtube Videos' style='max-width:100px;' src='img/Youtube.png'></div>$return";
	return $return;
}


?>
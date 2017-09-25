<?PHP
	chdir("..");
	require 'local.php';

	$head = "<base href='..'>";
	//$head .= "\n<META NAME='ROBOTS' CONTENT='NOINDEX, NOFOLLOW'>\n";

	$nodeId = isset( $_GET['node'] ) ? $_GET['node'] : '171119';

	$node = getNode($nodeId);

	$Name = $BrowseNodeLinks = null;

	if( $node ) {
		unset( $node->OperationRequest );
		unset( $node->BrowseNodes->Request );
		unset( $node->BrowseNodes->BrowseNode->TopSellers );
		unset( $node->BrowseNodes->BrowseNode->NewReleases );

		$Name = clone $node->BrowseNodes->BrowseNode->Name;
		$BrowseNodeLinks = parseBrowseNodeLookup($node->BrowseNodes->BrowseNode);
		unset( $node->BrowseNodes->BrowseNode->Children );
		unset( $node->BrowseNodes->BrowseNode->Ancestors );

		$title = $Name;


		//TopSellers
		//http://www.amazon.com/gp/bestsellers/books/?ie=UTF8&camp=1789&creative=390957&linkCode=ur2&tag=ezstbu-20
		//http://www.amazon.com/gp/bestsellers/automotive/?ie=UTF8&camp=1789&creative=390957&linkCode=ur2&tag=ezstbu-20
		//http://www.amazon.com/gp/bestsellers/books/?ie=UTF8&tag=ezstbu-20

		//NewReleases
		//http://www.amazon.com/gp/new-releases/books/12290/?ie=UTF8&camp=1789&creative=390957&linkCode=ur2&tag=ezstbu-20

		//MostWishedFor
		//http://www.amazon.com/gp/most-wished-for/books/12290/?ie=UTF8&camp=1789&creative=390957&linkCode=ur2&tag=ezstbu-20

		//MostGifted
		//http://www.amazon.com/gp/most-gifted/books/12290/?ie=UTF8&camp=1789&creative=390957&linkCode=ur2&tag=ezstbu-20

		$x = 0;
		$TopSellers = $NewReleases = $MostGifted = $MostWishedFor = null;
		while(  $x < sizeof( $node->BrowseNodes->BrowseNode->TopItemSet ) ) {
			if( strval($node->BrowseNodes->BrowseNode->TopItemSet[$x]->Type) == "TopSellers" )
				$TopSellers = clone $node->BrowseNodes->BrowseNode->TopItemSet[$x]->TopItem;
			if( strval($node->BrowseNodes->BrowseNode->TopItemSet[$x]->Type) == "NewReleases" )
				$NewReleases = clone $node->BrowseNodes->BrowseNode->TopItemSet[$x]->TopItem;
			if( strval($node->BrowseNodes->BrowseNode->TopItemSet[$x]->Type) == "MostGifted" )
				$MostGifted = clone $node->BrowseNodes->BrowseNode->TopItemSet[$x]->TopItem;
			if( strval($node->BrowseNodes->BrowseNode->TopItemSet[$x]->Type) == "MostWishedFor" )
				$MostWishedFor = clone $node->BrowseNodes->BrowseNode->TopItemSet[$x]->TopItem;
			$x++;
		}

		//Top Sellers
		$TopSellersOut = null;
		if( $TopSellers != null )
			foreach( $TopSellers as $TopSeller ) {
				$TopSellersOut .= <<<AMAZONLIST
					<ul class="list-group">
						<li class="list-group-item">
							<a href="aws/itemPage.php?item={$TopSeller->ASIN}">{$TopSeller->Title}</a> <span class="glyphicon glyphicon-circle-arrow-right"></span>
						</li>
					</ul>
AMAZONLIST;
			}
		if( $TopSellersOut != null )
			$TopSellersOut = <<<AMAZONLIST
				<h2>Top Sellers</h2>
				<div style="margin-left:10px;">
					$TopSellersOut
				</div>
AMAZONLIST;

		//New Releases
		$NewReleasesOut = null;
		if( $NewReleases != null )
			foreach( $NewReleases as $NewRelease ) {
				$NewReleasesOut .= <<<AMAZONLIST
					<ul class="list-group">
						<li class="list-group-item">
							<a href="aws/itemPage.php?item={$NewRelease->ASIN}">{$NewRelease->Title}</a> <span class="glyphicon glyphicon-circle-arrow-right"></span>
						</li>
					</ul>
AMAZONLIST;
			}
		if( $NewReleasesOut != null )
			$NewReleasesOut = <<<AMAZONLIST
				<h2>New Releases</h2>
				<div style="margin-left:10px;">
					$NewReleasesOut
				</div>
AMAZONLIST;

		//Most Gifted
		$MostGiftedOut = null;
		if( $MostGifted != null )
			foreach( $MostGifted as $MostGift ) {
				$MostGiftedOut .= <<<AMAZONLIST
					<ul class="list-group">
						<li class="list-group-item">
							<a href="aws/itemPage.php?item={$MostGift->ASIN}">{$MostGift->Title}</a> <span class="glyphicon glyphicon-circle-arrow-right"></span>
						</li>
					</ul>
AMAZONLIST;
			}
		if( $MostGiftedOut != null )
			$MostGiftedOut = <<<AMAZONLIST
				<h2>Most Gifted</h2>
				<div style="margin-left:10px;">
					$MostGiftedOut
				</div>
AMAZONLIST;

		//Most Wished For
		$MostWishedForOut = null;
		if( $MostWishedFor != null )
			foreach( $MostWishedFor as $MostWished ) {
				$MostWishedForOut .= <<<AMAZONLIST
					<ul class="list-group">
						<li class="list-group-item">
							<a href="aws/itemPage.php?item={$MostWished->ASIN}">{$MostWished->Title}</a> <span class="glyphicon glyphicon-circle-arrow-right"></span>
						</li>
					</ul>
AMAZONLIST;
			}
		if( $MostWishedForOut != null )
			$MostWishedForOut = <<<AMAZONLIST
				<h2>Most Wished For</h2>
				<div style="margin-left:10px;">
					$MostWishedForOut
				</div>
AMAZONLIST;

	} else {
		$TopSellersOut = $NewReleasesOut = $MostWishedForOut = $MostGiftedOut = null;
	}
	//Get a wiki article about this topic...
	//Get some Youtube Videos...
	//Etc...

	$content = <<<HTML

			<h1>$Name</h1>

			<p>
				$BrowseNodeLinks
			</p>

			$TopSellersOut
			$NewReleasesOut
			$MostWishedForOut
			$MostGiftedOut

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

	//wtf( $node );

?>
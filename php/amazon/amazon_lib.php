<?php

/* Replace the value in quotes with your own Developer token */
define('TOKEN', "15XGD9GB2J5T8BQHYE02");

define('ASSOCID', "ezstbu-20");

/* My secret key from AWS */
define('SECRETKEY', "M2mkMg2ehL79IdB+WGh3CD8lpzkYoAz5K5PFZi1V");

function getAmazonLinkCat($word)
{
	$word = ucwords( strtolower($word) );

	$categories = Array("Apparel"=>"Apparel","Appliances"=>"Appliances","ArtsAndCrafts"=>"Arts and Crafts","Automotive"=>"Automotive","Baby"=>"Baby","Beauty"=>"Beauty","Blended"=>"Blended","Books"=>"Books","Classical"=>"Classical","Collectibles"=>"Collectibles","DigitalMusic"=>"Digital Music","DVD"=>"DVD","Electronics"=>"Electronics","GourmetFood"=>"Gourmet Food","Grocery"=>"Grocery","HealthPersonalCare"=>"Health and Personal Care","HomeGarden"=>"Home Garden","Industrial"=>"Industrial","Jewelry"=>"Jewelry","KindleStore"=>"Kindle Store","Kitchen"=>"Kitchen","LawnAndGarden"=>"Lawn and Garden","Magazines"=>"Magazines","Marketplace"=>"Market Place","Miscellaneous"=>"Miscellaneous","MobileApps"=>"Mobile Apps","MP3Downloads"=>"MP3 Downloads","Music"=>"Music","MusicalInstruments"=>"Musical Instruments","MusicTracks"=>"Music Tracks","OfficeProducts"=>"Office Products","OutdoorLiving"=>"Outdoor Living","PCHardware"=>"PC Hardware","PetSupplies"=>"Pet Supplies","Photo"=>"Photo","Shoes"=>"Shoes","Software"=>"Software","SportingGoods"=>"Sporting Goods","Tools"=>"Tools","Toys"=>"Toys","UnboxVideo"=>"Unbox Video","VHS"=>"VHS","Video"=>"Video","VideoGames"=>"Video Games","Watches"=>"Watches","Wireless"=>"Wireless","WirelessAccessories"=>"Wireless Accessories");	
	$catLinks = null;

	foreach( $categories as $cat => $hCat )
		$catLinks .= " [<a style='font-size:8pt;font-weight:normal;' href='storeFront.php?pagenum=1&amp;category=$cat&amp;displayCat=$word'>$hCat-&gt;$word</a>] ";

	return "<br><br>".$catLinks;
}

function repeatGetItems($category,$keywords,$pagenum)
{
	$items = requestSearch($category,$keywords,$pagenum);

	$escape = false;
	/*if( $items == null ) {
		sleep(5);
		$keywords = explode(" ",$keywords);
		if( sizeof( $keywords ) <= 1 )
			$escape = true;
		$keywords = array_slice($keywords,0,sizeof($keywords)/3);
		$keywords = implode(" ",$keywords);
		$items = requestSearch($category,$keywords,$pagenum);
	}
	if( ! $escape && $items == null ) {
		sleep(5);
		$keywords = explode(" ",$keywords);
		if( sizeof( $keywords ) <= 1 )
			$escape = true;
		$keywords = array_slice($keywords,0,sizeof($keywords)/3);
		$keywords = implode(" ",$keywords);
		$items = requestSearch($category,$keywords,$pagenum);
	}
	if( ! $escape && $items == null ) {
		sleep(5);
		$keywords = explode(" ",$keywords);
		if( sizeof( $keywords ) <= 1 )
			$escape = true;
		$keywords = array_slice($keywords,0,sizeof($keywords)/3);
		$keywords = implode(" ",$keywords);
		$items = requestSearch($category,$keywords,$pagenum);
	}*/
	if( ! $escape && $items == null ) {
		if( $_SERVER['SERVER_NAME'] === 'randomthoughts.club' || $_SERVER['SERVER_NAME'] === 'www.randomthoughts.club' )
			$keywords = 'random thoughts';
		else
			$keywords = 'dj equipment';

		$items = requestSearch($category,$keywords,$pagenum);
	}

	return $items;
}

function throttle()
{
	$last_hit = file_get_contents( 'cache/amazon/last_hit.txt' );

	if( time() - $last_hit < 2 )
		return true;

}

function setLastHit()
{
	file_put_contents( 'cache/amazon/last_hit.txt', time() );
}

/*****
	Searches for items based on category, keywords and pagination
	TODO make into small and medium versions
 *****/

function searchForItems($category,$keywords,$pagenum=1)
{
	global $labels;

	$items = repeatGetItems($category,$keywords,$pagenum);

	$AmazonList = null;
	$AmazonListCnt = 0;
	if( $items )
		foreach( $items->Item as $item ) {
			$AmazonListCnt++;
			$labels .= " ".$item->ItemAttributes->Title;
			
			//Can we get title & thumbnail with a smaller size API request?

//			wtf( $item );

			if( $item->SmallImage ) {
				$imgUrl = $item->SmallImage->URL;
			} else {
				$imgUrl = "img/noimage.gif";
			}

			$urlTitle = str_replace( "%2F", "/", substr( urlencode( ucwords( strtolower( $item->ItemAttributes->Title ) ) ), 0, 1000 ));
			$altTitle = str_replace( '"', '', urldecode($urlTitle) );

			$AmazonList .= <<<AMAZONLIST
			
				<ul class='list-group'>
					<li class='list-group-item'>
						<div>
							<a href="itemPage.php?item={$item->ASIN}">{$item->ItemAttributes->Title}</a>
							<!--<a href="{$item->DetailPageURL}"><span class="glyphicon glyphicon-circle-arrow-right"></span></a>-->
						</div>
						<a href="itemPage.php?item={$item->ASIN}"><img alt="View $altTitle Details" style="float:left;max-width:50px;" src="$imgUrl"></a>
						<div style='clear:both;'></div>
					</li>
				</ul>

AMAZONLIST;
	}

	$urlKeywords = str_replace( "%2F", "/", substr( urlencode( ucwords( strtolower( $keywords ) ) ), 0, 1000 ));

	if( $pagenum == 1 ) {
		$LeftPagination = null;
		$RightPagination = "<a class='btn btn-lg btn-primary' href='storeFront.php?pagenum=2&amp;category=$category&amp;displayCat=$urlKeywords' role='button'>More Items &raquo;</a>";
	} else if( $pagenum == 2 ) {
		$LeftPagination = "<a class='btn btn-lg btn-primary' href='storeFront.php?pagenum=1&amp;category=$category&amp;displayCat=$urlKeywords' role='button'>&laquo; Previous</a>";
		$RightPagination = "<a class='btn btn-lg btn-primary' href='storeFront.php?pagenum=3&amp;category=$category&amp;displayCat=$urlKeywords' role='button'>More Items &raquo;</a>";
	} else {
		$LeftPagination = "<a class='btn btn-lg btn-primary' href='storeFront.php?pagenum=".($pagenum-1)."&amp;category=$category&amp;displayCat=$urlKeywords' role='button'>&laquo; Previous</a>";
		$RightPagination = "<a class='btn btn-lg btn-primary' href='storeFront.php?pagenum=".($pagenum+1)."&amp;category=$category&amp;displayCat=$urlKeywords' role='button'>More Items &raquo;</a>";
	}

//	wtf( $AmazonListCnt );

	if( $AmazonListCnt < 9 )
		$RightPagination = null;
	if( $category == "All" && $pagenum == 5 )
		$RightPagination = null;
	if( $pagenum == 10 )
		$RightPagination = null;

	if( $AmazonList ) {
		$altKeywords = str_replace("'", "", $keywords);
		return "<br><img style='max-width:50px;max-height:50px;' src='img/shopping_basket_blue.png' alt='Shop for $altKeywords'><br><br>" . $AmazonList . $LeftPagination . $RightPagination . "<br><br>";
	}
	else
		return null;
}

function microSearchForItems($category,$keywords,$pagenum=1)
{
	global $labels;

	$items = repeatGetItems($category,$keywords,$pagenum);

	$AmazonList = null;
	$AmazonListCnt = 0;
	if( $items )
		foreach( $items->Item as $item ) {
			$AmazonListCnt++;
			$labels .= " ".$item->ItemAttributes->Title;
			
			//Can we get title & thumbnail with a smaller size API request?

//			wtf( $item );

			if( $item->SmallImage ) {
				$imgUrl = $item->SmallImage->URL;
			} else {
				$imgUrl = "img/noimage.gif";
			}

			$urlTitle = str_replace( "%2F", "/", substr( urlencode( ucwords( strtolower( $item->ItemAttributes->Title ) ) ), 0, 1000 ));
			$altTitle = str_replace( '"', '', urldecode($urlTitle) );
			$disTitle = substr( ucwords( strtolower( $item->ItemAttributes->Title ) ), 0, 15 );

			if( strlen( $item->ItemAttributes->Title ) > 15 )
				$disTitle = $disTitle . "...";

			$AmazonList .= <<<AMAZONLIST
			
						<div style="border:1px solid #DEDEDE;text-align:center;height:110px;float:left;width:19%;margin:1px;padding:1px;" >
							<a title="$altTitle" href="itemPage.php?item={$item->ASIN}"><img alt="View $altTitle Details" src="$imgUrl"></a>
							<br>
							<a style='font-size:8pt;line-height:none;display:inline-block;word-wrap:break-word;' title="$altTitle" href="itemPage.php?item={$item->ASIN}">$disTitle</a>
						</div>

AMAZONLIST;
	}

	return "$AmazonList<div style='clear:both;'></div>";
}

function requestSearch($category,$keywords,$pagenum)
{

    $keywords = stripslashes($keywords);

	$items = checkCache("amazon","search",$keywords,$pagenum,$category);
	if( $items )
		if( $items->Request->Errors->Error )
			return null;
		else
			return $items;

	if( throttle() ) {
		return null;
	}

    $array = array('Operation' => 'ItemSearch',
                   'ItemPage' => $pagenum,
                   'SearchIndex' => $category,
                   'ResponseGroup' => 'Medium',
                   'Keywords' => $keywords,
				   'AssociateTag' => 'ezstbu-20');

	$result = aws_signed_request("com", $array, TOKEN, SECRETKEY);

	setLastHit();

	if( $result ) {
		$items = $result->Items;
		addToCache("amazon","search",$keywords,$pagenum,$category,$items->asXML());
		if( ! $items->Request->Errors->Error )
			return $items;
	} else {
		//$e = new Exception();
		error_log( "throttled by aws for search '$keywords' -- " );
	}

	return null;
}

/*****
	Get all the data for a particular page
 *****/

function getItem($item)
{
	$cacheItem = checkCache("amazon","item",$item);
	if( $cacheItem )
		return $cacheItem;

	$array = array('Operation' => 'ItemLookup', 
				   'ResponseGroup' => 'Large',
				   'ItemId' => "$item", 
				   'AssociateTag' => 'ezstbu-20');
	
	if( throttle() ) {
		return null;
	}

	$result = aws_signed_request("com", $array ,TOKEN,SECRETKEY);

	setLastHit();

	if( ! $result ) {
		error_log( "throttled by aws for item '$item'" );
	} else {
		if( ! $result->Items->Request->Errors->Error ) {
			$itemData = $result->Items->Item;
			if( $itemData ){
				addToCache("amazon","item",$item,"","",$itemData->asXML());
				return $itemData;
			}
		}
	}

	return null;
}

/*****
	Get all the data for a particular node
 *****/

function getNode($node)
{
	$cacheItem = checkCache("amazon","node",$node);
	if( $cacheItem )
		return $cacheItem;

	$array = array('Operation' => 'BrowseNodeLookup',
				   'ResponseGroup' => 'BrowseNodeInfo,TopSellers,NewReleases,MostGifted,MostWishedFor',
				   'BrowseNodeId' => "$node", 
				   'AssociateTag' => 'ezstbu-20');

	if( throttle() ) {
		return null;
	}

	$result = aws_signed_request("com", $array ,TOKEN,SECRETKEY);

	setLastHit();

    if($result && ! $result->BrowseNodes->Request->Errors->Error){
        if( $result ){
			addToCache("amazon","node",$node,"","",$result->asXML());
			return $result;
		}
	}
	return null;
}

/*****
	Parses the data from a BrowseNodes element
 *****/

function parseBrowseNodeLookup($TopBrowseNode)
{
	$BrowseNodes = Array();
	if( $TopBrowseNode )
		foreach( $TopBrowseNode as $BrowseNode ) {
			$Ancestors = $BrowseNode->Ancestors;
			$Children = $BrowseNode->Children;

			//_____Current
			$urlTitle = urlencode( $BrowseNode->Name );
			if( ! isset( $BrowseNodes["{$BrowseNode->BrowseNodeId}"] ) ) {
				$BrowseNodes["{$BrowseNode->BrowseNodeId}"] = "[<a href='nodePage.php?node={$BrowseNode->BrowseNodeId}'>{$BrowseNode->Name}</a>]";
			}

			//_____Ancestors
			if( $Ancestors ) {
				while( true ) {
					if( ! isset( $BrowseNodes["{$Ancestors->BrowseNode->BrowseNodeId}"] ) ) {
						$urlTitle = urlencode( $Ancestors->BrowseNode->Name );
						$BrowseNodes["{$Ancestors->BrowseNode->BrowseNodeId}"] = " [<a href='nodePage.php?node={$Ancestors->BrowseNode->BrowseNodeId}'>{$Ancestors->BrowseNode->Name}</a>] ";
					}
					if( $Ancestors->BrowseNode->Ancestors ) {
						$Ancestors = $Ancestors->BrowseNode->Ancestors;
					} else {
						break;
					}
				}
			}

			//_____Children
			if( $Children ) {
				foreach( $Children->BrowseNode as $Child ) {
					if( ! isset( $BrowseNodes["{$Child->BrowseNodeId}"] ) ) {
						$urlTitle = urlencode( $Child->Name );
						$BrowseNodes["{$Child->BrowseNodeId}"] = "[<a href='nodePage.php?node={$Child->BrowseNodeId}'>{$Child->Name}</a>]";
					}
				}
			}
		}
	$BrowseNodeLinks = null;
	foreach( $BrowseNodes as $BrowseNode ) {
		$BrowseNodeLinks .= $BrowseNode . " ";
	}
	return $BrowseNodeLinks;
}

/*****
	Sign a request before we make it
 *****/

function aws_signed_request($region, $params, $public_key, $private_key)
{
    $method = "GET";
    $host = "ecs.amazonaws.".$region;
    $uri = "/onca/xml";
    
    // additional parameters
    $params["Service"] = "AWSECommerceService";
    $params["AWSAccessKeyId"] = $public_key;
    // GMT timestamp
    $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
    // API version
    $params["Version"] = "2013-08-01";
    
    // sort the parameters
    ksort($params);
    
    // create the canonicalized query
    $canonicalized_query = array();
    foreach ($params as $param=>$value) {
        $param = str_replace("%7E", "~", rawurlencode($param));
        $value = str_replace("%7E", "~", rawurlencode($value));
        $canonicalized_query[] = $param."=".$value;
    }
    $canonicalized_query = implode("&", $canonicalized_query);
    
    // create the string to sign
    $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
    // calculate HMAC with SHA256 and base64-encoding
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
    
    // encode the signature for the request
    $signature = str_replace("%7E", "~", rawurlencode($signature));
    
    // create request
    $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;
    //echo $request;
    // do request
    $response = @file_get_contents($request);
    //print_r( $response );
    if ($response === False) {
        return False;
    } else {
        // parse XML
        $pxml = simplexml_load_string($response);
        if ($pxml === False) {
            return False; // no xml
        } else {
            return $pxml;
        }
    }
}



?>
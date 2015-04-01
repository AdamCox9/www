<?PHP
	chdir("..");
	require_once 'local.php';

	$SearchTerm = isset( $_GET['displayCat'] ) ? $_GET['displayCat'] : null;
	$Category = isset( $_GET['category'] ) ? $_GET['category'] : null;
	$pagenum = isset( $_GET['pagenum'] ) ? $_GET['pagenum'] : 1;

	if( $Category == null && $SearchTerm == null )
		$title = "Store Front";
	else
		$title = "Store Front - $SearchTerm - $Category - $pagenum";

	$head = "<base href='..'>";
	$head .= "<link rel='canonical' href='http://randomthoughts.club/aws/storeFront.php?pagenum=$pagenum&amp;category=$Category&amp;displayCat=".urlencode(ucwords(strtolower($SearchTerm)))."' />";

	//Will be overwritten in functions
	$labels = "";

	$AmazonList = searchForItems($Category,$SearchTerm,$pagenum);
	$YoutubeVideos = GetYoutubeVideos($SearchTerm,null);
	$SearchTerm = ucwords(strtolower($SearchTerm));
	$linkCat = getAmazonLinkCat($SearchTerm);

	if( strpos($AmazonList,'1404847855') && strpos($AmazonList,'B007SUW24W') && strpos($AmazonList,'B00JKGYSAI') ) {
		$head .= '<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">';
		$linkCat = null;
	}

	
	if( $Category == 'All' && $pagenum == 1 )
		$WikipediaSearch = GetWikipediaSearch($SearchTerm);
	else
		$WikipediaSearch = null;

	if( sizeof( explode(" ", $SearchTerm) ) == 1 && $Category == 'All' && $pagenum == 1 )
		$wordnet_def = getWordnetDef($SearchTerm);
	else
		$wordnet_def = null;

	$labels = getLabels($labels);

	if( $pagenum > 1 || $Category != 'All' ) {
		$YoutubeVideos = $WikipediaSearch = $wordnet_def = null;
		$head .= '<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">';
	}

	if( ! isset( $_GET['displayCat'] ) && ! isset( $_GET['category'] ) && ! isset( $_GET['pagenum'] ) ) {
		$content = <<<HTML

		<h1>$title</h1>

		<div>
			<div class='samplesearches'>
				<b class='populr'>Apparel:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Sunglasses" href="aws/storeFront.php?pagenum=1&amp;category=Apparel&amp;displayCat=Sunglasses">Sunglasses</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Lingerie" href="aws/storeFront.php?pagenum=1&amp;category=Apparel&amp;displayCat=Lingerie">Lingerie</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Baby Products:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Toys" href="aws/storeFront.php?pagenum=1&amp;category=Baby&amp;displayCat=Toys">Toys</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Stroller" href="aws/storeFront.php?pagenum=1&amp;category=Baby&amp;displayCat=Stroller">Stroller</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Beauty:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Makeup" href="aws/storeFront.php?pagenum=1&amp;category=Beauty&amp;displayCat=Makeup">Makeup</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Lipstick" href="aws/storeFront.php?pagenum=1&amp;category=Beauty&amp;displayCat=Lipstick">Lipstick</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Books:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Politics" href="aws/storeFront.php?pagenum=1&amp;category=Books&amp;displayCat=Politics">Politics</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Economics" href="aws/storeFront.php?pagenum=1&amp;category=Books&amp;displayCat=Economics">Economics</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>DVD:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for House" href="aws/storeFront.php?pagenum=1&amp;category=DVD&amp;displayCat=House">House</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Harry Potter" href="aws/storeFront.php?pagenum=1&amp;category=DVD&amp;displayCat=Harry+Potter">Harry Potter</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Electronics:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Car Stereo" href="aws/storeFront.php?pagenum=1&amp;category=Electronics&amp;displayCat=Car+Stereo">Car Stereo</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for GPS Navigator" href="aws/storeFront.php?pagenum=1&amp;category=Electronics&amp;displayCat=GPS+Navigator">GPS Navigator</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Gourmet Food:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Gift Basket" href="aws/storeFront.php?pagenum=1&amp;category=GourmetFood&amp;displayCat=Gift+Basket">Gift Basket</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Spices" href="aws/storeFront.php?pagenum=1&amp;category=GourmetFood&amp;displayCat=Spices">Spices</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Health &amp; Personal Care:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Vitamins" href="aws/storeFront.php?pagenum=1&amp;category=HealthPersonalCare&amp;displayCat=Vitamins">Vitamins</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Herbal" href="aws/storeFront.php?pagenum=1&amp;category=HealthPersonalCare&amp;displayCat=Herbal">Herbal</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Jewelry:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Gold Chain" href="aws/storeFront.php?pagenum=1&amp;category=Jewelry&amp;displayCat=Gold+Chain">Gold Chain</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Silver Necklace" href="aws/storeFront.php?pagenum=1&amp;category=Jewelry&amp;displayCat=Silver+Necklace">Silver Necklace</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Music:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for The Doors" href="aws/storeFront.php?pagenum=1&amp;category=Music&amp;displayCat=The+Doors">The Doors</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for AC/DC" href="aws/storeFront.php?pagenum=1&amp;category=Music&amp;displayCat=AC/DC">AC/DC</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Musical Instruments:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Guitars" href="aws/storeFront.php?pagenum=1&amp;category=MusicalInstruments&amp;displayCat=Guitar">Guitar</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Saxophone" href="aws/storeFront.php?pagenum=1&amp;category=MusicalInstruments&amp;displayCat=Saxophone">Saxophone</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Office Products:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Desk" href="aws/storeFront.php?pagenum=1&amp;category=OfficeProducts&amp;displayCat=Desk">Desk</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Stapler" href="aws/storeFront.php?pagenum=1&amp;category=OfficeProducts&amp;displayCat=Stapler">Stapler</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Pet Supplies:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Flea Control" href="aws/storeFront.php?pagenum=1&amp;category=PetSupplies&amp;displayCat=Flea+Control">Flea Control</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Collar" href="aws/storeFront.php?pagenum=1&amp;category=PetSupplies&amp;displayCat=Collar">Collar</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Shoes:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Boots" href="aws/storeFront.php?pagenum=1&amp;category=Shoes&amp;displayCat=Boots">Boots</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Nike" href="aws/storeFront.php?pagenum=1&amp;category=Shoes&amp;displayCat=Nike">Nike</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Software:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Poker" href="aws/storeFront.php?pagenum=1&amp;category=Software&amp;displayCat=Poker">Poker</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Games" href="aws/storeFront.php?pagenum=1&amp;category=Software&amp;displayCat=Games">Games</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Sporting Goods:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Football" href="aws/storeFront.php?pagenum=1&amp;category=SportingGoods&amp;displayCat=Football">Football</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Baseball" href="aws/storeFront.php?pagenum=1&amp;category=SportingGoods&amp;displayCat=Baseball">Baseball</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Tools:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Wrench Set" href="aws/storeFront.php?pagenum=1&amp;category=Tools&amp;displayCat=Wrench+Set">Wrench Set</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Workbench" href="aws/storeFront.php?pagenum=1&amp;category=Tools&amp;displayCat=Workbench">Workbench</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Toys:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Power Wheels" href="aws/storeFront.php?pagenum=1&amp;category=Toys&amp;displayCat=Power+Wheels">Power Wheels</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Educational" href="aws/storeFront.php?pagenum=1&amp;category=Toys&amp;displayCat=Educational">Educational</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Video Games:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Wii" href="aws/storeFront.php?pagenum=1&amp;category=VideoGames&amp;displayCat=Wii">Wii</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for xBox" href="aws/storeFront.php?pagenum=1&amp;category=VideoGames&amp;displayCat=xBox">xBox</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Watches:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Leather" href="aws/storeFront.php?pagenum=1&amp;category=Watches&amp;displayCat=Leather">Leather</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for Movado" href="aws/storeFront.php?pagenum=1&amp;category=Watches&amp;displayCat=Movado">Movado</a>
					</li>
				</ul>
			</div>
			<div class='samplesearches'>
				<b class='populr'>Wireless:</b>
				<ul class='populr'>
					<li class='populr'>
						<a class="nav2" title="Search for Phone Charger" href="aws/storeFront.php?pagenum=1&amp;category=WirelessAccessories&amp;displayCat=Phone+Charger">Phone Charger</a>
					</li>
					<li class='populr'>
						<a class="nav2" title="Search for iPhone" href="aws/storeFront.php?pagenum=1&amp;category=Wireless&amp;displayCat=iPhone">iPhone</a>
					</li>
				</ul>
			</div>
		</div>
		<div style='clear:both;'>
		</div>

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
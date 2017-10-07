<?PHP

	unset( $item->CustomerReviews );
	unset( $item->Offers );
	unset( $item->OfferSummary );
	unset( $item->ASIN );

	$DetailPageURL = isset( $item->DetailPageURL ) ? clone $item->DetailPageURL : "";
	unset( $item->DetailPageURL );

	$title = isset( $item->ItemAttributes ) && isset( $item->ItemAttributes->Title ) ? clone $item->ItemAttributes->Title : "";
	unset( $item->ItemAttributes->Title );
	$labels .= " ".$title;

	/*****
	 ItemLinks
	 *****/

	$ItemLinks = null;
	if( $item->ItemLinks ) {
		foreach( $item->ItemLinks->ItemLink as $ItemLink ) {
			$URL = $ItemLink->URL;
			$Description = $ItemLink->Description;
			$ItemLinks .= " [<a rel='nofollow' href='$URL'>$Description &raquo;</a>] ";
			$labels .= " ".$Description;
		}
		unset( $item->ItemLinks );
	}

	/*****
	 BrowseNodes
	 *****/

	$BrowseNodeLinks = parseBrowseNodeLookup($item->BrowseNodes->BrowseNode);
	unset( $item->BrowseNodes );

	/*****
	 Images
	 *****/

	$ImageSets = null;
	$MaxHeight = 0;
	if( isset( $item->ImageSets ) ) {
		foreach( $item->ImageSets->ImageSet as $ImageSet ) {
			if( intval($ImageSet->LargeImage->Height) > $MaxHeight ) {
				$MaxHeight = intval($ImageSet->LargeImage->Height);
			}
		}
		foreach( $item->ImageSets->ImageSet as $ImageSet ) {
			if( $ImageSet->attributes()->Category == "primary" )
				$primary = " active";
			else
				$primary = "";
			$ImageSets .= <<<CAROUSEL
				<div class="item$primary">
				  <div class="inner-item">
					<div style="text-align:center;min-height:{$MaxHeight}px;">
						<a rel='nofollow' href="$DetailPageURL">
							<img alt="Item Picture" src="{$ImageSet->LargeImage->URL}">
						</a>
					</div>
				  </div>
				</div>
CAROUSEL;
		}
	}
	unset( $item->ImageSets );
	unset( $item->SmallImage );
	unset( $item->MediumImage );
	unset( $item->LargeImage );

	/*****
	 Similar Products
	 *****/

	if( $item->SimilarProducts ) {
		$SimilarProducts = "<ul>";
		foreach( $item->SimilarProducts->SimilarProduct as $SimilarProduct ) {
			$SP_ASIN = $SimilarProduct->ASIN;
			$SimilarProducts .= "<li><a href='itemPage.php?item=$SP_ASIN'>{$SimilarProduct->Title}</a></li>";
			$labels .= " ".$SimilarProduct->Title;
		}
		$SimilarProducts .= "</ul>";
		$SimilarProducts = "<h2>Similar Products</h2>$SimilarProducts";
		unset( $item->SimilarProducts );
	} else {
		$SimilarProducts = null;
	}

	/*****
	 Editorial Reviews
	 *****/

	$EditorialReview = null;
	if( $item->EditorialReviews ) {
		$EditorialReview = clone $item->EditorialReviews->EditorialReview->Content;
		$labels .= " ".$EditorialReview;
	}
	unset( $item->EditorialReviews );

	/*****
	 Tracks
	 *****/

	$Tracks = null;
	if( $item->Tracks ) {
		$Tracks = "<h2>Tracks</h2><ul>";
		foreach( $item->Tracks->Disc->Track as $Track ) {
			$Tracks .= "<li>$Track</li>";
			$labels .= " ".$Track;
		}
		$Tracks .= "</ul>";
		unset( $item->Tracks );
	}

	/*****
	 Features
	 *****/

	$Features = null;
	if( $item->ItemAttributes->Feature ) {
		$Features = "<ul>";
		foreach( $item->ItemAttributes->Feature as $Feature ) {
			$Features .= "<li>$Feature</li>";
			$labels .= " ".$Feature;
		}
		$Features .= "</ul>";
		unset( $item->ItemAttributes->Feature );
	}

	/*****
	 Miscellaneous
	 *****/

	unset( $item->ItemAttributes->EANList );
	unset( $item->ItemAttributes->ItemDimensions );
	unset( $item->ItemAttributes->Languages );
	unset( $item->ItemAttributes->ListPrice );
	unset( $item->ItemAttributes->NumberOfItems );
	unset( $item->ItemAttributes->NumberOfPages );
	unset( $item->ItemAttributes->PackageDimensions );
	unset( $item->ItemAttributes->ProductTypeName );
	unset( $item->ItemAttributes->IsEligibleForTradeIn );
	unset( $item->ItemAttributes->TradeInValue );
	unset( $item->SalesRank );
	unset( $item->ItemAttributes->UPCList );
	unset( $item->ItemAttributes->IsAutographed );
	unset( $item->ItemAttributes->IsMemorabilia );
	unset( $item->ItemAttributes->IsAdultProduct );
	unset( $item->ItemAttributes->RegionCode );
	unset( $item->ItemAttributes->ManufacturerMaximumAge );
	unset( $item->ItemAttributes->ManufacturerMinimumAge );

	$MiscellaneousTable = "<table class='ItemAttributes' style='width:100%;'>";
	if($item->ItemAttributes->Author) {
		$MiscellaneousTable .= "<tr><td>Author</td><td>".clone $item->ItemAttributes->Author."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Author;
		unset( $item->ItemAttributes->Author );
	}
	if($item->ItemAttributes->Binding) {
		$MiscellaneousTable .= "<tr><td>Binding</td><td>".clone $item->ItemAttributes->Binding."</td></tr>";
		unset( $item->ItemAttributes->Binding );
	}
	if($item->ItemAttributes->EAN) {
		$MiscellaneousTable .= "<tr><td>EAN</td><td>".clone $item->ItemAttributes->EAN."</td></tr>";
		$labels .= " ".$item->ItemAttributes->EAN;
		unset( $item->ItemAttributes->EAN );
	}
	if($item->ItemAttributes->ISBN) {
		$MiscellaneousTable .= "<tr><td>ISBN</td><td>".clone $item->ItemAttributes->ISBN."</td></tr>";
		$labels .= " ".$item->ItemAttributes->ISBN;
		unset( $item->ItemAttributes->ISBN );
	}
	if($item->ItemAttributes->SKU) {
		$MiscellaneousTable .= "<tr><td>SKU</td><td>".clone $item->ItemAttributes->SKU."</td></tr>";
		$labels .= " ".$item->ItemAttributes->SKU;
		unset( $item->ItemAttributes->SKU );
	}
	if($item->ItemAttributes->Label) {
		$MiscellaneousTable .= "<tr><td>Label</td><td>".clone $item->ItemAttributes->Label."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Label;
		unset( $item->ItemAttributes->Label );
	}
	if($item->ItemAttributes->Manufacturer) {
		$MiscellaneousTable .= "<tr><td>Manufacturer</td><td>".clone $item->ItemAttributes->Manufacturer."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Manufacturer;
		unset( $item->ItemAttributes->Manufacturer );
	}
	if($item->ItemAttributes->ProductGroup) {
		$MiscellaneousTable .= "<tr><td>Product Group</td><td>".clone $item->ItemAttributes->ProductGroup."</td></tr>";
		$labels .= " ".$item->ItemAttributes->ProductGroup;
		unset( $item->ItemAttributes->ProductGroup );
	}
	if($item->ItemAttributes->Publisher) {
		$MiscellaneousTable .= "<tr><td>Publisher</td><td>".clone $item->ItemAttributes->Publisher."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Publisher;
		unset( $item->ItemAttributes->Publisher );
	}
	if($item->ItemAttributes->Studio) {
		$MiscellaneousTable .= "<tr><td>Studio</td><td>".clone $item->ItemAttributes->Studio."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Studio;
		unset( $item->ItemAttributes->Studio );
	}
	if($item->ItemAttributes->PublicationDate) {
		$MiscellaneousTable .= "<tr><td>Publication Date</td><td>".clone $item->ItemAttributes->PublicationDate."</td></tr>";
		unset( $item->ItemAttributes->PublicationDate );
	}
	if($item->ItemAttributes->Creator) {
		$MiscellaneousTable .= "<tr><td>Creator</td><td>".clone $item->ItemAttributes->Creator."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Creator;
		unset( $item->ItemAttributes->Creator );
	}
	if($item->ItemAttributes->Director) {
		$MiscellaneousTable .= "<tr><td>Director</td><td>".clone $item->ItemAttributes->Director."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Director;
		unset( $item->ItemAttributes->Director );
	}
	if($item->ItemAttributes->Format) {
		$MiscellaneousTable .= "<tr><td>Format</td><td>".clone $item->ItemAttributes->Format."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Format;
		unset( $item->ItemAttributes->Format );
	}
	if($item->ItemAttributes->ReleaseDate) {
		$MiscellaneousTable .= "<tr><td>Release Date</td><td>".clone $item->ItemAttributes->ReleaseDate."</td></tr>";
		unset( $item->ItemAttributes->ReleaseDate );
	}
	if($item->ItemAttributes->Edition) {
		$MiscellaneousTable .= "<tr><td>Edition</td><td>".clone $item->ItemAttributes->Edition."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Edition;
		unset( $item->ItemAttributes->Edition );
	}
	if($item->ItemAttributes->PackageQuantity) {
		$MiscellaneousTable .= "<tr><td>Package Quantity</td><td>".clone $item->ItemAttributes->PackageQuantity."</td></tr>";
		unset( $item->ItemAttributes->PackageQuantity );
	}
	if($item->ItemAttributes->Brand) {
		$MiscellaneousTable .= "<tr><td>Brand</td><td>".clone $item->ItemAttributes->Brand."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Brand;
		unset( $item->ItemAttributes->Brand );
	}
	if($item->ItemAttributes->Model) {
		$MiscellaneousTable .= "<tr><td>Model</td><td>".clone $item->ItemAttributes->Model."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Model;
		unset( $item->ItemAttributes->Model );
	}
	if($item->ItemAttributes->MPN) {
		$MiscellaneousTable .= "<tr><td>MPN</td><td>".clone $item->ItemAttributes->MPN."</td></tr>";
		$labels .= " ".$item->ItemAttributes->MPN;
		unset( $item->ItemAttributes->MPN );
	}
	if($item->ItemAttributes->PartNumber) {
		$MiscellaneousTable .= "<tr><td>Part Number</td><td>".clone $item->ItemAttributes->PartNumber."</td></tr>";
		$labels .= " ".$item->ItemAttributes->PartNumber;
		unset( $item->ItemAttributes->PartNumber );
	}
	if($item->ItemAttributes->UPC) {
		$MiscellaneousTable .= "<tr><td>UPC</td><td>".clone $item->ItemAttributes->UPC."</td></tr>";
		$labels .= " ".$item->ItemAttributes->UPC;
		unset( $item->ItemAttributes->UPC );
	}
	if($item->ItemAttributes->Color) {
		$MiscellaneousTable .= "<tr><td>Color</td><td>".clone $item->ItemAttributes->Color."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Color;
		unset( $item->ItemAttributes->Color );
	}
	if($item->ItemAttributes->ManufacturerPartsWarrantyDescription) {
		$MiscellaneousTable .= "<tr><td>Warranty Description</td><td>".clone $item->ItemAttributes->ManufacturerPartsWarrantyDescription."</td></tr>";
		unset( $item->ItemAttributes->ManufacturerPartsWarrantyDescription );
	}
	if($item->ItemAttributes->Warranty) {
		$MiscellaneousTable .= "<tr><td>Warranty</td><td>".clone $item->ItemAttributes->Warranty."</td></tr>";
		unset( $item->ItemAttributes->Warranty );
	}
	if($item->ItemAttributes->ClothingSize) {
		$MiscellaneousTable .= "<tr><td>Clothing Size</td><td>".clone $item->ItemAttributes->ClothingSize."</td></tr>";
		$labels .= " ".$item->ItemAttributes->ClothingSize;
		unset( $item->ItemAttributes->ClothingSize );
	}
	if($item->ItemAttributes->Size) {
		$MiscellaneousTable .= "<tr><td>Size</td><td>".clone $item->ItemAttributes->Size."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Size;
		unset( $item->ItemAttributes->Size );
	}
	if($item->ItemAttributes->CatalogNumberList) {
		$MiscellaneousTable .= "<tr><td>Catalog Number List</td><td>".clone $item->ItemAttributes->CatalogNumberList->CatalogNumberListElement."</td></tr>";
		unset( $item->ItemAttributes->CatalogNumberList );
	}
	if($item->ItemAttributes->AudienceRating) {
		$MiscellaneousTable .= "<tr><td>Audience Rating</td><td>".clone $item->ItemAttributes->AudienceRating."</td></tr>";
		$labels .= " ".$item->ItemAttributes->AudienceRating;
		unset( $item->ItemAttributes->AudienceRating );
	}
	if($item->ItemAttributes->Genre) {
		$MiscellaneousTable .= "<tr><td>Genre</td><td>".clone $item->ItemAttributes->Genre."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Genre;
		unset( $item->ItemAttributes->Genre );
	}
	if($item->ItemAttributes->NumberOfDiscs) {
		$MiscellaneousTable .= "<tr><td>Number Of Discs</td><td>".clone $item->ItemAttributes->NumberOfDiscs."</td></tr>";
		unset( $item->ItemAttributes->NumberOfDiscs );
	}
	if($item->ItemAttributes->Actor) {
		$Actors = null;
		foreach( $item->ItemAttributes->Actor as $Actor )
			$Actors .= "$Actor, ";
		$Actors = substr( $Actors, 0, strlen($Actors)-2 );
		$labels .= " ".$Actors;
		$MiscellaneousTable .= "<tr><td>Actors</td><td>$Actors</td></tr>";
		unset( $item->ItemAttributes->Actor );
	}
	if($item->ItemAttributes->RunningTime) {
		$RunningTime = number_format($item->ItemAttributes->RunningTime / 60,2);
		if( $RunningTime < 20 )
			$TimeUnit = "Hours";
		else
			$TimeUnit = "Minutes";
		$MiscellaneousTable .= "<tr><td>Running Time</td><td>$RunningTime $TimeUnit</td></tr>";
		unset( $item->ItemAttributes->RunningTime );
	}
	if($item->ParentASIN) {
		$MiscellaneousTable .= "<tr><td>Parent</td><td><a href='itemPage.php?item=".clone $item->ParentASIN."'>".clone $item->ParentASIN."</a></td></tr>";
		unset( $item->ParentASIN );
	}
	if($item->Accessories) {
		$MiscellaneousTable .= "<tr><td>Accessories</td><td><a href='itemPage.php?item=".clone $item->Accessories->Accessory->ASIN."'>".clone $item->Accessories->Accessory->Title."</a></td></tr>";
		$labels .= " ".$item->Accessories->Accessory->Title;
		unset( $item->Accessories );
	}
	if($item->ItemAttributes->AspectRatio) {
		$MiscellaneousTable .= "<tr><td>Aspect Ratio</td><td>".clone $item->ItemAttributes->AspectRatio."</td></tr>";
		$labels .= " ".$item->ItemAttributes->AspectRatio;
		unset( $item->ItemAttributes->AspectRatio );
	}
	if($item->ItemAttributes->Artist) {
		$MiscellaneousTable .= "<tr><td>Artist</td><td>".clone $item->ItemAttributes->Artist."</td></tr>";
		$labels .= " ".$item->ItemAttributes->Artist;
		unset( $item->ItemAttributes->Artist );
	}
	if($item->ItemAttributes->PictureFormat) {
		$MiscellaneousTable .= "<tr><td>Picture Format</td><td>".clone $item->ItemAttributes->PictureFormat."</td></tr>";
		unset( $item->ItemAttributes->PictureFormat );
	}
	if($item->ItemAttributes->LegalDisclaimer) {
		$MiscellaneousTable .= "<tr><td>Legal Disclaimer</td><td>".clone $item->ItemAttributes->LegalDisclaimer."</td></tr>";
		unset( $item->ItemAttributes->LegalDisclaimer );
	}
	if($item->ItemAttributes->Department) {
		$MiscellaneousTable .= "<tr><td>Department</td><td>".clone $item->ItemAttributes->Department."</td></tr>";
		unset( $item->ItemAttributes->Department );
	}
	if($item->ItemAttributes->ItemPartNumber) {
		$MiscellaneousTable .= "<tr><td>Item Part Number</td><td>".clone $item->ItemAttributes->ItemPartNumber."</td></tr>";
		unset( $item->ItemAttributes->ItemPartNumber );
	}
	$MiscellaneousTable .= "</table>";

?>
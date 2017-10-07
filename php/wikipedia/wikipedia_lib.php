<?PHP

function GetWikipediaSearch($SearchTerm)
{
	global $labels;

	if( strlen( $SearchTerm ) > 50 || substr_count( $SearchTerm, '+' ) > 2 )
		return null;

	$xml = checkCache("wikipedia","search",$SearchTerm);

	if( ! $xml ) {
		$xml = simplexml_load_file('http://en.wikipedia.org/w/api.php?action=opensearch&search='.urlencode($SearchTerm).'&format=xml&callback=spellcheck');
		addToCache("wikipedia","search",$SearchTerm,'','',$xml->asXML());
	}

	$content = NULL;
	
	if( $xml && is_object( $xml ) && $xml != null )
		foreach( $xml->Section->Item as $Item ) {
			$labels .= " ".$Item->Text." ".$Item->Description;

			$Url = "storeFront.php?pagenum=1&amp;category=All&amp;displayCat=" . urlencode(ucwords(strtolower($Item->Text)));

			if( isset( $Item->Image ) )
				$image = $Item->Image->attributes()->source;
			else
				$image = "img/noimage.gif";

			$itemTitle = $Item->Text;
			$altItemTitle = str_replace('"', '', $itemTitle);
			
			//print_r( strpos( $Item->Description, " may refer to " ) );

			if( strpos( $Item->Description, "may refer to" ) === false && strpos( $Item->Description, "$itemTitle may be" ) === false )
				$content .= <<<HTML
					<ul class='list-group'>
						<li class='list-group-item'>
							<a href="$Url">$itemTitle</a>
							<div>
								<img alt="Learn about $altItemTitle" style='float:left;max-width:50px;' src='$image'>
							</div>
							<div>{$Item->Description} <a href="{$Item->Url}">Learn about $itemTitle&raquo;</a></div>
							<div style='height:15px;clear:both;'></div>
						</li>
					</ul>
HTML;

		}
	
	if( $content )
		return "<img alt='Wikipedia Info' src='img/wikipedia.png'><br><br>$content";
	
	return null;
}

?>
<?php

function getWordnetDef($words)
{
	global $labels;

	$words = explode( " ", $words );

	$word_def = null;
	foreach( $words as $word ) {

		$output = array();

		$shWord = escapeshellarg( $word );
		$cmd = "../php/wordnet/Wordnet30/src/wn $shWord -over";

		exec( $cmd, $output );
	
		$cnt = 0;
		foreach( $output as $line ) {
			$labels .= " " . $line;
			$line = str_replace("  ", "&nbsp;&nbsp;", $line);
			if( substr($line,0,8) == "Overview" ) {
				$line = "<b>$line</b>";
			}
			$word_def .= $line."<br>";
			$cnt++;
		}
		/*if( $cnt > 0 ) {
			$word_def .= "[<a href='/wnt/item/$word'>$word</a>]<br>";
			$cnt = 0;
		}*/
	}

	$words = implode(" ", $words);

	if( $word_def )
		return <<<HTML

			<br>
			<br>
			<ul class='list-group'>
				<li class='list-group-item' style='min-height:150px;'>
					$word_def
				</li>
			</ul>

HTML;
}

?>
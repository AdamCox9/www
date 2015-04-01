<?php

$x = 0;
$c = file_get_contents( "TWL06.txt" );
$c = explode( "\n", $c );

foreach( $c as $b ) {
	$d[substr( $b, 0, 1 )][$x++] = $b;
}

foreach( $d as $b => $f ) {
	file_put_contents( "$b.txt", implode( "\n", $f ) );
}

?>
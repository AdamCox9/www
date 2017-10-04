<?php

	ini_set( "error_reporting", "on" );
	error_reporting( E_ALL );

	require_once '../php/common.php';

	$template = file_get_contents( 'template.html' );

	$randomId = file_get_contents( '../php/thoughts/randomids/ids.txt' );
	$randomId = explode( "\n", $randomId );

	$template = str_replace( '<!--[[[~RANDOMID~]]]-->', $randomId[rand(0,sizeof($randomId))], $template );

	$GLOBALS['db_name'] = 'randomthoughts';

?>
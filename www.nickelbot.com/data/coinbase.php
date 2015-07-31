<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	$data = '{"test":"fun","fun":"test"}';
	
	$output = Array();

	$data = exec( 'node ../../js/nickelbot/coinbase/worth.js', $output );

	header('Content-Type: application/json');
	echo json_encode($output);

?>
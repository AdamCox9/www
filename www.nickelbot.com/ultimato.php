<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}

	require_once( "config.php" );

	foreach( $Adapters as $Adapter ) {
		try{
			$result = $Adapter->cancel_all();
			var_dump( $result );
			$result = $Adapter->buy('btcusd','1','5','limit');
			var_dump( $result );
			$result = $Adapter->sell('btcusd','1','500','limit');
			var_dump( $result );
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}

?> 
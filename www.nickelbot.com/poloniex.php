<?PHP

	require_once( "../php/poloniex/poloniex_lib.php" );

	$api_key = 'LWQRJN6Q-X3LS0IPC-42H0I72F-J8N624TD';
	$api_secret = 'ca7bfe0e1f1f98b37a4f6eba8aba9ec69d663c01dca46cd3792fca9e53de545e2e25ee95fec0d35f91d0299bb7f83bbb0cd066c2b0fa5349876ae5d74fd44d4d';

	$Poloniex = new poloniex($api_key, $api_secret);

	//print_r( $Poloniex->get_balances() );

	$trading_pairs = $Poloniex->get_ticker();

	//_____Cancel all orders:
	/*foreach( $trading_pairs as $key => $trading_pair ) {
		$open_orders = $Poloniex->get_open_orders($key);
		//$Poloniex->cancel_order($pair, $order_number)
	}*/

	$btc_cost = 0;
	//_____Make buy orders for highest bid placed:
	foreach( $trading_pairs as $key => $trading_pair ) {
		$x = 99;
		while( $x > 82 ) {
			$rate = bcmul($trading_pair['highestBid'],$x/100,32);
			$amount = bcdiv((100-$x)*0.0001,$rate,32);
			$btc_cost = $btc_cost + (100-$x)*0.0001;
			echo "buying $key for $rate with a total of $amount\n";
			$x = $x - 2;
			print_r( $Poloniex->buy($key,$rate,$amount) );
		}
	}
	echo "\n\nThe total cost is $btc_cost bitcoins.\n\n";

	//_____Make buy orders for smallest bid possible:
	/*foreach( $trading_pairs as $key => $trading_pair ) {
		$rate = '0.00000003';
		$amount = '25000';
		echo "buying $key for $rate with a total of $amount\n";
		print_r( $Poloniex->buy($key,$rate,$amount) );
	}*/

?>
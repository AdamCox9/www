<?PHP

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );

	/*if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '76.24.176.23' ) {
		if( ! isset( $_SERVER['SSH_CONNECTION'] ) || $_SERVER['SSH_CONNECTION'] != '76.24.176.23 50058 104.130.212.109 22' ) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}*/
		
	require_once( "/var/www/php/poloniex/poloniex_lib.php" );

	$api_key = 'LWQRJN6Q-X3LS0IPC-42H0I72F-J8N624TD';
	$api_secret = 'ca7bfe0e1f1f98b37a4f6eba8aba9ec69d663c01dca46cd3792fca9e53de545e2e25ee95fec0d35f91d0299bb7f83bbb0cd066c2b0fa5349876ae5d74fd44d4d';

	$Poloniex = new poloniex($api_key, $api_secret);
	$get_ticker = $Poloniex->get_ticker();
	$get_balances = $Poloniex->get_balances();
	$get_total_btc_balance = $Poloniex->get_total_btc_balance();
	echo "Total Bitcoin Balance " . $get_total_btc_balance . "\n";
	echo "Available Bitcoin Balance " . $get_balances['BTC'] . "\n";
	//if( $get_balances['BTC'] > 1.24 ) {
		list_detailed_info();
		//cancel_all_orders();
		//make_buy_orders();
		//make_sell_orders();
	//}

	//_____List Detailed Info:
	function list_detailed_info()
	{
		global $Poloniex;
		global $get_ticker;
		global $get_balances;
		global $total_volume;
		
		$total_volume = 0;

		foreach( $get_ticker as $pair => $ticker ) {
			//$get_open_orders = $Poloniex->get_open_orders($pair);
			//print_r( $get_open_orders );

			//$get_my_trade_history = $Poloniex->get_my_trade_history($pair);
			//print_r( $get_my_trade_history );

			$last = $ticker['last'];
			$lowestAsk = $ticker['lowestAsk'];
			$highestBid = $ticker['highestBid'];
			$percentChange = $ticker['percentChange'];
			$baseVolume = $ticker['baseVolume'];
			$quoteVolume = $ticker['quoteVolume'];
			$isFrozen = $ticker['isFrozen'];
			$high24hr = $ticker['high24hr'];
			$low24hr = $ticker['low24hr'];

			$total_volume += $baseVolume;

			$currency = explode("_", $pair);
			$currency0 = $currency[0];
			$currency1 = $currency[1];
			$balance = $get_balances[$currency1];

			echo "last $last\n";
			echo "lowestAsk $lowestAsk\n";
			echo "highestBid $highestBid\n";
			echo "percentChange $percentChange\n";
			echo "baseVolume $baseVolume\n";
			echo "quoteVolume $quoteVolume\n";
			echo "isFrozen $isFrozen\n";
			echo "high24hr $high24hr\n";
			echo "low24hr $low24hr\n";
			echo "pair $pair\n";
			echo "currency0 $currency0\n";
			echo "currency1 $currency1\n";
			echo "balance $balance\n";
			echo "******************\n";

		}

		echo "******************\n";
		echo "Total Volume $total_volume\n";
		echo "\n\n";

	}

	//_____Cancel all orders:
	function cancel_all_orders()
	{
		global $Poloniex;
		global $get_ticker;

		foreach( $get_ticker as $key => $ticker ) {
			$get_open_orders = $Poloniex->get_open_orders($key);
			if( is_array( $get_open_orders ) ) {
				foreach( $get_open_orders as $open_order ) {
					$orderNumber = $open_order['orderNumber'];
					if( ! is_null( $orderNumber ) ) {
						echo "Cancelling order number $orderNumber with pair $key\n";
						$Poloniex->cancel_order($key, $orderNumber);
					}
				}
			}
		}
	}

	//_____Make decreasing buy orders from highest bid placed:
	function make_buy_orders()
	{
		global $Poloniex;
		global $get_ticker;
		global $total_volume;

		$btc_cost = 0;
		foreach( $get_ticker as $key => $ticker ) {
			$highestBid = $ticker['highestBid'];
			$baseVolume = $ticker['baseVolume'];
			$percentChange = $ticker['percentChange'];
			$percentVolume = $baseVolume / $total_volume;

			echo "\n\n$key has $percentVolume% $baseVolume/$total_volume\n";

			$x = 100;
			while( $x > 80 ) {
				if( $highestBid > 0 ) {
					$rate = bcmul($highestBid,$x/100,32);
					$amount = bcdiv((100-$x)*0.0001,$rate,32);
					if( $percentVolume > 0.01 ) {
						$amount = bcmul($amount, $percentVolume, 32);
						$amount = bcmul($amount, 150, 32);
					}
					echo "\n\n$percentChange**************\n\n";
					if( $percentChange < -0.20 ) {
						$amount = $amount * 10 * $percentChange * -1;
					}
					$total = $rate * $amount;
					if( $total > 0.0001 ) {
						echo "buying $key for $rate with a total of $amount\n";
						$btc_cost = $btc_cost + $total;
						print_r( $Poloniex->buy($key,$rate,$amount) );
					}
				}
				$x = $x - 3;
			}
			$rate = '0.00000001'; //maybe set to 1% or 5%?
			$amount = '50000';
			$total = bcmul( $rate, $amount, 32 );
			$btc_cost = $btc_cost + $total;
			echo "\nmin buy $key for $rate with an amount of $amount and total $total\n";
			print_r( $Poloniex->buy($key,$rate,$amount) );

		}
		echo "\n\nThe total cost is $btc_cost bitcoins.\n\n";
	}

	//_____Make increasing sell orders from lowest ask placed:
	function make_sell_orders()
	{
		global $Poloniex;
		global $get_ticker;
		global $get_balances;

		$btc_gain = 0;
		$xmr_gain = 0;
		$usdt_gain = 0;
		foreach( $get_ticker as $key => $ticker ) {
			$lowestAsk = $ticker['lowestAsk'];
			$currency = explode("_", $key);
			$balance = $get_balances[$currency[1]];
			$percentChange = $ticker['percentChange'];

			echo "Total Balance: $balance\n";

			$potential_sale = 0;
			$x = 1;
			while( $x < 50 ) {
				if( $lowestAsk > 0 ) {
					$rate = bcmul($lowestAsk, 1+$x/100, 32);
					$amount = bcmul($balance,$x/100,32);
					$potential_sale = $potential_sale + $amount;
					$total = bcmul( $rate, $amount, 32 );
					if( $percentChange > 0.1 ) {
						$amount = $amount * 10 * $percentChange;
					}

					if( $amount > 0 && $rate > 0 && $total > 0.0001 ) {
						if( $currency[0] == 'BTC' )
							$btc_gain = $btc_gain + $total;
						if( $currency[0] == 'XMR' ) {
							$xmr_gain = $xmr_gain + $total;
							$x = $x + 2;
							continue;
						}
						if( $currency[0] == 'USDT' ) {
							$usdt_gain = $usdt_gain + $total;
							$x = $x + 2;
							continue;
						}
						echo "selling $key for $rate with an amount of $amount and total $total \n";
						print_r( $Poloniex->sell($key,$rate,$amount) );
					}
				}
				$x = $x + 4;
			}
			echo "Potential Sale: $potential_sale\n";

		}
		echo "\n\nThe potential gain is $btc_gain bitcoin $xmr_gain monero $usdt_gain usdt.\n\n";
	}

?>
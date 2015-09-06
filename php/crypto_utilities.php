<?PHP

	// Utility functions:
	// These should be compatible with all of the exchanges
	// They should not use ExchangeAdapter objects, 
	// but they should accept arrays returned from Adapter APIs

	class Utilities {
		public static function get_total_volumes( $market_summaries ) {

			/*
				This MUST return volume for each of the quote currencies
				Then a specific market for that currency will have a percentage of that market only
				BTC is just another currency that could be used as a base or quote currency in a market
			*/

			$total_volume = 0;
			foreach( $market_summaries as $market_summary ) {
				if( strstr( $market_summary['pair'], "-BTC" ) !== FALSE ) {
					$total_volume += $market_summary['quote_volume'];
					continue;
				}
				if( strstr( $market_summary['pair'], "BTC-" ) !== FALSE ) {
					$total_volume += $market_summary['base_volume'];
					continue;
				}
				//TODO calculate then non-BTC markets too...
				/*$base_volume = $market_summary['base_volume'];
				$quote_volume = $market_summary['quote_volume'];
				$curs = explode( "-", $market_summary['pair'] );
				$base_cur = $curs[0];
				$quote_cur = $curs[1];
				$market_summary = Utilities::surch( "pair", $base_cur."-BTC", $market_summaries );
				if( $market_summary ) {
					$total_volume += $quote_volume / $market_summary['last_price'];
					continue;
				}
				$market_summary = Utilities::surch( "pair", "BTC-".$quote_cur, $market_summaries );
				if( $market_summary ) {
					$total_volume += $base_volume / $market_summary['last_price'];
					continue;
				}*/
			}
			return array( 'total_volume' => $total_volume );
		}
		public static function surch( $key, $value, $arr ) {
			foreach( $arr as $a )
				if( isset( $a[$key] ) && $a[$key] === $value )
					return $a;
			return FALSE;
		}
		public static function get_worth( $balances, $market_summaries ) {
			$btc_worth = 0;
			foreach( $balances as $balance ) {
				if( $balance['currency'] === "BTC" ) {
					$btc_worth += $balance['total'];
					continue;
				}
				$market_summary = Utilities::surch( "pair", $balance['currency']."-BTC", $market_summaries );
				if( $market_summary ) {
					$btc_worth += $balance['total'] * $market_summary['last_price'];
					continue;
				}
				$market_summary = Utilities::surch( "pair", "BTC-".$balance['currency'], $market_summaries );
				if( $market_summary ) {
					$btc_worth += $balance['total'] / $market_summary['last_price'];
					continue;
				}
			}
			return array( "btc_worth" => $btc_worth );
		}

		//Calculate avg_buy_price, avg_sell_price, loss, profit, breakeven sale price,
		//for all buys & sells (disregard transfers & deposits... only trades) etc...
		public static function analysis( $trades = array(), $time = 0 ){}

		//This function will parse through trades since $time finding the highest & lowest sell price
		public static function get_high_low( $trades = array(), $time = 0 ){}

		//This function will parse through trades and calculate the ema for $time
		//Maybe this is already implemented in PHP???
		public static function get_ema( $trades = array(), $time = 0 ){}

	}
?>
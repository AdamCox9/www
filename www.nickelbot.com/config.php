<?PHP

	require_once( "../php/crypto_interface.php" );

	require_once( "../php/bitfinex/bitfinex_lib.php" );
	require_once( "../php/bitstamp/bitstamp_lib.php" );
	require_once( "../php/bittrex/bittrex_lib.php" );
	require_once( "../php/btc-e/btc-e_lib.php" );
	require_once( "../php/bter/bter_lib.php" );
	require_once( "../php/coinbase/coinbase_lib.php" );
	require_once( "../php/cryptsy/cryptsy_lib.php" );
	require_once( "../php/poloniex/poloniex_lib.php" );

	require_once( "../php/bitfinex/bitfinex_adapter.php" );
	require_once( "../php/bitstamp/bitstamp_adapter.php" );
	require_once( "../php/bittrex/bittrex_adapter.php" );
	require_once( "../php/btc-e/btc-e_adapter.php" );
	require_once( "../php/bter/bter_adapter.php" );
	require_once( "../php/coinbase/coinbase_adapter.php" );
	require_once( "../php/cryptsy/cryptsy_adapter.php" );
	require_once( "../php/poloniex/poloniex_adapter.php" );

	$bitfinex_api_key = "A4K0wCj6KLdChWkp2Xxd4xPLDWj9nYD7dCvdZ5Wj7jr";
	$bitfinex_api_secret = "xYKT35o3rJloES7GWkO2lWKYH9c5kwxCVV0W3XqqaP7";

	$bitstamp_api_key = "IFo7FPrSu97ufUbUS89CHz3uwqKyHFcX";
	$bitstamp_api_secret = "CehjYgU0JArV9vtO7hj6p1WL5Q7rdVtP";

	$bittrex_api_key = "5d46d0942fea4f059d95c3bce1377f57";
	$bittrex_api_secret = "15aa417db72249d5831b402cab1aa289";

	$btce_api_key = "2ZCV2VA7-GQA3QKHA-VJ1IGJ1N-7K86RGKW-6IHMCY2P";
	$btce_api_secret = "cfb855a2ccbdf1e9baf8c7fcd19327e53c9b080db3aec1fa98a3a6a62cccfb87";

	$bter_api_key = "EAD04357-77CE-4A75-9B7D-CFAD0B481D09";
	$bter_api_secret = "1dd9b32a6a0a92ab70bc2944ad567db9b133593eee8c597d80e9d4392235f11b";

	$coinbase_api_key = '8aa0beaec603603e90411f28f17b902f';
	$coinbase_api_secret = '/2xL4nbN5jwjevO02WTyhRe3usqubtEJIlzL6+omWzls9cGHftdCtDfB7tQE1JwZkKGoaGtFrO6QhWxS47Kfgw==';
	$coinbase_api_passphrase = '8f0ac5kewjtw3ik9';

	$cryptsy_api_key = "48d4648b4a93b38a292c41dcde74b6bdec185c83";
	$cryptsy_api_secret = "4c8d67097d0243dcabe368a1db09cf1665966fe9abf2c2f4b1a21c52393582522d58ea6ffbbdfeb4";

	$poloniex_api_key = "LWQRJN6Q-X3LS0IPC-42H0I72F-J8N624TD";
	$poloniex_api_secret = "ca7bfe0e1f1f98b37a4f6eba8aba9ec69d663c01dca46cd3792fca9e53de545e2e25ee95fec0d35f91d0299bb7f83bbb0cd066c2b0fa5349876ae5d74fd44d4d";

	$Adapters = array();
	$Adapters['Bitfinex'] = new BitfinexAdapter( new Bitfinex( $bitfinex_api_key, $bitfinex_api_secret ) );
	$Adapters['Bitstamp'] = new BitstampAdapter( new Bitstamp( $bitstamp_api_key, $bitstamp_api_secret, "779882" ) );
	$Adapters['Bittrex'] = new BittrexAdapter( new bittrex( $bittrex_api_key, $bittrex_api_secret ) );
	$Adapters['Btce'] = new BtceAdapter( new btce( $btce_api_key, $btce_api_secret ) );
	$Adapters['Bter'] = new BterAdapter( new bter( $bter_api_key, $bter_api_secret ) );
	$Adapters['Coinbase'] = new CoinbaseAdapter( new coinbase( $coinbase_api_key, $coinbase_api_secret, $coinbase_api_passphrase ) );
	$Adapters['Cryptsy'] = new CryptsyAdapter( new cryptsy( $cryptsy_api_key, $cryptsy_api_secret ) );
	$Adapters['Poloniex'] = new PoloniexAdapter( new poloniex( $poloniex_api_key, $poloniex_api_secret ) );

?>
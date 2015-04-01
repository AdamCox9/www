<?PHP

	$daysAverageString = isset( $_GET['daysAverageString'] ) ? $_GET['daysAverageString'] : 1;

	echo file_get_contents( "https://blockchain.info/charts/market-price?showDataPoints=false&timespan=all&show_header=true&daysAverageString=$daysAverageString&scale=0&format=json&address=" );

?>

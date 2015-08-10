var bittrex = require('./node.bittrex.api/node.bittrex.api.js');

//configure
bittrex.options({
    'apikey' : "5d46d0942fea4f059d95c3bce1377f57",
    'apisecret' : "15aa417db72249d5831b402cab1aa289",
    'stream' : false,
    'verbose' : true,
    'cleartext' : false 
});

//_____Make minimum possible buy orders:
total_cost = 0;
bittrex.getmarketsummaries( function( data ) {
	//console.log( data );
    for( var i in data.result ) {
		rate = '0.00000080';
		quantity = '1625';
		amount = rate * quantity;
		total_cost = total_cost + amount;
		console.log( "placing order for " + data.result[i].MarketName + " at rate " + rate + " with quantity " + quantity + " totaling " + amount );

		config = { 'market'		: data.result[i].MarketName,
				   'quantity'	: quantity,
				   'rate'		: rate };

		console.log( " total cost " + total_cost );

		bittrex.buylimit( config, function( data ) {
			console.log( data );
		});

	}
});

//_____Make minimum listed buy orders:
/*total_cost = 0;
bittrex.getmarketsummaries( function( data ) {
	//console.log( data );
    for( var i in data.result ) {
		x = 100;
		while( x > 82 ) {
			quantity = (101-x)*0.0005/data.result[i].Bid;
			rate = data.result[i].Bid * 0.01 * x;
			amount = rate * quantity;
			if( rate > 0 ) {
				total_cost = total_cost + amount;
				config = { 'market'		: data.result[i].MarketName,
						   'quantity'	: quantity,
						   'rate'		: rate };

				console.log( "placing order for " + data.result[i].MarketName + " at rate " + rate + " with quantity " + quantity + " totaling " + amount );
				bittrex.buylimit( config, function( data ) {
					console.log( data );
				});
			}
			x = x - 6;

			console.log( " total cost " + total_cost );
		
		}
	}
});*/

//_____Cancel all orders:
/*bittrex.getopenorders( {}, function( data ) {
	console.log( data.result );
	for( var i in data.result ) {
		console.log( "cancelling order " + data.result[i].OrderUuid );

		opts = { 'uuid' : data.result[i].OrderUuid };
		bittrex.cancel( opts, function( data ) {
			console.log( data );
		});
	}
});*/
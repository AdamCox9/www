var bittrex = require('./node.bittrex.api/node.bittrex.api.js');

//configure
bittrex.options({
    'apikey' : "5d46d0942fea4f059d95c3bce1377f57",
    'apisecret' : "15aa417db72249d5831b402cab1aa289",
    'stream' : false,
    'verbose' : true,
    'cleartext' : false 
});

//_____Make minimum buy orders:
/*bittrex.getmarketsummaries( function( data ) {
	//console.log( data );
    for( var i in data.result ) {
		console.log( "placing order for " + data.result[i].MarketName + " at rate " + data.result[i].Ask + " with quantity " + (0.0005/data.result[i].Ask) );

		config = { 'market'		: data.result[i].MarketName,
				   'quantity'	: 0.0005/data.result[i].Bid,
				   'rate'		: data.result[i].Bid };

		bittrex.buylimit( config, function( data ) {
			console.log( data );
		});
	
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


var CoinbaseExchange = require('coinbase-exchange');
var publicClient = new CoinbaseExchange.PublicClient();

var callback = function(err, response, data) {
  console.log( JSON.stringify( data ) );
};

publicClient.getProducts(callback);
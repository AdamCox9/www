
/**
 **/

var key = '96f5aece4b51a56d71520a6f8f55f5d3';
var b64secret = 'RU2oFVSOVXJRUEtLSWt7cUoN1WMY2Q8COidj8e65yr+1G5YmDev3ezRqkGEjLt+96ypxrRpHmZ8QmKhqi33Vpw==';
passphrase = 'dd6bf0643ebe8959960a246f8085695a74d064d856e6b03791d65d9fd878a95c';

var CoinbaseExchange = require( 'coinbase-exchange' );
var publicClient = new CoinbaseExchange.PublicClient();
var authedClient = new CoinbaseExchange.AuthenticatedClient( key, b64secret, passphrase );


/***** Public Client Samples *****/

//publicClient.getProducts(callback);
//publicClient.getProductOrderBook(callback);
//publicClient.getProductOrderBook({'level': 3}, callback);
//publicClient.getProductTicker(callback);
//publicClient.getProductTrades({'after': 1000}, callback);
//publicClient.getProductHistoricRates({'granularity': 3000}, callback);
//publicClient.getProduct24HrStats(callback);
//publicClient.getCurrencies(callback);
//publicClient.getTime(callback);

/***** Authed Client Samples *****/


//[{"id":"66d97e24-3035-474e-99b5-874f73e33ce7","currency":"USD","balance":"77.4738735592967500","hold":"0.0000000000000000","available":"77.4738735592967500","profile_id":"dc890ebe-bf2a-4e6a-8c33-912d6dad8ed0"},{"id":"279bd9ff-6e8a-4677-9f22-676951ffbb53","currency":"BTC","balance":"1.0000000000000000","hold":"0.0000000000000000","available":"1.0000000000000000","profile_id":"dc890ebe-bf2a-4e6a-8c33-912d6dad8ed0"}]

//var accountUSD = '66d97e24-3035-474e-99b5-874f73e33ce7';
//var accountBTC = '279bd9ff-6e8a-4677-9f22-676951ffbb53';
//authedClient.getAccounts(callback);
//authedClient.getAccount(accountBTC, callback);
//authedClient.getAccountHistory(accountUSD, callback);
//authedClient.getAccountHolds(accountUSD, callback);

// Buy 0.01 BTC @ 100 USD 
/*var buyParams = {
  'price': '100.00', // USD 
  'size': '0.01',  // BTC 
  'product_id': 'BTC-USD',
};
authedClient.buy(buyParams, callback);*/
 
// Sell 0.01 BTC @ 300 USD 
/*var sellParams = {
  'price': '300.00', // USD 
  'size': '0.01', // BTC 
  'product_id': 'BTC-USD',
};
authedClient.sell(sellParams, callback);*/

//authedClient.getOrders(callback);

//var orderID = 'b21382d5-c2ea-4e40-a493-bcbf02e36582';
//authedClient.getOrder(orderID, callback);
//authedClient.cancelOrder(orderID, callback);

//authedClient.getFills(callback);
//authedClient.getFills({'before': 3000}, callback);

/*****
	Listen to the live feed:
 *****/
//var CoinbaseExchange = require('coinbase-exchange');
//var websocket = new CoinbaseExchange.WebsocketClient();
//websocket.on('message', function(data) { console.log(data); });
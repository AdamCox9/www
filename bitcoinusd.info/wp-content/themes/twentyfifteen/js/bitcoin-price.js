jQuery(document).ready(function($) {

	//https://api.bitcoinaverage.com/ticker/global/
	//https://api.bitcoinaverage.com/ticker/global/USD/

	$.ajax({
		url: "https://api.bitcoinaverage.com/ticker/global/USD/",
		/*data: {
			id: 123
		},*/
		type: "GET",
		dataType : "json",
		success: function( json ) {
			//console.log( JSON.stringify( json ) );
			$( '#bitcoin_price' ).html( json.last );
			$( '#bitcoin_ask' ).html( json.ask );
			$( '#bitcoin_bid' ).html( json.bid );
			$( '#bitcoin_avg' ).html( json['24h_avg'] );
			$( '#bitcoin_vol' ).html( json.volume_btc );
		},
		error: function( xhr, status, errorThrown ) {
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		}
	});

});
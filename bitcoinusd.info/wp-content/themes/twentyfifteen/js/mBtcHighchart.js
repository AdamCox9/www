jQuery(document).ready(function($) {

	//https://blockchain.info/charts/market-price?showDataPoints=false&timespan=all&show_header=true&daysAverageString=1&scale=0&format=json&address=
	function updateChart(daysAverageString) {
		window.mBtcVals = [];

		$.ajax({
			url: "https://bitcoinusd.info/wp-content/themes/twentyfifteen/getChartData.php?daysAverageString="+daysAverageString,
			/*data: {
				id: 123
			},*/
			type: "GET",
			dataType : "json",
			success: function( data ) {

				for( var i = 0; i < data.values.length; i++ ) {
					//console.log( data.values[i].y );
					window.mBtcVals.push( data.values[i].y );
				}

				$('#container').highcharts({
					chart: {
						zoomType: 'x'
					},
					title: {
						text: 'Bitcoin to USD exchange rate from 2009 through Present'
					},
					subtitle: {
						text: document.ontouchstart === undefined ?
								'Click and drag in the plot area to zoom in' :
								'Pinch the chart to zoom in'
					},
					xAxis: {
						type: 'datetime',
						minRange: 14 * 24 * 3600000 // 6 years
					},
					yAxis: {
						title: {
							text: 'Exchange rate'
						}
					},
					legend: {
						enabled: false
					},
					plotOptions: {
						area: {
							fillColor: {
								linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
								stops: [
									[0, Highcharts.getOptions().colors[0]],
									[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
								]
							},
							marker: {
								radius: 2
							},
							lineWidth: 1,
							states: {
								hover: {
									lineWidth: 1
								}
							},
							threshold: 0
						}
					},

					series: [{
						type: 'area',
						name: 'USD to BTC',
						pointInterval: 24 * 3600 * 1000,
						pointStart: Date.UTC(2009, 0, 1),
						data: window.mBtcVals //y values
					}]
				});

			},
			error: function( xhr, status, errorThrown ) {
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			}
		});
	}

	updateChart(1);
	
	var spinner = $( "#spinnerValue" ).spinner();
 
    $( "#update" ).click(function() {
		updateChart( $( "#spinnerValue" ).val() );
    });
 
    $( "button" ).button();

});
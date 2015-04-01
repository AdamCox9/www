// Shorthand for $( document ).ready()
$(function() {
	$('.carousel').carousel({interval: 4000});

	if( location.pathname == '/aws/itemPage.php' ) {
		var iploc = $('#iplookup').val(),
			newLink = null,
			newTag = null,
			keywords = null;


		console.log( iploc );

		/*if( iploc == 'GB' ) {
			newLink = "www.amazon.co.uk";
			newTag = "randothoug05-21";

			for(var i = 0, l=document.links.length; i<l; i++) {
				console.log(document.links[i].href);
				document.links[i].href = document.links[i].href.replace("www.amazon.com",newLink);
				document.links[i].href = document.links[i].href.replace("ezstbu-20",newTag);
			}

		}*/

		
		if( iploc == "FR" ) {
			newLink = "http://www.amazon.fr";
			newTag = "randothoug0c-21";
			for(var i = 0, l=document.links.length; i<l; i++) {
				console.log(document.links[i].href);
				keywords = document.links[i].href.split("/");
				if( keywords[2] == "www.amazon.com" && keywords[3] != "gp" && keywords[3] != "review" ) {
					keywords = keywords[3].replace(/-/g, '+');
					document.links[i].href = newLink + "/s/?url=search-alias&tag=" + newTag + "&field-keywords=" + keywords;
				}
			}
		}

		if( iploc == "IT" ) {
			newLink = "http://www.amazon.it";
			newTag = "randothoug00-21";
			for(var i = 0, l=document.links.length; i<l; i++) {
				console.log(document.links[i].href);
				keywords = document.links[i].href.split("/");
				if( keywords[2] == "www.amazon.com" && keywords[3] != "gp" && keywords[3] != "review" ) {
					keywords = keywords[3].replace(/-/g, '+');
					document.links[i].href = newLink + "/s/?url=search-alias&tag=" + newTag + "&field-keywords=" + keywords;
				}
			}
		}

		if( iploc == "DE" ) {
			newLink = "http://www.amazon.de";
			newTag = "randothoug03-21";
			for(var i = 0, l=document.links.length; i<l; i++) {
				console.log(document.links[i].href);
				keywords = document.links[i].href.split("/");
				if( keywords[2] == "www.amazon.com" && keywords[3] != "gp" && keywords[3] != "review" ) {
					keywords = keywords[3].replace(/-/g, '+');
					document.links[i].href = newLink + "/s/?url=search-alias&tag=" + newTag + "&field-keywords=" + keywords;
				}
			}
		}

		if( iploc == "ES" ) {
			newLink = "http://www.amazon.es";
			newTag = "randothoug08-21";
			for(var i = 0, l=document.links.length; i<l; i++) {
				console.log(document.links[i].href);
				keywords = document.links[i].href.split("/");
				if( keywords[2] == "www.amazon.com" && keywords[3] != "gp" && keywords[3] != "review" ) {
					keywords = keywords[3].replace(/-/g, '+');
					document.links[i].href = newLink + "/s/?url=search-alias&tag=" + newTag + "&field-keywords=" + keywords;
				}
			}
		}

	}

});
$(document).ready(function() {
	myAjaxHandler();
});

/*

	Get's the initial data to generate all the forms...

*/

function myAjaxHandler() {
	$.getJSON('manage.php?action=getall', function(data) {
	  var items = [];

	  $.each(data, function(key, val) {
		  $('<form/>', {
			'id': 'f' + val.id,
			'method': 'get',
			'action': 'manage.php',
			html:	'<b>Record: <a target="_blank" href="files/' + val.filename + '">' + val.id + '</a></b> &nbsp; <input type="hidden" name="action" value="update"> &nbsp; <input type="hidden" name="id" value="' + val.id + '"' + '> ' +
					'Filename: <input size="40" type="text" name="filename"' + ' value="' + val.filename + '"> ' + 
					'Title: <input size="40" type="text" name="title"' + ' value="' + val.title + '"> ' + 
					'Author: <input size="40" type="text" name="author"' + ' value="' + val.author + '"> ' + 
					'Website: <input size="40" type="text" name="website"' + ' value="' + val.website + '"> ' + 
					'Email: <input size="40" type="text" name="email"' + ' value="' + val.email + '">' + 
					'Verified: <input size="5" type="text" name="verified"' + ' value="' + val.verified + '"> ' +
					'<input type="submit" value="commit"> '
		  }).appendTo('body');

		  $('<hr/>', {
			  'style': 'padding: 5px; border-top: none; border-left: none;'
		  }).appendTo('body');

		  formHandler(  'form#f' + val.id );
	  });

	});
}

function formHandler(id) {
	$(id).ajaxForm({
			beforeSubmit: function() {
				//$('#uploading').show();
				//location.href="#top";
			},
			success: function(data) {
				$(id).hide();
				$('#finished').show();
				var $out = $('#results');
				$out.html('Your results:');
				$out.append('<div><pre>'+ data +'</pre></div>');
			}
		});
}
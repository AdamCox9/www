$(document).ready(function() {

	myAjaxHandler();

	$('#uploading').hide();
	$('#finished').hide();



});


function myAjaxHandler() {
	$('form').ajaxForm({
			beforeSubmit: function() {
				if ( $("input#title").val() == "" ) {
				    alert("Title can't be blank");
					return false;
				}
				if ( $("input#author").val() == "" ) {
				    alert("Author can't be blank");
					return false;
				}
				if ( $("input#website").val() == "" ) {
				    alert("Website can't be blank");
					return false;
				}
				if ( $("input#email").val() == "" ) {
				    alert("Email can't be blank");
					return false;
				}
				if ( $("input#mp3").val() == "" ) {
				    alert("MP3 can't be blank");
					return false;
				}
				
				//Let's make sure the file is MP3 or WAV...
				temp = $("input#mp3").val().split('.');
				if ( ! ( temp[temp.length-1] == "mp3" || temp[temp.length-1] == "wav" ) ) {
				    alert("File must be MP3 or WAV format.");
					return false;
				}

				if ( ! $("input#agree").is(":checked") ) {
				    alert("You must check the box to agree");
					return false;
				}

				$('form').hide();
				$('#uploading').show();
				location.href="#top";
			},
			success: function(data) {
				$('#uploading').hide();
				$('#finished').show();
				var $out = $('#results');
				$out.html('Your results:');
				$out.append('<div><pre>'+ data +'</pre></div>');
			}
		});
}
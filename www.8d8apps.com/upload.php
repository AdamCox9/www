<?PHP

	require 'local.php';

	$MicroAmazonList = microSearchForItems('All','Android',1);

	$content = <<<HTML

		$MicroAmazonList

		<a name="top"></a>

		<h3 style="text-align: left;">Publish Beats</h3>
		<h4 style="text-align: left;">Upload MP3 or WAV</h4>
		<p>Publish your beats to hundreds of thousands of rappers. You will get credit (Title/Author/Website) where the beat is used!</p>
		<p>Upload only MP3 or WAV that are instrumentals with no lyrics. You must agree to either own the beats or have permission to publish the beats.</p>

		<form id="upload_form" method="post" action="uploader.php" enctype="multipart/form-data">
			<input type="hidden" name="hidden" value="hidden" />
			<div class="form_text">Title:</div>
			<div class="form_text_input"><input type="text" name="title" id="title" /></div>
			<div class="br"></div>
			<div class="form_text">Author:</div>
			<div class="form_text_input"><input type="text" name="author" id="author" /></div>
			<div class="br"></div>
			<div class="form_text">Website:</div>
			<div class="form_text_input"><input type="text" name="website" it="website" /></div>
			<div class="br"></div>
			<div class="form_text">E-Mail: (not published)</div>
			<div class="form_text_input"><input type="text" name="email" id="email" /></div>
			<div class="br"></div>
			<div class="form_text">MP3:</div>
			<div class="form_file_input"><input type="file" name="mp3" id="mp3" /></div>
			<div class="br"></div>
			<br>
			<div class="form_text"><input type="checkbox" name="agree" id="agree" CHECKED /> <div>I agree that I own this beat and have the right to distribute it and I am also granting rights to 8D8 Apps to redistribute the beat.</div> </div>
			<div class="br"></div>
			<div class="form_submit_input"><input type="submit" value="Upload MP3" /></div>
			<div class="br"></div>
		</form>
		<div id="uploading">
			<img width='250px' alt="Uploading... This may take a minute..." src="images/ajax-loader.gif">
			<p>Uploading... Please be patient. This may take a minute...</p>
		</div>
		<div id="finished">
			Your beats have been submitted for consideration to be included. We will contact you if we publish your beats.
			<br>
			Press back on your device to return to the Rap Beats app.
		</div>

HTML;

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', " - Publish Beats", $template );

	echo $template;

?>
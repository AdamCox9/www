<?PHP

	require_once 'local.php';

	if( ! $db )
		$db = new DatabaseConnection();

	$title = "Categories";
	$head = null;


	if( isset( $_GET['category'] ) ) {
		$title = $_GET['category'];
		$thoughts = getCategorizedThoughts($_GET['category']);
		$quotes = "<div style='text-align:left;'>";
		foreach( $thoughts as $thought ) {
			if ( strlen( $thought[1] ) > 300 ) {
				$quote = strip_tags(substr(stripslashes($thought[1]),0,300))."...";
			} else {
				$quote = strip_tags(stripslashes($thought[1]));
			}

			$quotes .= "<div style='padding:15px;'>$quote <a href='page.php?lim=".$thought[0]."'>view&gt;&gt;&gt;</a></div>";
		}
		$content = $quotes . "</div>";

	} else {

		$content = <<<HTML

<div style='padding-left:100px;'>

	<table width="100%">
		<tr>
			<td valign="top">
				<div class='category'><a href="categories.php?category=Bad+Thoughts">Bad Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Beautiful+Thoughts">Beautiful Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Crazy+Thoughts">Crazy Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Depressing+Thoughts">Depressing Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Deep+Thoughts">Deep Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Funny+Thoughts">Funny Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Funny+Random+Thoughts">Funny Random Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Happy+Thoughts">Happy Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Inspirational+Thoughts">Inspirational Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Motivational+Thoughts">Motivational Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Negative+Thoughts">Negative Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Obsessive+Thoughts">Obsessive Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Positive+Thoughts">Positive Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Political+Thoughts">Political Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Racing+Thoughts">Racing Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Spiritual+Thoughts">Spiritual Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Stupid+Thoughts">Stupid Thoughts</a></div>
				<div class='category'><a href="categories.php?category=Suicidal+Thoughts">Suicidal Thoughts</a></div>
				<br>
				<div class='category'><a href="categories.php?category=Thoughts+for+the+day">Thoughts for the day</a></div>
				<div class='category'><a href="categories.php?category=Thoughts+to+Ponder">Thoughts to Ponder</a></div>
				<div class='category'><a href="categories.php?category=Thoughts+on+Love">Thoughts on Love</a></div>
				<div class='category'><a href="categories.php?category=Thoughts+on+Life">Thoughts on Life</a></div>
				<div class='category'><a href="categories.php?category=Thoughts+on+Prayer">Thoughts on Prayer</a></div>
				<div class='category'><a href="categories.php?category=Thoughts+on+Religion">Thoughts on Religion</a></div>
				<br>
			</td>
			<td valign="top">
				<div class='category'><a href="categories.php?category=Random+Curiosity">Random Curiosity</a></div>
				<div class='category'><a href="categories.php?category=Random+Definitions">Random Definitions</a></div>
				<div class='category'><a href="categories.php?category=Random+Facts">Random Facts</a></div>
				<div class='category'><a href="categories.php?category=Random+Funny+Facts">Random Funny Facts</a></div>
				<div class='category'><a href="categories.php?category=Random+Funny+Quotes">Random Funny Quotes</a></div>
				<div class='category'><a href="categories.php?category=Random+Ideas">Random Ideas</a></div>
				<div class='category'><a href="categories.php?category=Random+Insults">Random Insults</a></div>
				<div class='category'><a href="categories.php?category=Random+Jokes">Random Jokes</a></div>
				<div class='category'><a href="categories.php?category=Random+Kindness">Random Kindness</a></div>
				<div class='category'><a href="categories.php?category=Random+Knowledge">Random Knowledge</a></div>
				<div class='category'><a href="categories.php?category=Random+Laws">Random Laws</a></div>
				<div class='category'><a href="categories.php?category=Random+Lyrics">Random Lyrics</a></div>
				<div class='category'><a href="categories.php?category=Random+News">Random News</a></div>
				<div class='category'><a href="categories.php?category=Random+Pictures">Random Pictures</a></div>
				<div class='category'><a href="categories.php?category=Random+Quotes">Random Quotes</a></div>
				<div class='category'><a href="categories.php?category=Random+Questions">Random Questions</a></div>
				<div class='category'><a href="categories.php?category=Random+Recipe">Random Recipe</a></div>
				<div class='category'><a href="categories.php?category=Random+Thoughts+of+the+Day">Random Thoughts of the Day</a></div>
				<div class='category'><a href="categories.php?category=Random+Stuff">Random Stuff</a></div>
				<div class='category'><a href="categories.php?category=Random+Sayings">Random Sayings</a></div>
				<div class='category'><a href="categories.php?category=Random+Things">Random Things</a></div>
				<div class='category'><a href="categories.php?category=Random+Videos">Random Videos</a></div>
				<div class='category'><a href="categories.php?category=Random+Words">Random Words</a></div>
				<div class='category'><a href="categories.php?category=Random+Website">Random Website</a></div>
			</td>
		</tr>
	</table>

</div>

HTML;

	}

	$content = "<h1>$title</h1>$content";

	$template = str_replace( '<!--[[[~CONTENT~]]]-->', $content, $template );
	$template = str_replace( '<!--[[[~HEAD~]]]-->', $head, $template );
	$template = str_replace( '<!--[[[~TITLE~]]]-->', $title, $template );

	echo $template;

?>
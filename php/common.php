<?php

//ini_set( 'display_errors', 'off' );
error_reporting( 0 );

$title = 'Random Thoughts';

session_start();

if ( isset( $_SESSION['username'] ) && isset( $_SESSION['password'] ) ) {
	$loggedIn = TRUE;
} else {
	$loggedIn = FALSE;
}

$GLOBALS['db_host'] = 'localhost';
$GLOBALS['db_user'] = 'root';
$GLOBALS['db_pass'] = '123233abc';
$GLOBALS['db_name'] = 'randomthoughts';

require_once '../php/common_lib.php';
require_once '../php/amazon/amazon_lib.php';
require_once '../php/youtube/youtube_lib.php';
require_once '../php/wikipedia/wikipedia_lib.php';
require_once '../php/wikipedia/Wiky.php-master/wiky.inc.php';
require_once '../php/wordnet/wordnet_lib.php';
require_once '../php/thoughts/thought_lib.php';
require_once '../php/thoughts/thought_comment_lib.php';
require_once '../php/thoughts/thought_category_lib.php';
require_once '../php/database/database.class.php';
require_once '../php/users/users.class.php';
require_once '../php/users/users.profile.class.php';

$db = null; //This should be set to new DatabaseConnection if it is used...

$template = file_get_contents( 'template.html' );

$randomId = file_get_contents( '../php/thoughts/randomids/ids.txt' );
$randomId = explode( "\n", $randomId );

$template = str_replace( '<!--[[[~RANDOMID~]]]-->', $randomId[rand(0,sizeof($randomId))], $template );

$user = 'Anonymous';

?>
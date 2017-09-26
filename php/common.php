<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

$title = 'Random Thoughts';

session_start();

if ( isset( $_SESSION['username'] ) && isset( $_SESSION['password'] ) ) {
	$loggedIn = TRUE;
} else {
	$loggedIn = FALSE;
}

$GLOBALS['db_host'] = 'localhost';
$GLOBALS['db_user'] = 'root';
$GLOBALS['db_pass'] = 'pqBsMMnroIm2k2ldqiSQ';
$GLOBALS['db_name'] = '8d8global';

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

$user = 'Anonymous';

$timestamp = time();

?>
<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

define("PAYPAL_GATEWAY_URL", "www.paypal.com");

define("MAIL_PHPMAIL", 0);
define("MAIL_SENDMAIL", 1);
define("MAIL_SMTP", 2);

define('ACCESS_UNREGISTERED', -2);
define('ACCESS_ALL', -1);
define('ACCESS_REGISTERED', 0);
define('ACEESS_GM', 3);
define('ACCESS_ADMIN', 4);
define('ACCESS_ROOT', 255);
define('ACCESS_NONE', 256);

define('BAN_ACCOUNT', 500);
define('BAN_IP', 501);
define('BAN_BOTH', 502);

define('EMAIL_EMPTY', 1);
define('EMAIL_FORMAT', 2);
define('EMAIL_CONFIRM', 3);
define('EMAIL_EXISTS', 4);
define('EMAIL_ILLEGAL_SPACE', 5);
define('EMAIL_LENTH_ABOVE', 6);

//Username system is deprecated, email adress is now username
define('USERNAME_EMPTY', 1);
define('USERNAME_ILLEGAL_CHARACTER', 2);
define('USERNAME_ILLEGAL_SPACE', 3);
define('USERNAME_LENTH_ABOVE', 4);
define('USERNAME_LENTH_BELOW', 5);
define('USERNAME_EXISTS', 6);

define('REWARD_VOTE', 0);
define('REWARD_DONATE', 1);

define('MMQryType_Unset', 0); //Not set yet
define('MMQryType_Select', 1); //Queries like SELECT
define('MMQryType_Insert', 2); //Queries like INSERT and REPLACE
define('MMQryType_Update', 3); //Queries like UPDATE
define('MMQryType_Delete', 4); //Queries like DELETE

define('PAYMENTTYPE_INVALID', 0);
define('PAYMENTTYPE_VALID', 1);

define('CRAWLERS_LIST', 'Bloglines subscriber|Dumbot|Sosoimagespider|QihooBot|FAST-WebCrawler|Superdownloads Spiderman|LinkWalker|msnbot|ASPSeek|WebAlta Crawler|Lycos|FeedFetcher-Google|Yahoo|YoudaoBot|AdsBot-Google|Googlebot|Scooter|Gigabot|Charlotte|eStyle|AcioRobot|GeonaBot|msnbot-media|Baidu|CocoCrawler|Google|Charlotte t|Yahoo! Slurp China|Sogou web spider|YodaoBot|MSRBOT|AbachoBOT|Sogou head spider|AltaVista|IDBot|Sosospider|Yahoo! Slurp|Java VM|DotBot|LiteFinder|Yeti|Rambler|Scrubby|Baiduspider|accoona');

$SECRETQUESTIONS = array(
	1 => array(
		1 => "In which city were you born?",
		2 => "What was the name of your first pet?",
		3 => "Who was your childhood friend?",
		4 => "What was the name of your favorite teacher?",
		5 => "When did you start World of Warcraft?",
	),
	2 => array(
		1 => "What was the make of your first car?",
		2 => "What was your favorite place to visit as a child?",
		3 => "Who is your favorite actor, musician, or artist?",
		4 => "What high school did you attend?",
		5 => "What street did you grow up on?",
	)
);

/**
 * ###### SOME DOCUMENTS AND VARIABLES TO REMEMBER	######
 * ###### 			FOR DEVELOPERS ONLY				######
 * 
 * --- Character List GLOBAL Variables ---
 * $CHARACTERLIST_SHOW_TOOLS = false; //Shows the links for character tools if set to true
 * $CHARACTERLIST_MUSTBEONLINE = false; //If set to true, it'll prevent it from being selected if the character is offline and if SELECTION is enabled
 * $CHARACTERLIST_NOT_MUSTBEOLINE = false; //If set to true, it'll prevent it from being selected if the character is online and if SELECTION is enabled
 * $CHARACTERLIST_RID = null; //REALMIDs are set in /includes/config.php
 * $CHARACTERLIST_SELECTION = false; //If set to true ... Adds radio button to select character with FROM variables as: Name=character_selected, Value=345(CharacterID)
 * 
 * --- Redirect GLOBAL Variables and Method ---
 * $REDIRECT_MESSAGE = "Redirection Message to show without times";
 * $REDIRECT_LOCATION = "redirection_page.ext";
 * $REDIRECT_INTERVAL = 2000; //Interval in milliseconds(Default 2000 ie 2seconds)
 * $REDIRECT_TYPE = "success"; //Can be "success", "error", and "notification"
 * eval($templates->Redirect()); //This is called after the 4 variables have been set
 */

?>
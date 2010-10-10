<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

/**
 * *********************************************************
 * ******************** MYSQL CONFIGS **********************
 * *********************************************************
 */
//Databases
$WEB_DATABASE = array(
	'host'	=> '127.0.0.1', //Hostname or IP Address of MySQL server
	'user'	=> 'root', //Username of MySQL server
	'pass'	=> 'root', //Password of the username above
	'port'	=> '3306', //Listening Port of the MySQL server(Default: 3306)
	'db'	=> 'website', //Database with the stored tables
);

$LOGON_DATABASE = array(
	'host'	=> '127.0.0.1', //Hostname or IP Address of MySQL server
	'user'	=> 'root', //Username of MySQL server
	'pass'	=> 'root', //Password of the username above
	'port'	=> '3306', //Listening Port of the MySQL server(Default: 3306)
	'db'	=> 'realmd', //Database with the stored tables
);

$LOGON_REALMLIST = "logon.dominationwow.com";

$REALM = array(); //$REALM[realmid] = array(CH_DATABASE=array,W_DATABASE=array,CAP,IP,PORT,SOAP=array)
$REALM[1] = array(
	'CH_DATABASE'	=> array(
		'host'	=> '127.0.0.1', //Hostname or IP Address of MySQL server
		'user'	=> 'root', //Username of MySQL server
		'pass'	=> 'root', //Password of the username above
		'port'	=> '3306', //Listening Port of the MySQL server(Default: 3306)
		'db'	=> 'character1', //Database with the stored tables
	),
	'W_DATABASE'	=> array(
		'host'	=> '127.0.0.1', //Hostname or IP Address of MySQL server
		'user'	=> 'root', //Username of MySQL server
		'pass'	=> 'root', //Password of the username above
		'port'	=> '3306', //Listening Port of the MySQL server(Default: 3306)
		'db'	=> 'world1', //Database with the stored tables
	),
	'NAME'	=> "Funserver",
	'CAP'	=> 200,
	'IP'	=> '66.219.29.67',
	'PORT'	=> 8129,
	'SOAP'	=> array(
		'user'	=> 'RAREMOTE', //Username of account with gmlevel 4 in UPPERCASE
		'pass'	=> 'REMOTEACCESSONLY', //Password
		'port'	=> 7878, //Port of SOAP server
	),
	'UNSTUCK' => array(
		'alliance'	=> 'shatmall',
		'horde'		=> 'shatmall',
	),
	'DESC_LONG'	=> //Description of the server's role and game play below in the quotes
	"This realm is all about KILLING, no matter the opponent is a horde or alliance. Everyone is your enemy! You do not have to focus on leveling or anything like getting ready for PvP by raiding for items or trading for gold. You start as a full leveled player at a shopping mall, all you have to do it just your favorite items from the mall and start killing in our PvP Arenas! For every kill you are rewarded [Badge of Justice] which can be turned-in for PvP Gear! Login now to find out what more we have!",
);

/**
 * *********************************************************
 * ***************** Email Configurations ******************
 * ******************* Default Settings ********************
 * *********************************************************
 */
//First admin email address is primary email address!
$email = array();
$email['adminemail'][] = "saif@fistrive.com"; // Email address of administrator(To add another admin email address just copy/paste this line and change the email address)
$email['adminemail'][] = "msoul@live.com";

$email['type'] = MAIL_PHPMAIL; //Use MAIL_SMTP or MAIL_PHPMAIL
$email['copy'] = false; //Send copy mails to all admin's email

//ONLY CHANGE IF YOU ARE USING MAIL_SMTP IN $email['type']!
$email['smtp']['host'] = ''; //Host name or IP address of the mail server
$email['smtp']['port'] = 25; //Port of the mail server
$email['smtp']['username'] = ''; //Leave empty if using anonymous
$email['smtp']['password'] = ''; //Leave empty if using anonymous
$email['smtp']['encrypted'] = false;

//OTHER CONFIGS
$usetemplate = "mmopro";

$COOKIECONF = array(
	'cookiepath' => '',
	'cookiedomain' => '',
);

$USERTIMEOUT = 5;

/**
 * *********************************************************
 * ***************** Core Configurations *******************
 * *********************************************************
 */
$configfile['fullpath'] = "";
$configfile['language'] = "english"; // Language must be lowercase, To see a list of languages check /languages directory
$DEBUG = false; //Shows Debug messages on the footer
$OFFLINE_MAINTENANCE = false;


?>
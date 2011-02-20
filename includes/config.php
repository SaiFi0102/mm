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
//Databases(USE p:<host or ip> in 'host' if u have PHP 5.3 to open persistent connections... saves alot of ram and speed)
$DATABASE_CONNECTION = array(
	'host'	=> 'p:127.0.0.1', //Hostname or IP Address of MySQL server
	'user'	=> 'root', //Username of MySQL server
	'pass'	=> 'root', //Password of the username above
	'port'	=> '3306', //Listening Port of the MySQL server(Default: 3306)
);

define("DBNAME", "realmd"); //Database with the website and realm tables
$LOGON_REALMLIST = "127.0.0.1"; //Realmlist to tell people to set
$LOGON_CRAWLERUSERNAME = "SEARCHENGINECRAWLER"; //Username of any account used for crawlers' login
$LOGON_CRAWLERUSERPASS = "SUPERSECRETCRAWLERONLY"; //and its password

$CONTACTDETAILS = array(
	'ADDRESS'	=> "75/5/3, Lalukhet, 4th Charpai, 6th Khadda Lane,<br />Karachi, Pakistan, 75500",
	'PHONE'		=> "+92(21) 31234567",
);

$REALM = array(); //$REALM[realmid] = array(CH_DATABASE=array,W_DATABASE=array,CAP,IP,PORT,SOAP=array,UNSTUCK=array,DESC_LONG)
$REALM[1] = array(
	'CH_DB'	=> 'character1', //Database with the stored tables
	'W_DB'	=> 'world1', //Database with the stored tables
	'NAME'	=> "Funserver",
	'COLOR'	=> "#5588ff",
	'CAP'	=> 200,
	'IP'	=> '127.0.0.1',
	'PORT'	=> 8085,
	'SOAP'	=> array(
		'user'	=> 'RAREMOTE', //Username of account with gmlevel 4 in UPPERCASE
		'pass'	=> 'REMOTEACCESSONLY', //Password
		'port'	=> 7878, //Port of SOAP server
	),
	'UNSTUCK' => array(
		'alliance'	=> 'shattrath',
		'horde'		=> 'shattrath',
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
$email['adminemail'][] = "saifi0102@gmail.com"; // Email address of administrator(To add another admin email address just copy/paste this line and change the email address)

$email['type'] = MAIL_PHPMAIL; //Use MAIL_SMTP or MAIL_PHPMAIL
$email['copy'] = false; //Send copy mails to all admin's email

//ONLY CHANGE IF YOU ARE USING MAIL_SMTP IN $email['type']!
$email['smtp']['host'] = ''; //Host name or IP address of the mail server
$email['smtp']['port'] = 25; //Port of the mail server
$email['smtp']['username'] = ''; //Leave empty if using anonymous
$email['smtp']['password'] = ''; //Leave empty if using anonymous
$email['smtp']['encrypted'] = false;

//OTHER CONFIGS
$usetemplate = "wow";

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
$DEBUG = true; //Shows Debug messages on the footer
$OFFLINE_MAINTENANCE = false;
define("IPN_ALERTPAY_SECURITY_CODE", "oDOIxWomBxyxaEnQ");
define("IPN_MONEYBOOKERS_PASSWORD", "password");


?>
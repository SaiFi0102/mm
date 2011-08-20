<?php
//################ Page Start Variables ################
define("START_MEMORY", memory_get_usage());
define("START_TIME", microtime(true));

//############### ERRORS/PHP INI #################
error_reporting(E_ALL ^ E_NOTICE);
ini_set("log_errors", 1);
ini_set("display_errors", 0);
if(!$AJAX_PAGE)
{
	ini_set("error_log", dirname(__FILE__)."/administration/logs/php_errors.log");
}
else
{
	ini_set("error_log", dirname(__FILE__)."/administration/logs/ajax_errors.log");
}

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

//################ Constants ################
define("DOC_ROOT", dirname(__FILE__));

//################ Variables ################
$page_name = array();
$USER = array();

//################ Required Resources ################
$REQUIRED_RESOURCES = array_merge(
array(
//Optional
	'iso2country'	=> false,
	'WoW'			=> false,
	'Realm'			=> false,
	'ReCAPTCHA'		=> false,

//The rest are required and may give an error if they are excluded
	'Cookies'		=> true,
	'MySQLi'		=> true,
	'Core'			=> true,
	'Users'			=> true,
	'Templates'		=> true,
), $REQUIRED_RESOURCES);

//################ Include required classes and pages for each page ################
//Common constant and variables
require_once(DOC_ROOT."/includes/common.php");

//Iso to Country array
if($REQUIRED_RESOURCES['iso2country'])
{
	require_once(DOC_ROOT."/includes/iso2country.include.php");
}

//Local configurations
require_once(DOC_ROOT."/includes/config.php");

//Display errors in HTML if debug mode is on
if($DEBUG && !$AJAX_PAGE)
{
	ini_set("display_errors", 1);
}

//If admin did not add the REALMs yet
if(!count($REALM))
{
	exit("No Realms Exists");
}

//Basic non OOP functions
require_once(DOC_ROOT."/includes/functions.php");

//Cookies class
if($REQUIRED_RESOURCES['Cookies'])
{
	require_once(DOC_ROOT."/includes/class/Cookies.class.php");
	$cookies = new Cookies();
}

//Initialize MySQL Connection
if($REQUIRED_RESOURCES['MySQLi'])
{
	require_once(DOC_ROOT."/includes/class/MySQLi.class.php");
	$DB = new MMySQLi($DATABASE_CONNECTION['host'], $DATABASE_CONNECTION['user'], $DATABASE_CONNECTION['pass'], DBNAME, $DATABASE_CONNECTION['port']);
}

//Core related class
if($REQUIRED_RESOURCES['Core'])
{
	require_once(DOC_ROOT."/includes/class/Core.class.php");
	$cms = new Core();
}

//Users related resources
if($REQUIRED_RESOURCES['Users'])
{
	//User class of visitor
	require_once(DOC_ROOT."/includes/class/User.class.php");
	require_once(DOC_ROOT."/includes/class/Ban.class.php");
	$UserSelf = new User(1);
	$UserSelf->LoadUserDataFromDB(true, true, true);
	
	//Account Related
	require_once(DOC_ROOT."/includes/class/Users.class.php");
	$uclass = new Users($UserSelf->UserGlobals(), $USER);
	
	//Display php errors in HTML to administrators
	if((int)$USER['access'] >= 4 && !$AJAX_PAGE) //If user is an administrator
	{
		ini_set("display_errors", 1);
	}
	
	//TODO User System
	date_default_timezone_set('America/New_York');
}

//Templates class
if($REQUIRED_RESOURCES['Templates'])
{
	require_once(DOC_ROOT."/includes/class/Templates.class.php");
	$templates = new Templates($usetemplate);
}

//WoW resources class
if($REQUIRED_RESOURCES['WoW'])
{
	require_once(DOC_ROOT."/includes/class/WoW.class.php");
}

//Realm related class
if($REQUIRED_RESOURCES['Realm'])
{
	require_once(DOC_ROOT."/includes/class/Realm.class.php");
}

//Recaptcha library
if($REQUIRED_RESOURCES['ReCAPTCHA'])
{
	require_once("includes/recaptcha/recaptchalib.php");
}

//################ Maintenance ################
if($OFFLINE_MAINTENANCE && $USER['access'] < 4 && !$AJAX_PAGE)
{
	//Login page and payment handler page should not be affected by maintenance
	if(strpos($_SERVER['PHP_SELF'], 'login.php') === false && strpos($_SERVER['PHP_SELF'], 'payments.php') === false)
	{
		$cms->BannedAccess(true);
		eval($cms->SetPageAccess(ACCESS_ALL));
		$page_name[] = array("Under Maintenance");
		eval($templates->Output("maintenance"));
		exit();
	}
}
?>
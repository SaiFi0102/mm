<?php
//################ Page Start Time ################
define("START_MEMORY", memory_get_usage());
define("START_TIME", microtime(true));
ob_start("ob_gzhandler");

//############### ERRORS/PHP INI #################
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
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
define("TIMENOW", time());
define("DOC_ROOT", dirname(__FILE__));

//################ Variables ################
$page_name = array();
$USER = array();

//################ Include required classes and pages for each page ################
require_once(DOC_ROOT."/includes/common.php");
require_once(DOC_ROOT."/includes/config.php");

if($DEBUG && !$AJAX_PAGE)
{
	ini_set("display_errors", 1);
}
if(!count($REALM))
{
	exit("No Realms Exists");
}

require_once(DOC_ROOT."/includes/mail/swift_required.php");
require_once(DOC_ROOT."/includes/functions.php");
require_once(DOC_ROOT."/includes/class/Cookies.class.php");
$cookies = new Cookies();

//Initialize MySQL Connection
require_once(DOC_ROOT."/includes/class/MySQLi.class.php");
$DB = new MMMySQLi($DATABASE_CONNECTION['host'], $DATABASE_CONNECTION['user'], $DATABASE_CONNECTION['pass'], DBNAME, $DATABASE_CONNECTION['port']);

require_once(DOC_ROOT."/includes/class/Core.class.php");
$cms = new Core();
require_once(DOC_ROOT."/includes/class/Authorization.class.php");
$auth = new Authorization();
require_once(DOC_ROOT."/includes/class/Users.class.php");
$uclass = new Users($auth->UserGlobals(), $USER);
if((int)$USER['access'] >= 4 && !$AJAX_PAGE) //If user is an administrator
{
	ini_set("display_errors", 1);
}
date_default_timezone_set('America/New_York'); //TODO User System
require_once(DOC_ROOT."/includes/class/Templates.class.php");
$templates = new Templates($usetemplate);
require_once(DOC_ROOT."/includes/class/WoW.class.php");
require_once(DOC_ROOT."/includes/class/Realm.class.php");

//################ Functions ################


//################ Maintenance ################
if($OFFLINE_MAINTENANCE && $USER['access'] < 4 && strpos($_SERVER['PHP_SELF'], 'login.php') === false && strpos($_SERVER['PHP_SELF'], 'payments.php') === false)
{
	$cms->BannedAccess(true);
	eval($cms->SetPageAccess(ACCESS_ALL));
	$page_name[] = array("Under Maintenance");
	eval($templates->Output("maintenance"));
	exit();
}
?>
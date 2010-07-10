<?php
//################ Page Start Time ################
define("START_TIME", microtime());

//############### ERRORS/PHP INI #################
ini_set("log_error", 1);
if(!isset($AJAX_PAGE))
{
	ini_set("error_log", dirname(__FILE__)."/administration/logs/php_errors.log");
	ini_set("display_errors", 1); //TODO Debug only
}
else
{
	ini_set("error_log", dirname(__FILE__)."/administration/logs/ajax_errors.log");
	ini_set("display_errors", 0);
	error_reporting(E_ERROR);
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

if(!count($REALM))
{
	exit("No Realms Exists");
}

require_once(DOC_ROOT."/includes/mail/swift_required.php");
require_once(DOC_ROOT."/includes/functions.php");
require_once(DOC_ROOT."/includes/class/Cookies.class.php");
$cookies = new Cookies();

//Initialize MySQL Connections
require_once(DOC_ROOT."/includes/class/MySQL.class.php");
$DB = new MySQL($WEB_DATABASE['host'], $WEB_DATABASE['user'], $WEB_DATABASE['pass'], $WEB_DATABASE['db'], $WEB_DATABASE['port']);
$LOGONDB = new MySQL($LOGON_DATABASE['host'], $LOGON_DATABASE['user'], $LOGON_DATABASE['pass'], $LOGON_DATABASE['db'], $LOGON_DATABASE['port']);
$CHARACTERDB = array();
foreach($REALM as $rid => $arr)
{
	$CHARACTERDB[$rid] = new MySQL($arr['CH_DATABASE']['host'], $arr['CH_DATABASE']['user'], $arr['CH_DATABASE']['pass'], $arr['CH_DATABASE']['db'], $arr['CH_DATABASE']['port']);
}
$WORLDDB = array(); //We wont initialize WORLDDB for realms now... If it is needed it should be initialized then by InitWorldDb(&$WORLDDB, $rid);

require_once(DOC_ROOT."/includes/class/Core.class.php");
$cms = new Core();
require_once(DOC_ROOT."/includes/class/Authorization.class.php");
$auth = new Authorization();
require_once(DOC_ROOT."/includes/class/Users.class.php");
$uclass = new Users($auth->UserGlobals(), $USER);
require_once(DOC_ROOT."/includes/class/Templates.class.php");
$templates = new Templates($usetemplate);
require_once(DOC_ROOT."/includes/class/WoW.class.php");
require_once(DOC_ROOT."/includes/class/Realm.class.php");

//################ Functions ################
function InitWorldDb(&$WORLDDB, $rid)
{
	global $REALM;
	//ALWAYS CHECK IF REALM EXISTS BEFORE INITIALIZING A WORLDDB CONNECTION!
	if(!isset($REALM[$rid]))
	{
		trigger_error("Wrong realm ID", E_USER_ERROR);
	}
	$WORLDDB[$rid] = new MySQL($REALM[$rid]['W_DATABASE']['host'], $REALM[$rid]['W_DATABASE']['user'], $REALM[$rid]['W_DATABASE']['pass'], $REALM[$rid]['W_DATABASE']['db'], $REALM[$rid]['W_DATABASE']['port']);
	return $WORLDDB;
}
?>
<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Resources ################
$REQUIRED_RESOURCES = array(
	'WoW'	=> true,
	'Realm'	=> true,
);

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ General Variables ################
$page_name[] = array("Online Players"=>"online.php");
WoW::getZonesArray();

//################ Page Functions ################
function FetchOnlinePlayers()
{
	global $DB, $REALM;
	
	$query = new Query();
	$query->Select("`characters`")->Columns("*")->Where("`online` <> '0'")->Build();
	$return = MySQLiFetch($DB->query($query, $REALM[$_GET['rid']]['CH_DB']));
		
	return $return;
}

if(empty($_GET['rid']) || empty($REALM[$_GET['rid']]))
{
	$page_name[] = array("Select Realm");
	$tplname = "realm_selection";
}
else
{
	$page_name[] = array($REALM[$_GET['rid']]['NAME']=>$_SERVER['REQUEST_URI']);
	$onlines = FetchOnlinePlayers();
	$tplname = "online";
}

eval($templates->Output($tplname));
?>
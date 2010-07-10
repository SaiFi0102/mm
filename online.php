<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Resources ################ 
WoW::getZonesArray();

//################ General Variables ################
$page_name[] = array("Online Players"=>"online.php");

//################ Constants ################

//################ Page Functions ################
function FetchOnlinePlayers()
{
	global $CHARACTERDB;
	$return = $CHARACTERDB[$_GET['rid']]->Select("*", "characters", "WHERE online <> '0'");
	
	return $return;
}

//################ Template's Output ################
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
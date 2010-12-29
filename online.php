<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

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
	global $DB, $REALM;
	
	$query = new MMQueryBuilder();
	$query->Select("`characters`")->Columns("*")->Where("`online` <> '0'")->Build();
	$return = MMMySQLiFetch($DB->query($query, $REALM[$_GET['rid']]['CH_DB']));
		
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
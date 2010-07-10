<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ Resources ################ 
WoW::getZonesArray();
foreach($REALM as $rid => $rdata)
{
	$rclass[$rid] = new Realm($rid);
}

//################ General Variables ################
$page_name[] = array("My Characters"=>"characters.php");

//################ Constants ################

//################ Page Functions ################


//################ Template's Output ################
foreach($REALM as $rid => $rdata)
{
	$cdata[$rid] = $rclass[$rid]->FetchCharactersByAccountID("", $USER['id']);
}
$CHARACTERLIST_SHOW_TOOLS = true;
$CHARACTERLIST_MUSTBEONLINE = false;
$CHARACTERLIST_MUSTBEOFFLINE = false;
$CHARACTERLIST_SELECTION = false;
eval($templates->Output('characters_list'));
?>
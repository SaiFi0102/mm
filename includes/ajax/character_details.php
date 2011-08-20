<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
set_include_path("../../");
$AJAX_PAGE = true;

//################ Required Data ################
if(!isset($_POST['cid']) || !isset($_POST['rid']) || empty($REALM[$_POST['rid']]))
{
	exit("Error: Please report an administrator!");
}
if(empty($_POST))
{
	exit;
}

//################ Required Resources ################
$REQUIRED_RESOURCES = array();

//################ Required Files ################
require_once("init.php");

//################ Ajax has ALL ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Page Functions ################
$rclass = new Realm($_POST['rid']);
$_cdata = $rclass->FetchCharacterByCharacterID("", $_POST['cid']);

//################ Template's Output ################
$CHARACTERLIST_SHOW_TOOLS = false; //Shows the links for character tools if set to true
$CHARACTERLIST_MUSTBEONLINE = false; //If set to true, it'll prevent it from being selected if the character is offline and if SELECTION is enabled
$CHARACTERLIST_NOT_MUSTBEOLINE = false; //If set to true, it'll prevent it from being selected if the character is online and if SELECTION is enabled
$CHARACTERLIST_RID = $_POST['rid']; //REALMIDs are set in /includes/config.php
$CHARACTERLIST_SELECTION = false; //If set to true ... Ads radio button to select character with FROM variables as: Name=character_selected, Value=345(CharacterID)
eval($templates->Output("character_bit", false, false, false, true));
?>
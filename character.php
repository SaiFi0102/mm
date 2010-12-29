<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ Resources ################ 
WoW::getZonesArray();

//################ General Variables ################
$page_name[] = array("Character"=>"character.php");

//################ Constants ################

//################ Page Functions ################


//################ Template's Output ################
if(empty($_GET['cid']) || empty($_GET['rid']) || empty($REALM[$_GET['rid']]))
{
	//Redirect to characters list
	$REDIRECT_MESSAGE = "No Character Selected";
	$REDIRECT_LOCATION = "characters.php";
	$REDIRECT_INTERVAL = 0;
	$REDIRECT_TYPE = "notice";
	eval($templates->Redirect());
	exit();
}

$rclass = new Realm($_GET['rid']);
$_cdata = $rclass->FetchCharacterByCharacterID("", $_GET['cid']);

$page_name[] = array($_cdata['name']=>"character.php?rid={$_GET['rid']}&cid={$_GET['cid']}");
if($_cdata['account'] == $USER['id'])
{
	$CHARACTERLIST_SHOW_TOOLS = true;
}
else
{
	$CHARACTERLIST_SHOW_TOOLS = false;
}
$CHARACTERLIST_RID = $_GET['rid'];
$CHARACTERLIST_MUSTBEONLINE = false;
$CHARACTERLIST_MUSTBEOFFLINE = false;
$CHARACTERLIST_SELECTION = false;

if(isset($_GET['act']) && $_GET['act'] == "unstuck" && $_cdata['account'] == $USER['id'])
{
	$page_name[] = array("Unstuck"=>$_SERVER['REQUEST_URI']);
	if(isset($_POST['submit']))
	{
		if($_cdata['online'] != 0)
		{
			$cms->ErrorPopulate("You must be logged out to use unstuck tool.");
		}
		if(!$cms->ErrorExists())
		{
			$location = null;
			if(WoW::$arrFactionId[$_cdata['race']] == 1)
			{
				$location = $REALM[$_GET['rid']]['UNSTUCK']['alliance'];
			}
			if(WoW::$arrFactionId[$_cdata['race']] == 2)
			{
				$location = $REALM[$_GET['rid']]['UNSTUCK']['horde'];
			}
			$result = $rclass->ExecuteSoapCommand("tele name {$_cdata['name']} {$location}");
			if(!$result['sent'])
			{
				$cms->ErrorPopulate("There was a problem with the server, please try again later. If this problem persists, please contact an administrator!");
			}
			else
			{
				eval($templates->Output("unstuck_success"));
				exit();
			}
		}
	}
	$binddata = $rclass->FetchCharacterBindDataByCharacterID("", $_GET['cid']);
}

eval($templates->Output('character'));
?>
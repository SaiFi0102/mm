<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("Referrals"=>"refer.php");

//################ Constants ################

//################ Page Functions ################
function GetRefferedPlayersString()
{
	global $LOGONDB, $USER;
	$q = $LOGONDB->Select("(SELECT username FROM account WHERE id=account_mm_extend.accountid) AS username", "account_mm_extend", "WHERE referred='%s'", false, $USER['id']);
	
	$return = null;
	foreach($q as $qz)
	{
		$return .= FirstCharUpperThenLower($qz['username']);
		$return .= ", ";
	}
	$return = substr($return, 0, -2);
	
	return $return;
}

//################ Template's Output ################
$referredbyme = GetRefferedPlayersString();
eval($templates->Output("refer"));
?>
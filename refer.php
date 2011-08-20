<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Resources ################
$REQUIRED_RESOURCES = array();

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ General Variables ################
$page_name[] = array("Referrals"=>"refer.php");

//################ Page Functions ################
function FetchRefferals()
{
	global $DB, $USER;
	$query = new Query();
	$query->Select("`account_referrals`")->Columns(array("*", "(SELECT `username` FROM `account` WHERE `id`=`account_referrals`.`to`)" => "username"))->Where("`by` = '%s'", $USER['id'])->Build();
	$referrals = MySQLiFetch($DB->query($query, DBNAME));
	
	return $referrals;
}

$referredbyme = FetchRefferals();
eval($templates->Output("refer"));
?>
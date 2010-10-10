<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("../init.php");
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("log_errors", 0);

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("ADMIN");

//################ Constants ################

//################ Page Functions ################
print "test";
$old = $LOGONDB->Select("id, points, times_voted", "voting_points", "WHERE date <> '0' LIMIT 5000");
foreach($old as $val)
{
	$LOGONDB->Update(array("date"=>"0"), "voting_points", "WHERE id='%s'", $val['id']);
	$LOGONDB->Insert(array("accountid"=>"'%s'", "votepoints"=>"'%s'", "voted"=>"'%s'"), "account_mm_extend", true, $val['id'], $val['points'], $val['times_voted']);
	print("Updated Vote/Donation Status for account ". $val['id'] ."<br /><br />");
}
unset($old);

//################ Template's Output ################
?>
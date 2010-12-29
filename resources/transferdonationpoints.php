<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("../init.php");
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("log_errors", 0);

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_NONE));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("ADMIN");

//################ Constants ################

//################ Page Functions ################
print "test";
$old = $LOGONDB->Select("itemname", "paypal_payment_info", "WHERE item_given = '1'");
foreach($old as $val)
{
	$CHARACTERDB[1]->Insert(array("guid"=>"'%s'", "donated"=>"'1'"), "character_mm_extend", true, $val['itemname']);
	print("Updated Vote/Domination Status for account ". $val['itemname'] ."<br /><br />");
}
unset($old);

//################ Template's Output ################
?>
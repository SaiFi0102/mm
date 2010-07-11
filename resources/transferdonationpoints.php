<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("../init.php");
ini_set("display_errors", 1);

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ADMIN));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("ADMIN");

//################ Constants ################
function prrrint($str)
{
	print $str;
}

//################ Page Functions ################
print "test";
$old = $LOGONDB->Select("itemname", "paypal_payment_info", "WHERE item_given = '1'");
foreach($old as $val)
{
	$CHARACTERDB[1]->Insert(array("guid"=>"'%s'", "donated"=>"'1'"), "character_mm_extend", true, $val['itemname']);
	prrrint("Updated Vote/Domination Status for account ". $val['itemname'] ."<br /><br />");
}
unset($old);

//################ Template's Output ################
?>
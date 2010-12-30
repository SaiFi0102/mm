<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
set_include_path("../../");
$AJAX_PAGE = true;

//################ Required Files ################
require_once("init.php");
error_reporting(0);

//################ Required Data ################
if(!isset($_POST['rid']) || empty($REALM[$_POST['rid']]))
{
	exit("Error: Please report an administrator!");
}

//################ Ajax has ALL ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Page Functions ################
$rclass = new Realm($_POST['rid']);
$status = $rclass->CheckRealmStatusAndOnlinePlayers();

//################ Template's Output ################
$print = json_encode($status);
print $print;
?>
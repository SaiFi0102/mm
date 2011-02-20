<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
set_include_path("../../");
$AJAX_PAGE = true;

//################ Required Files ################
require_once("init.php");
error_reporting(E_ERROR);

//################ Required Data ################
if(!isset($_POST['sure']))
{
	exit("Error: Please report an administrator!");
}

//################ Ajax has ALL ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Page Functions ################
$status = array();
foreach($REALM as $rid => $rdata)
{
	$rclass = new Realm($rid);
	$status[$rid] = $rclass->CheckRealmStatusAndOnlinePlayers();
	unset($rclass);
}

$print = json_encode($status);
print $print;
?>
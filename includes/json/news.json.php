<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
set_include_path("../../");
$AJAX_PAGE = true;

if(empty($_POST))
{
	exit;
}

//################ Required Files ################
require_once("init.php");

//################ Ajax has ALL ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Page Functions ################
function GetTotalNews()
{
	global $DB;
	$DB->Select("COUNT(id)", "news", "", true);
	return $DB->AffectedRows;
}

switch($_POST)
{
	case "totalonly":
	{
		print GetTotalNews();
	}break;
}

//################ Template's Output ################


?>
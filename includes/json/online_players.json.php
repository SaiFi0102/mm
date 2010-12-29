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
switch($_POST['data'])
{
	case "totalonly":
		print GetTotalOnlinePlayers();
	break;
	
	case "JSONData":
		if(!isset($_POST['ordercolumn']) || !isset($_POST['ordermethod']) || !isset($_POST['limit']))
		{
			exit;
		}
		$fetchnews = FetchNews($_POST['ordercolumn'], $_POST['ordermethod'], $_POST['limit']);
		print json_encode($fetchnews);
	break;
}

//################ Template's Output ################


?>
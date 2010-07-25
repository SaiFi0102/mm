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
	$DB->Select("id", "news", "", true);
	return $DB->AffectedRows;
}
function FetchNews($ordercolumn, $ordermethod, $limit="0,5")
{
	global $DB;
	
	$ordermethod = strtoupper($ordercolumn);
	if($ordermethod != "ASC" || $ordermethod != "DESC")
	{
		$ordermethod = "DESC";
	}
	switch($ordercolumn)
	{
		case "id":
			$ordercolumn = "id";
		break;
		case "title":
			$ordercolumn = "title";
		break;
		case "date":
			$ordercolumn = "date";
		break;
		case "by":
			$ordercolumn = "by";
		break;
		default:
			$ordercolumn = "date";
		break;
	}
	
	$q = $DB->Select("*", "news", "ORDER BY sticky DESC, %s {$ordermethod} LIMIT {$limit}", false, $ordercolumn);
	for($i = 0; $i < count($q); $i++)
	{
		$q[$i]['body'] = str_replace("\r\n", "<br />", $q[$i]['body']);
		$q[$i]['body'] = str_replace("\n", "<br />", $q[$i]['body']);
		$q[$i]['date'] = ConvertMysqlTimestamp($q[$i]['date']);
	}
	return $q;
}
switch($_POST['data'])
{
	case "totalonly":
		print GetTotalNews();
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
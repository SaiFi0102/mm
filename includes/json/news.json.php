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
	
	$query = new MMQueryBuilder();
	$query->Select("`news`")->Columns(array("COUNT(*)"=>"newscount"))->Build();
	$result = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");
	
	return $result['newscount'];
}
function FetchNews($ordercolumn, $ordermethod, $limitstart="0", $limitrows="5")
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
	if($limitrows > 10)
	{
		$limitrows = 10; //Do not allow more than 10 rows in a query
	}
	
	$query = new MMQueryBuilder();
	$query->Select("`news`")->Columns("*")->Order("`sticky` DESC, `%s` %s", $ordercolumn, $ordermethod)->Limit("%s", "%s", $limitstart, $limitrows)->Build();
	$news = MMMySQLiFetch($DB->query($query, DBNAME));	
	
	for($i = 0; $i < count($news); $i++)
	{
		$news[$i]['body'] = str_replace("\r\n", "<br />", $news[$i]['body']);
		$news[$i]['body'] = str_replace("\n", "<br />", $news[$i]['body']);
		$news[$i]['date'] = ConvertMysqlTimestamp($news[$i]['date']);
	}
	return $news;
}
switch($_POST['data'])
{
	case "JSONData":
		if(!isset($_POST['ordercolumn']) || !isset($_POST['ordermethod']) || !isset($_POST['limitstart']) || !isset($_POST['limitrows']))
		{
			exit;
		}
		$totalnews = GetTotalNews();
		$totalnews = array("TotalElements"=>$totalnews);
		
		$fetchnews = FetchNews($_POST['ordercolumn'], $_POST['ordermethod'], $_POST['limitstart'], $_POST['limitrows']);
		$fetchnews = array("MDElements"=>$fetchnews);
		
		print json_encode(array_merge($totalnews, $fetchnews));
	break;
}

//################ Template's Output ################


?>
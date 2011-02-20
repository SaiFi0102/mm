<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ General Variables ################
$page_name[] = array("News"=>"index.php");

//################ Page Functions ################
function FetchNews($start=0, $limit=5)
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`news`")->Columns("*")->Order("`sticky` DESC, `date` DESC")->Limit($start, $limit)->Build();
	$return = MMMySQLiFetch($DB->query($query, DBNAME));
	
	return $return;
}
function FetchTotalNews()
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`news`")->Columns(array("COUNT(*)"=>"numrows"))->Build();
	$return = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");
	
	return $return['numrows'];
}
function FetchNewsById($nid)
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`news`")->Columns("*")->Where("`id` = '%s'", $nid)->Build();
	$return = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");
	
	return $return;
}
function FetchComments($limit)
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`news_comments`")->Columns("*")->Where("`newsid` = '%s'", $_GET['id'])->Order("`date` ASC")->Limit($limit)->Build();
	$return = MMMySQLiFetch($DB->query($query, DBNAME));
	
	return $return;
}
//################ Template's Output ################
if(isset($_GET['id']))
{
	$news = FetchNewsById($_GET['id']);
	$comments = FetchComments("5");
	if($news)
	{
		$page_name[] = array($news['title']=>$_SERVER['REQUEST_URI']);
		eval($templates->Output('news_id'));
	}
	else
	{
		//TODO If no news found
	}
}
else
{
	$news = FetchNews();
	$numnews = FetchTotalNews();
	eval($templates->Output('news_home'));
}
?>
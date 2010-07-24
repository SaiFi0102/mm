<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");
require_once("includes/class/Pager.class.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("News"=>"index.php");

//################ Constants ################

//################ Page Functions ################
function FetchNews($limit = "0,5")
{
	global $DB;
	$q = $DB->Select(array('*', '(SELECT COUNT(*) FROM news_comments WHERE newsid = news.id) AS commentcount'),
	"news", "ORDER BY sticky DESC,date ASC LIMIT {$limit}", false);
	return $q;
}
function FetchNewsById($nid)
{
	global $DB;
	$q = $DB->Select("*", "news", "WHERE id='%s'", true, $nid);
	return $q;
}
function FetchComments($limit)
{
	global $DB;
	$q = $DB->Select("*", "news_comments", "WHERE newsid = '%s' ORDER BY date ASC LIMIT {$limit}", false, $_GET['id']);
	return $q;
}
//################ Template's Output ################
if(isset($_GET['id']))
{
	//TODO news_id
	/*$news = FetchNewsById($_GET['id']);
	$comments = FetchComments("0,5");
	if($news)
	{
		$page_name[] = array($news['title']=>$_SERVER['REQUEST_URI']);
		eval($templates->Output('news_id'));
	}
	else
	{
		//TODO If no news found
	}*/
}
else
{
	eval($templates->Output('news_home'));
}
?>
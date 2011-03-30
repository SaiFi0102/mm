<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ General Variables ################
$page_name[] = array("Player PvP Statistics"=>"pvp.php");
$template = "pvp_stats"; //The template to use for the page. Dont include .tpl inside quotes.

//################ Page Functions ################
$query = new MMQueryBuilder();
$query->Select("`character_pvp`")->Columns(array(
	"`totalkills`", "`currentkills`", "`totaldeaths`", "`currentdeaths`", "`groupkills`",
	"(SELECT `name` FROM `characters` WHERE `guid`=`character_pvp`.`guid`)"=>"`charactername`",
	"((`totalkills`/SQRT(`totaldeaths`))*(`totalkills`/(`totaldeaths`+1)))+`killstreak`"=>"`score`"))
->Order("`score` DESC")->Limit("100")->Build();
$top_pvp = MMMySQLiFetch($DB->query($query, $REALM['1']['CH_DB']));
eval($templates->Output($template));
?>
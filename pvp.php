<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("Player PvP Statistics"=>"pvp.php");
$template = "pvp_stats"; //The template to use for the page. Dont include .tpl inside quotes.

//################ Constants ################

//################ Page Functions ################

//################ Template's Output ################
$query = new MMQueryBuilder();
$query->Select("`character_pvp`")->Columns(array("*", "(SELECT `name` FROM `characters` WHERE `guid`=`character_pvp`.`guid`)"=>"`charactername`"))
->Order("`totalkills` DESC")->Limit("100")->Build();
$top_pvp = MMMySQLiFetch($DB->query($query, $REALM['1']['CH_DB']));
eval($templates->Output($template));
?>
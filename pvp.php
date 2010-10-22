<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

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
$top_pvp = $CHARACTERDB[1]->Select("*, (SELECT name FROM characters WHERE guid=character_pvpstats.guid) AS charactername", "character_pvpstats", "ORDER BY totalkills LIMIT 100");
eval($templates->Output($template));
?>
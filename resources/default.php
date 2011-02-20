<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ General Variables ################
$page_name[] = array("Page Name"=>"default.php");
$template = "template"; //The template to use for the page. Dont include .tpl inside quotes.

//################ Constants ################

//################ Page Functions ################
eval($templates->Output($template));
?>
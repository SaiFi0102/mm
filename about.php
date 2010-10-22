<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("About Us"=>"about.php");
$template = "about"; //The template to use for the page. Dont include .tpl inside quotes.

//################ Constants ################

//################ Page Functions ################

//################ Template's Output ################
eval($templates->Output($template));
?>
<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("QuadroPop"=>$_SERVER['REQUEST_URI']);
$template = "quadropop"; //The template to use for the page. Dont include .tpl inside quotes.

//################ Constants ################

//################ Page Functions ################
if(!empty($_GET['gameid']))
{
	
}
else
{
	$template = "quadropop_wgi";
}

//################ Template's Output ################
eval($templates->Output($template));
?>
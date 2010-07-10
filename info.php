<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ADMIN));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("Website Debug"=>$_SERVER['REQUEST_URI']);
$template = "info"; //The template to use for the page. Dont include .tpl inside quotes.

//################ Constants ################

//################ Page Functions ################
if(isset($_GET['LOGIN'])) $loggedin = $auth->Login("chmun", "public123", false);
if(isset($_GET['LOGOUT'])) $loggedout = $auth->Logout($USER['id']);
$onlines = $auth->FetchOnlineUsers();

//################ Template's Output ################
eval($templates->Output($template));
?>
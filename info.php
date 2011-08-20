<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Resources ################
$REQUIRED_RESOURCES = array(
	'WoW'			=> true,
	'Realm'			=> true,
	'iso2country'	=> true,
);

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_ADMIN));

//################ General Variables ################
$page_name[] = array("Website Debug"=>$_SERVER['REQUEST_URI']);
$template = "info"; //The template to use for the page. Dont include .tpl inside quotes.

//################ Page Functions ################
if(isset($_GET['LOGIN'])) $loggedin = $UserSelf->Login("chmun", "public123", false);
if(isset($_GET['LOGOUT'])) $loggedout = $UserSelf->Logout($USER['id']);
$onlines = array();

eval($templates->Output($template));
?>
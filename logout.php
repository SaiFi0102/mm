<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("Logging Out");

//################ Constants ################

//################ Page Functions ################
$auth->Logout($USER['id']);

//################ Template's Output ################
$REDIRECT_MESSAGE = "You were successfully logged out!";
$REDIRECT_LOCATION = "index.php";
$REDIRECT_INTERVAL = 2000;
$REDIRECT_TYPE = "success";
eval($templates->Redirect());
?>
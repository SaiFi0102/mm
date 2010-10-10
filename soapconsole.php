<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_NONE));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("SOAP Remote Console"=>"soapconsole.php");

//################ Constants ################

//################ Page Functions ################

//################ Template's Output ################
eval($templates->Output("soapconsole"));
?>
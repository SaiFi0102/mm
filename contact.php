<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("Contact Us"=>$_SERVER['REQUEST_URI']);

//################ Constants ################

//################ Page Functions ################
if(isset($_POST['submit']))
{
	$userstr = null;
	foreach($USER as $ukey => $udata)
	{
		$userstr .= "[$ukey] => $udata\r\n";
	}
	
	$body  = $_POST['body'];
	$body .= "\r\n\r\nDetails:\r\n".$userstr;
	
	$result = SendEmail($email['adminemail'], "{$USER['username']} contacted for '{$_POST['reason']}'", $body, array($_POST['email'] => $USER['username']));
	if($result['result'])
	{
		$success = true;
	}
	else
	{
		$error = true;
	}
}

//################ Template's Output ################
eval($templates->Output("contact"));
?>
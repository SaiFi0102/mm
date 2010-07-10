<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("Account Management"=>"account.php");

//################ Constants ################
//################ Page Functions ################
function UpdatePassword()
{
	global $LOGONDB, $USER, $cookies;
	
	$newpass = Sha1Pass($USER['username'], $_POST['newpassword']);
	$q = $LOGONDB->Query("UPDATE account SET sha_pass_hash = '%s' WHERE id = '%s'", $newpass, $USER['id']);
	if($q)
	{
		$cookies->SetCookie("username", $USER['username'], false);
		$cookies->SetCookie("password", $newpass, false);
	}
	return $q;
}

function UpdateClient()
{
	global $LOGONDB, $USER;
	
	$newclient = FixExpansionFlags($_POST['newflags']);
	$q = $LOGONDB->Query("UPDATE account SET expansion = '%s' WHERE id = '%s'", $newclient, $USER['id']);
	return $LOGONDB->AffectedRows;
}

if(isset($_POST['submit']))
{
	//If nothing changed
	if(empty($_POST['newpassword']) && $_POST['newflags'] == $USER['expansion'])
	{
		$cms->ErrorPopulate("You did not change anything.");
		$cms->ErrorStopList();
	}
	else
	{
		//Something changed
		$passchange = false;
		$flagschange = false;
		//if no current password is there
		if(empty($_POST['currentpassword']))
		{
			$cms->ErrorPopulate("You must enter your current password to change your account information.");
		}
		
		//If password changed
		if(!empty($_POST['newpassword']))
		{
			$passchange = true;
			//Password Check
			if($_POST['newpassword'] == $USER['username'])
			{
				$cms->ErrorPopulate("Your new password cannot be as same as your username.");
			}
			if(strlen($_POST['newpassword']) < 5)
			{
				$cms->ErrorPopulate("Your new password must contain atleast 5 characters, please use a stronger password.");
			}
		}
		
		//If flags changed
		if($_POST['newflags'] != $USER['expansion'])
		{
			$flagschange = true;
		}
		
		//Update from DB
		if(!$cms->ErrorExists())
		{
			if($flagschange) $cof = UpdateClient();
			if($passchange) $cop = UpdatePassword();
			$page_name[] = array("Success");
			eval($templates->Output('account_success'));
			exit();
		}
	}
}

//################ Template's Output ################
eval($templates->Output('account_form'));

?>
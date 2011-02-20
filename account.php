<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ General Variables ################
$page_name[] = array("Account Management"=>"account.php");

//################ Page Functions ################
function UpdateAccount($changeflags, $changepassword)
{
	global $DB, $USER, $cookies;
	
	//Set new data variables
	$currentpasshash = Sha1Pass($USER['username'], $_POST['currentpassword']);
	if($changeflags)	$newclient = FixExpansionFlags($_POST['newflags']);
	if($changepassword)	$newpass = Sha1Pass($USER['username'], $_POST['newpassword']);
	
	$query = new MMQueryBuilder();
	$query->Update("`account`")->Where("`id` = '%s' AND `sha_pass_hash` = '%s'", $USER['id'], $currentpasshash);
	
	if($changeflags)
	{
		$query->AddColumns(array("`expansion`"=>"'%s'"), $newclient);
	}
	if($changepassword)
	{
		$query->AddColumns(array("`sha_pass_hash`"=>"'%s'", "`sessionkey`"=>"''", "`v`"=>"''", "`s`"=>"''"), $newpass);
	}
	$query->Build();
	
	$result = $DB->query($query, DBNAME);
	//If password was successfully updated. set new cookies
	if($result && $DB->affected_rows && $changepassword)
	{
		$cookies->SetCookie("username", $USER['username'], false);
		$cookies->SetCookie("password", $newpass, false);
	}
	
	return $DB->affected_rows;
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
		else
		{
			if(Sha1Pass($USER['username'], $_POST['currentpassword']) != $USER['sha_pass_hash'])
			{
				$cms->ErrorPopulate("The current password you entered was incorrect.");
			}
		}
		
		//If password changed
		if(!empty($_POST['newpassword']))
		{
			$passchange = true;
			//Password Check
			if($_POST['newpassword'] == $USER['username'])
			{
				$cms->ErrorPopulate("Your new password cannot be same as your username.");
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
			$update_result = UpdateAccount($flagschange, $passchange);
		}
	}
}

eval(($templates->Output('account_form')));

?>
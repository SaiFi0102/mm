<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_UNREGISTERED));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("Login"=>"login.php");

//################ Constants ################

//################ Page Functions ################
function LogIn($user, $pass, $lifetime)
{
	global $auth;
	if($auth->Login($user, $pass, $lifetime))
	{
		return true;
	}
	else
	{
		return false;
	}
}

//################ Template's Output ################
if(isset($_POST['submit']))
{
	//From page
	if(isset($_GET['ref']))
	{
		$ref = urldecode($_GET['ref']);
	}
	else
	{
		$ref = false;
	}
	
	//Check for errors
	if(!isset($_POST['username']) || !isset($_POST['password']))
	{
		$cms->ErrorPopulate("An unknown error occurred, please contact an administrator.");
		$cms->ErrorStopList();
	}
	if(empty($_POST['username']))
	{
		$cms->ErrorPopulate("You did not enter your username.");
	}
	if(empty($_POST['password']))
	{
		$cms->ErrorPopulate("You did not enter your password.");
	}
	//Remember ME?
	if(isset($_POST['remember']))
	{
		$remember = true;
	}
	else
	{
		$remember = false;
	}
	//Login if no errors found
	if(!$cms->ErrorExists())
	{
		if(!LogIn($_POST['username'], $_POST['password'], $remember))
		{
			$cms->ErrorPopulate("You entered an incorrect username or password.");
		}
		else
		{
			if($ref)
			{
				$rdloc = $ref;
				if(strpos($rdloc, "register") !== false || strpos($rdloc, "logout") !== false || strpos($rdloc, "login") !== false)
				{
					$rdloc = "index.php";
				}
				if(preg_match("#(.+)://#i", $rdloc))
				{
					$rdloc = "index.php";
				}
			}
			else
			{
				$rdloc = "index.php";
			}
			$page_name = array();
			$page_name[] = array("Logging in");
			$REDIRECT_MESSAGE = "You were successfully logged in";
			$REDIRECT_LOCATION = $rdloc;
			$REDIRECT_INTERVAL = 2000;
			$REDIRECT_TYPE = "success";
			eval($templates->Redirect());
			exit();
		}
	}
}
$cms->ErrorSetHeading("The following errors occured while trying to log in.");
eval($templates->Output('login'));
?>
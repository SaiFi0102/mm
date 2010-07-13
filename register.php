<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");
require_once("includes/recaptcha/recaptchalib.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_UNREGISTERED));

//################ Resources ################ 

//################ General Variables ################

//################ Constants ################

//################ Page Functions ################
function FinalRegister($username, $password, $email, $flags)
{
	global $LOGONDB, $cookies;
	
	$sha_pass = Sha1Pass($username, $password);
	
	$insertarray = array(
	"username"		=> "'%s'",
	"sha_pass_hash"	=> "'%s'",
	"email"			=> "'%s'",
	"expansion"		=> "'%s'",
	"gmlevel"		=> "'0'",
	"last_ip"		=> "'{$_SERVER['REMOTE_ADDR']}'",
	);
	
	//Insert into account table
	$LOGONDB->Insert($insertarray, "account", false, $username, $sha_pass, $email, FixExpansionFlags($flags));
	$return = $LOGONDB->AffectedRows;
	
	if($return)
	{
		//Insert into account_mm_extend table
		$accountid = $LOGONDB->Select("id", "account", "WHERE username='%s' AND sha_pass_hash='%s'", true, $username, $sha_pass);
		$LOGONDB->Insert(array("accountid"=>"'%s'"), "account_mm_extend", false, $accountid['id']);
	
		//Login
		$cookies->SetCookie("username", $username, false);
		$cookies->SetCookie("password", $sha_pass, false);
	}
	
	return $return;
}
function GenerateAndSendPasswordReset()
{
	global $LOGONDB;
	$resetcode = RandomCharacters(20);
	
	$emailcheck = $LOGONDB->Select("id, username", "account", "WHERE email='%s'", true, $_POST['email']);
	if(!$LOGONDB->AffectedRows)
	{
		return false;
	}
	
$emailbody = "Dear ".FirstCharUpperThenLower($emailcheck['username']).",

We've received a request to reset your password, please follow the link below to complete this process
".$GLOBALS['cms']->config['websiteurl']."/register.php?act=reset&resetcode={$resetcode}&uid={$emailcheck['id']}

If you haven't made this request please follow the link below to cancel the request
".$GLOBALS['cms']->config['websiteurl']."/register.php?act=cancelreset&resetcode={$resetcode}&uid={$emailcheck['id']}

Regards,
Domination WoW Staff";
	
	$LOGONDB->Update(array("resetcode"=>"'%s'"), "account_mm_extend", "WHERE accountid='%s'", $resetcode, $emailcheck['id']);
	SendEmail($_POST['email'], "Instructions to reset your password", $emailbody);
	return true;
}
function ResetPassword()
{
	global $LOGONDB;
	$data = $LOGONDB->Select("*", "account","LEFT JOIN account_mm_extend ON account.id = account_mm_extend.accountid WHERE id='%s'", true, $_GET['uid']);
	if(!$LOGONDB->AffectedRows)
	{
		return false;
	}
	if(empty($data['resetcode']))
	{
		return false;
	}
	if($_GET['resetcode'] != $data['resetcode'])
	{
		return false;
	}
	
	$newpass = strtolower(RandomCharacters(rand(8,12)));
	
$emailbody = "Dear ".FirstCharUpperThenLower($data['username']).",

Your password has been successfully changed to:
{$newpass}

Please login as soon as possible and change the password to your own choice from the link below
".$GLOBALS['cms']->config['websiteurl']."/account.php

Regards,
Domination WoW Staff";
	
	$LOGONDB->Update(array("sha_pass_hash"=>"'%s'"), "account", "WHERE id='%s'", Sha1Pass($data['username'], $newpass), $_GET['uid']);
	$LOGONDB->Update(array("resetcode"=>"''"), "account_mm_extend", "WHERE accountid = '%s'", $_GET['uid']);
	SendEmail($data['email'], "Your new password", $emailbody);
	return $newpass;
}
function RemoveResetCode()
{
	global $LOGONDB;
	if(empty($_GET['resetcode']))
	{
		return false;
	}
	$LOGONDB->Update(array("resetcode"=>"''"), "account_mm_extend", "WHERE accountid='%s' AND resetcode='%s'", $_GET['uid'], $_GET['resetcode']);
	return $LOGONDB->AffectedRows;
}

//################ Template's Output ################
switch($_GET['act'])
{
	case "retrieve":
		$page_name[] = array("Retrieve Password"=>$_SERVER['REQUEST_URI']);
		
		if(isset($_POST['submit']))
		{
			$message = GenerateAndSendPasswordReset();
			if(!$message)
			{
				$cms->ErrorPopulate("The email address you entered does not exits, please enter your correct email address.");
			}
			
			if(!$cms->ErrorExists())
			{
				eval($templates->Output("register_retrieve_success"));
				exit();
			}
		}
		eval($templates->Output("register_retrieve"));
	break;
	
	case "reset":
		$page_name[] = array("Reset Password"=>$_SERVER['REQUEST_URI']);
		if(empty($_GET['uid']))
		{
			$cms->ErrorPopulate("No userid was provided, please follow the link from the email you received.");
		}
		if(!$cms->ErrorExists())
		{
			$message = ResetPassword();
			if($message == false)
			{
				$cms->ErrorPopulate("Either the userid or reset confirmation code was incorrect, please try again or follow the link provided in the email.");
			}
			if($message != false)
			{
				$NEWPASSWORD = $message;
				eval($templates->Output("register_reset_success"));
				exit();
			}
		}
		eval($templates->Output("register_reset"));
	break;
	
	case "cancelreset":
		$page_name[] = array("Cancel Password Reset Request");
		if(isset($_GET['uid']) && isset($_GET['resetcode']))
		{
			$removal = RemoveResetCode();
			if($removal)
			{
				$page_name[] = array("Success");
				eval($templates->Output("register_cancelreset_success"));
				exit();
			}
			else
			{
				$page_name[] = array("Error");
				eval($templates->Output("register_cancelreset_error"));
				exit();
			}
		}
		else
		{
			$REDIRECT_MESSAGE = "No password reset code was given. Please follow the link from the email address!";
			$REDIRECT_LOCATION = "index.php";
			$REDIRECT_INTERVAL = 5000;
			$REDIRECT_TYPE = "error";
			eval($templates->Redirect());
			exit();
		}
	break;
	
	default:
		$page_name[] = array("Register"=>$_SERVER['REQUEST_URI']);
		if(isset($_POST['submit']))
		{
			//Check for errors
			if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['confirmpassword']) || !isset($_POST['email']) || !isset($_POST['confirmemail']) || !isset($_POST['flags']))
			{
				$cms->ErrorPopulate("An unknown error occurred, please contact an administrator.");
				$cms->ErrorStopList();
			}
			
			//Username check
			$usercheck = CheckUsername($_POST['username']);
			if($usercheck)
			{
				if($usercheck == USERNAME_EMPTY)
				{
					$cms->ErrorPopulate("You did not enter a username.");
				}
				if($usercheck == USERNAME_ILLEGAL_CHARACTER)
				{
					$cms->ErrorPopulate("Your desired username contained illegal characters, please remove them and try again.");
				}
				if($usercheck == USERNAME_ILLEGAL_SPACE)
				{
					$cms->ErrorPopulate("Your desired username contained spaces, please remove them and try again.");
				}
				if($usercheck == USERNAME_LENTH_ABOVE)
				{
					$cms->ErrorPopulate("Your desired username cannot contain more than {$cms->config['usermaxlen']} characters, please try another.");
				}
				if($usercheck == USERNAME_LENTH_BELOW)
				{
					$cms->ErrorPopulate("Your desired username must contain atleast {$cms->config['userminlen']} characters, please try another.");
				}
				if($usercheck == USERNAME_EXISTS)
				{
					$cms->ErrorPopulate("Your desired username is already in use, please try another.");
				}
			}
			
			//Password Check
			if(empty($_POST['password']) || $_POST['password'] == null)
			{
				$cms->ErrorPopulate("You did not enter a password.");
			}
			if($_POST['password'] == $_POST['username'])
			{
				$cms->ErrorPopulate("Your desired password cannot be as same as your username.");
			}
			if($_POST['password'] != $_POST['confirmpassword'])
			{
				$cms->ErrorPopulate("Your password must match the confirmation password.");
			}
			if(strlen($_POST['password']) < 5)
			{
				$cms->ErrorPopulate("Your password must contain atleast 5 characters, please use a stronger password.");
			}
			
			//Email check
			$emailcheck = CheckEmail($_POST['email'], $_POST['confirmemail']);
			if($emailcheck)
			{
				if($emailcheck == EMAIL_EMPTY)
				{
					$cms->ErrorPopulate("You did not enter your email address.");
				}
				if($emailcheck == EMAIL_FORMAT)
				{
					$cms->ErrorPopulate("The email address you entered is incorrect, please make sure your email has a correct format(example@domain.tld).");
				}
				if($emailcheck == EMAIL_CONFIRM)
				{
					$cms->ErrorPopulate("The confirmation email address you entered does not match your email address.");
				}
				if($emailcheck == EMAIL_EXISTS)
				{
					$cms->ErrorPopulate("The email you entered is already in use with another account, if you forgot your password please <a href='register.php?action=forgot'>click here</a>");
				}
			}
			
			//Captcha Check
			$resp = recaptcha_check_answer($cms->config['captchaprivkey'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if(!$resp->is_valid)
			{
				$cms->ErrorPopulate("The characters you entered from the verification image was incorrect, please try again.");
			}
			
			//If no error exists, try to register
			if(!$cms->ErrorExists())
			{
				$register = FinalRegister($_POST['username'], $_POST['password'], $_POST['email'], $_POST['flags']);
				if($register)
				{
					$page_name[] = array("Success");
					$REDIRECT_MESSAGE = "You account was successfuly created, you can now login and play online!";
					$REDIRECT_LOCATION = "index.php";
					$REDIRECT_INTERVAL = 5000;
					$REDIRECT_TYPE = "success";
					eval($templates->Redirect());
					exit();
				}
				else
				{
					$cms->ErrorPopulate("There was an error with the server, please try again later or contact an administrator if this problem persists.");
				}
			}
		}
		$cms->ErrorSetHeading("The following errors occured while you were trying to register.");
		eval($templates->Output("register_form"));
	break;
}
?>
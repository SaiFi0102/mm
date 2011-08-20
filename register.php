<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Resources ################
$REQUIRED_RESOURCES = array(
	'iso2country' => true,
	'ReCAPTCHA' => true,
);

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_UNREGISTERED));

//################ Page Functions ################
function FinalRegister($username, $password, $email, $countrycode, $sq1, $sq2, $sa1, $sa2)
{
	global $cookies, $DB;
	
	$sha_pass = Sha1Pass($username, $password);
	
	$query = new Query();
	$query->Insert("`account`")->Columns(array(
		"`username`"	=> "'%s'",
		"`sha_pass_hash`"=> "'%s'",
		"`email`"		=> "'%s'",
		"`expansion`"	=> "'3'",
		"`last_ip`"		=> "'%s'",
	), $username, $sha_pass, $email, GetIp())
	->Build();
	
	//Insert into account table
	$DB->query($query, DBNAME);
	$return = $DB->affected_rows;
	
	if($return)
	{
		//Find the account id of the new account
		$query = new Query();
		$query->Select("`account`")->Columns("`id`")->Where("`username` = '%s' AND `sha_pass_hash` = '%s'", $username, $sha_pass)->Build();
		$id = MySQLiFetch($DB->query($query, DBNAME), "onerow: 1");
		
		//Insert extra details into account_mm_extend table
		$query = new Query();
		$query->Insert("`account_mm_extend`")->Columns(array(
			"`id`"		=> "'%s'",
			"`countrycode`"		=> "'%s'",
			"`secretquestion1`"	=> "'%s'",
			"`secretquestion2`"	=> "'%s'",
			"`secretanswer1`"	=> "'%s'",
			"`secretanswer2`"	=> "'%s'",
		), $id['id'], $countrycode, $sq1, $sq2, $sa1, $sa2)
		->Build();
		
		$DB->query($query, DBNAME);
	
		//Login
		$cookies->SetCookie("username", $username, true);
		$cookies->SetCookie("password", $sha_pass, true);
	}
	
	return $return;
}
function GenerateAndSendPasswordReset()
{
	global $DB, $cms;
	$resetcode = RandomCharacters(20);
	
	//Query
	$query = new Query();
	$query->Select("`account`")->Columns(array("`id`", "`username`"))->Where("`email` = '%s'", $_POST['email'])->Build();
	$result = $DB->query($query, DBNAME);
	if($result->num_rows < 1)
	{
		return false;
	}
	//Userdata
	$emailcheck = MySQLiFetch($result, "onerow: 1");
	
$emailbody = "Dear ".FirstCharUpperThenLower($emailcheck['username']).",

We've received a request to reset your password, please follow the link below to complete this process.
".$GLOBALS['cms']->config['websiteurl']."/register.php?act=reset&resetcode={$resetcode}&uid={$emailcheck['id']}

If you haven't made this request please follow the link below to cancel the request.
".$GLOBALS['cms']->config['websiteurl']."/register.php?act=cancelreset&resetcode={$resetcode}&uid={$emailcheck['id']}

Regards,
{$cms->config['websitename']} Staff.";
	
	$query = new Query();
	$query->Update("`account_mm_extend`")->Columns(array("`resetcode`"=>"'%s'"), $resetcode)->Where("`id` = '%s'", $emailcheck['id'])->Build();
	$DB->query($query, DBNAME);
	
	//Send email for reset intructions
	SendEmail($_POST['email'], "Instructions to reset your password", $emailbody);
	return true;
}
function ResetPassword()
{
	global $DB, $cms;
	
	$query = new Query();
	$query->Select("`account`")->Columns("*")->Join("`account_mm_extend`", "LEFT")->JoinOn("`account`.`id`", "`account_mm_extend`.`id`")
	->Where("`id` = '%s'", $_GET['uid'])->Build();
	$result = $DB->query($query, DBNAME);
	
	if($result->num_rows < 1)
	{
		return false;
	}
	$data = MySQLiFetch($result, "onerow: 1");
	
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
{$cms->config['websitename']} Staff";
	$query = new Query();
	$query->Update("`account`")->Columns(array("`sha_pass_hash`"=>"'%s'", "`sessionkey`"=>"''", "`v`"=>"''", "`s`"=>"''"), Sha1Pass($data['username'], $newpass))
	->Where("`id` = '%s'", $_GET['uid'])->Build();
	$DB->query($query, DBNAME);
	
	$query = new Query();
	$query->Update("`account_mm_extend`")->Columns(array("`resetcode`"=>"''"))->Where("`id` = '%s'", $_GET['uid'])->Build();
	$DB->query($query, DBNAME);
	
	SendEmail($data['email'], "Your new password", $emailbody);
	return $newpass;
}
function RemoveResetCode()
{
	global $DB;
	if(empty($_GET['resetcode']))
	{
		return false;
	}
	
	$query = new Query();
	$query->Update("`account_mm_extend`")->Columns(array("`resetcode`"=>"''"))->Where("`id` = '%s' AND `resetcode` = '%s'", $_GET['uid'], $_GET['resetcode'])->Build();
	$DB->query($query, DBNAME);
	
	return $DB->affected_rows;
}

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
		if(!$cms->ErrorExists() && isset($_GET['resetcode']))
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
			if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['confirmpassword']) || !isset($_POST['email']) || !isset($_POST['countrycode']) || !isset($_POST['sq1']) || !isset($_POST['sq2']) || !isset($_POST['sa1']) || !isset($_POST['sa2']))
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
					$cms->ErrorPopulate("Your entered username contained illegal characters, please remove them and try again.");
				}
				if($usercheck == USERNAME_ILLEGAL_SPACE)
				{
					$cms->ErrorPopulate("Your entered username contained spaces, please remove them and try again.");
				}
				if($usercheck == USERNAME_LENTH_ABOVE)
				{
					$cms->ErrorPopulate("Your entered username cannot contain more than {$cms->config['usermaxlen']} characters, please try another.");
				}
				if($usercheck == USERNAME_LENTH_BELOW)
				{
					$cms->ErrorPopulate("Your entered username must contain atleast {$cms->config['userminlen']} characters, please try another.");
				}
				if($usercheck == USERNAME_EXISTS)
				{
					$cms->ErrorPopulate("Your entered username is already in use, please try another.");
				}
			}
			
			//Password Check
			if(empty($_POST['password']) || $_POST['password'] == null)
			{
				$cms->ErrorPopulate("You did not enter a password.");
			}
			if($_POST['password'] == $_POST['email'])
			{
				$cms->ErrorPopulate("Your password cannot be the same as your email address.");
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
			$emailcheck = CheckEmail($_POST['email']);
			if($emailcheck)
			{
				if($emailcheck == EMAIL_EMPTY)
				{
					$cms->ErrorPopulate("You did not enter your email address.");
				}
				if($emailcheck == EMAIL_LENTH_ABOVE)
				{
					$cms->ErrorPopulate("Your email address can not be longer than 64 characters.");
				}
				if($emailcheck == EMAIL_FORMAT)
				{
					$cms->ErrorPopulate("The email address you entered is incorrect, please make sure your email is in the correct format(example@domain.tld).");
				}
				if($emailcheck == EMAIL_ILLEGAL_SPACE)
				{
					$cms->ErrorPopulate("The email address you entered contained spaces. Please enter your correct email address.");
				}
				if($emailcheck == EMAIL_EXISTS)
				{
					$cms->ErrorPopulate("The email you entered is already in use with another account, if you forgot your password please <a href='register.php?act=retrieve'>click here</a>.");
				}
			}
			
			//Country Check
			if($_POST['countrycode'] == "XX" || empty($_POST['countrycode']))
			{
				$cms->ErrorPopulate("You did not select a country. Please select your country.");
			}
			if(!array_key_exists($_POST['countrycode'], $ISO2COUNTRY))
			{
				$cms->ErrorPopulate("You selected an invalid country. Please select your country.");
			}
			
			//Secret Question Check
			if(!array_key_exists($_POST['sq1'], $SECRETQUESTIONS['1'])) //Secret Question 1
			{
				$cms->ErrorPopulate("You selected an invalid Secret Question 1. Please select the Secret Question again from the list.");
			}
			if(!array_key_exists($_POST['sq2'], $SECRETQUESTIONS['2'])) //Secret Question 2
			{
				$cms->ErrorPopulate("You selected an invalid Secret Question 2. Please select the Secret Question again from the list.");
			}
			
			//Secret Answer Check
			if(strlen($_POST['sa1']) < 3 || strlen($_POST['sa2']) < 3)
			{
				$cms->ErrorPopulate("Your Secret Question's answer must have atleast 3 characters or more. Please enter a stronger answer.");
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
				$register = FinalRegister($_POST['username'], $_POST['password'], $_POST['email'], $_POST['countrycode'], $_POST['sq1'], $_POST['sq2'], $_POST['sa1'], $_POST['sa2']);
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
		$cms->ErrorSetHeading("The following errors occured while trying to register.");
		$countrylisthtml = BuildCountryListHTML();
		eval($templates->Output("register_form"));
	break;
}
?>
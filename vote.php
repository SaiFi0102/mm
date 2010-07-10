<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
if(empty($_GET['act']))
{
	eval($cms->SetPageAccess(ACCESS_ALL));
}
else
{
	eval($cms->SetPageAccess(ACCESS_REGISTERED));
}

//################ Resources ################
WoW::getZonesArray();

//################ General Variables ################
$page_name[] = array("Vote"=>"vote.php");

//################ Constants ################
define("RPPV", $cms->config['rppv']);

//################ Page Functions ################
//VOTE SPEND PAGE
function ModifyVotePoints($accountid, $amount)
{
	global $LOGONDB;
	if($amount < 1)
	{
		$times = 0;
	}
	else
	{
		$times = 1;
	}
	$LOGONDB->Update(array("votepoints"=>"votepoints + '%s'", "voted"=>"voted + '{$times}'"), "account_mm_extend", "WHERE accountid = '%s'", $amount, $accountid);
	return $LOGONDB->AffectedRows;
}
function FetchVoteRewards($rid)
{
	global $DB;
	$return = $DB->Select("*", "rewards_voting", "WHERE realm = '%s'", false, $rid);
	
	return $return;
}
//VOTE FORM PAGE
function FetchVoteLogs()
{
	global $DB, $USER;
	
	//Logged in or not
	if($USER['loggedin'])
	{
		$votes = $DB->Select("*", "log_votes", "WHERE accountid = '%s' OR ip = '%s'", false, $USER['id'], $_SERVER['REMOTE_ADDR']);
	}
	else
	{
		$votes = $DB->Select("*", "log_votes", "WHERE ip = '%s'", false, $_SERVER['REMOTE_ADDR']);
	}
	
	//Gateway ID to array key
	$return = array();
	foreach($votes as $vote)
	{
		$return[$vote['gateway']] = $vote;
	}
	
	return $return;
}
function TallyVote()
{
	global $DB, $USER;
	
	//Check if gateway exists
	$gateway = $DB->Select("*", "vote_gateways", "WHERE id='%s'", true, $_POST['gateway']);
	if($DB->AffectedRows == 0)
	{
		return false;
	}
	
	//If already voted in last 12 hour
	if($USER['loggedin'])
	{
		$prevvote = $DB->Select("gateway", "log_votes", "WHERE (accountid = '%s' OR ip = '%s') AND gateway = '%s'", false, $USER['id'], $_SERVER['REMOTE_ADDR'], $_POST['gateway']);
	}
	else
	{
		$prevvote = $DB->Select("gateway", "log_votes", "WHERE ip = '%s' AND gateway = '%s'", false, $_SERVER['REMOTE_ADDR'], $_POST['gateway']);
	}
	if($DB->AffectedRows == 0)
	{
		//Add vote to logs
		$accountid = $USER['loggedin'] ? $USER['id'] : '0';
		$DB->Insert(array("gateway"=>"'%s'", "ip"=>"'%s'", "accountid"=>"'%s'", "time"=>"'%s'"), "log_votes", false, $_POST['gateway'], $_SERVER['REMOTE_ADDR'], $accountid, time());
		
		//Modify Vote Points
		if($USER['loggedin'])
		{
			ModifyVotePoints($USER['id'], RPPV);
		}
	}
	
	return $gateway['url'];
}

//################ Template's Output ################
$action = $_GET['act'];
switch($action)
{
	case "spend":
		if(empty($_GET['rid']) || empty($REALM[$_GET['rid']]))
		{
			$page_name[] = array("Select Realm");
			$tplname = "realm_selection";
		}
		else
		{
			$page_name[] = array("Buy Vote Rewards"=>$_SERVER['REQUEST_URI']);
			$page_name[] = array($REALM[$_GET['rid']]['NAME']=>$_SERVER['REQUEST_URI']);
			$rclass = new Realm($_GET['rid']);
			
			//If Submitted ... Check for erros
			if(isset($_POST['submit']))
			{
				if(empty($_POST['character_selected']))
				{
					$cms->ErrorPopulate("You did not select a character.");
				}
				if(empty($_POST['reward_selected']))
				{
					$cms->ErrorPopulate("You did not select a reward.");
				}
			}
			
			//Prepare page variables
			InitWorldDb($WORLDDB, $_GET['rid']);
			$CHARACTERLIST_RID = $_GET['rid'];
			$CHARACTERLIST_SHOW_TOOLS = false;
			$CHARACTERLIST_MUSTBEONLINE = false;
			$CHARACTERLIST_MUSTBEOFFLINE = false;
			$CHARACTERLIST_SELECTION = true;
			$characters = $rclass->FetchCharactersByAccountID("", $USER['id']);
			$rewards = FetchVoteRewards($_GET['rid']);
			$itemnames = FetchItemsData($rewards, $_GET['rid']);
			
			//If there is an error
			if($cms->ErrorExists())
			{
				$tplname = "vote_spend";
			}
			else //Or else we'll continue on sending the items
			{
				if(isset($_POST['submit']))
				{
					$result = $rclass->SendReward($_POST['reward_selected'], $_POST['character_selected'], REWARD_VOTE);
					if($result['bool'])
					{
						$successmessage = $result['message'];
					}
					else
					{
						$cms->ErrorPopulate($result['message']);
					}
				}
				$tplname = "vote_spend";
			}
		}
	break;
	
	default:
		if(isset($_POST['submit']))
		{
			$url = TallyVote();
			$REDIRECT_TYPE = "success"; //Can be "success", "error", and "notification"
			if($url == false)
			{
				$message = "You've voted on an invalid gateway, please try again or vote on another gateway. If this problem persists, please contact an administrator.";
				$url = "vote.php";
				$REDIRECT_TYPE = "error"; //Can be "success", "error", and "notification"
			}
			else
			{
				$message = "You're being redirected to the vote page. You will receive your vote points after the vote have been successfuly counted and only if you are logged in.";
			}
			$REDIRECT_MESSAGE = $message;
			$REDIRECT_LOCATION = $url;
			$REDIRECT_INTERVAL = 3000; //Interval in milliseconds(Default 2000 ie 2seconds)
			eval($templates->Redirect()); //This is called after the 4 variables have been set
		}
		else
		{
			$previousvotes = FetchVoteLogs();
			$gateways = FetchVoteGateways();
			$tplname = "vote_info";
		}
	break;
}

if($tplname)
{
	eval($templates->Output($tplname));
}
?>
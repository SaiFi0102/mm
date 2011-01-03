<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

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
	global $DB;
	if($amount < 1)
	{
		$times = 0;
	}
	else
	{
		$times = 1;
	}
	
	$query = new MMQueryBuilder();
	$query->Update("`account_mm_extend`")->Where("`accountid` = '%s'", $accountid)
	->Columns(array("`votepoints`" => "`votepoints` + '%s'", "`voted`" => "`voted` + '{$times}'"), $amount)->Build();
	$DB->query($query, DBNAME);
	
	return $DB->affected_rows;
}
function FetchVoteRewards($rid)
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`rewards_voting`")->Columns("*")->Where("`realm` = '%s'", $rid)->Build();
	
	$return = MMMySQLiFetch($DB->query($query, DBNAME));
	
	return $return;
}
//VOTE FORM PAGE
function FetchVoteLogs()
{
	global $DB, $USER;
	
	//Logged in or not
	$query = new MMQueryBuilder();
	$query->Select("`log_votes`")->Columns("*");
	if($USER['loggedin'])
	{
		$query->Where("`ip` = '%s' OR `accountid` = '%s'", GetIp(), $USER['id']);
	}
	else
	{
		$query->Where("`ip` = '%s'", GetIp());
	}
	$query->Build();
	$votes = MMMySQLiFetch($DB->query($query, DBNAME));
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
	$query = new MMQueryBuilder();
	$query->Select("`vote_gateways`")->Columns("*")->Where("`id` = '%s'", $_POST['gateway'])->Build();
	$result = $DB->query($query, DBNAME);
	if($result->num_rows == 0)
	{
		return false;
	}
	$gateway = MMMySQLiFetch($result, "onerow: 1");
	
	//If already voted in last 12 hour
	$query = new MMQueryBuilder();
	$query->Select("`log_votes`")->Columns(array("COUNT(*)"=>"numrows"));
	if($USER['loggedin'])
	{
		$query->Where("(`accountid` = '%s' OR `ip` = '%s') AND `gateway` = '%s'", $USER['id'], GetIp(), $_POST['gateway']);
	}
	else
	{
		$query->Where("`ip` = '%s' AND `gateway` = '%s'", GetIp(), $_POST['gateway']);
	}
	$query->Build();
	$result = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");
	
	if((int)$result['numrows'] == 0)
	{
		//Add vote to logs
		$accountid = $USER['loggedin'] ? $USER['id'] : '0';
		$query = new MMQueryBuilder();
		$query->Insert("`log_votes`")->Columns(array("`gateway`"=>"'%s'", "`ip`"=>"'%s'", "`accountid`"=>"'%s'", "`time`"=>"'%s'"), $_POST['gateway'], GetIp(), $accountid, time())->Build();
		$DB->query($query, DBNAME);
		
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
			$page_name[] = array("Buy Vote Rewards"=>"vote.php?act=spend");
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
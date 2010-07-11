<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

//################ Required Files ################
require_once("init.php");
require_once(DOC_ROOT."/includes/PayPal.gateway.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
if($_GET['act'] == 'validate')
{
	eval($cms->SetPageAccess(ACCESS_ALL));
}
else
{
	eval($cms->SetPageAccess(ACCESS_REGISTERED));
}

//################ Resources ################ 
$PayPal = new PayPal();
WoW::getZonesArray();

//################ General Variables ################
$page_name[] = array("Donate"=>"donate.php");

//################ Constants ################

//################ Page Functions ################
function ModifyDonationPoints($accountid, $amount)
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
	$LOGONDB->Update(array("donationpoints"=>"donationpoints + '%s'", "donated"=>"donated + '{$times}'"), "account_mm_extend", "WHERE accountid = '%s'", $amount, $accountid);
	return $LOGONDB->AffectedRows;
}
function FetchTransactions($uid)
{
	global $DB;
	$return = $DB->Select("*", "log_payments_paypal", "WHERE account_id = '%s' ORDER BY transaction_id DESC, timestamp ASC", false, $uid);
	
	return $return;
}
function FetchDonationRewards($rid)
{
	global $DB;
	$return = $DB->Select("*", "rewards_donation", "WHERE realm = '%s'", false, $rid);
	
	return $return;
}

//################ Template's Output ################
$action = $_GET['act'];
switch($action)
{
	//PayPal's IPN Listener
	case "validate":
		$tplname = false;
		$validation = $PayPal->ValidateTransaction();
		if($validation != false && is_array($validation))
		{
			ModifyDonationPoints($validation['accountid'], $validation['amount']);
		}
	break;
	
	case "spend":
		if(empty($_GET['rid']) || empty($REALM[$_GET['rid']]))
		{
			$page_name[] = array("Select Realm");
			$tplname = "realm_selection";
		}
		else
		{
			$page_name[] = array("Buy Donation Rewards"=>"donate.php?act=spend");
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
			$rewards = FetchDonationRewards($_GET['rid']);
			$itemnames = FetchItemsData($rewards, $_GET['rid']);
			
			//If there is an error
			if($cms->ErrorExists())
			{
				$tplname = "donation_spend";
			}
			else //Or else we'll continue on sending the items
			{
				if(isset($_POST['submit']))
				{
					$result = $rclass->SendReward($_POST['reward_selected'], $_POST['character_selected'], REWARD_DONATE);
					if($result['bool'])
					{
						$successmessage = $result['message'];
					}
					else
					{
						$cms->ErrorPopulate($result['message']);
					}
				}
				$tplname = "donation_spend";
			}
		}
	break;
	
	default:
		$transactions = FetchTransactions($USER['id']);
		$tplname = "donation_info";
	break;
}

if($tplname)
{
	eval($templates->Output($tplname));
}
?>
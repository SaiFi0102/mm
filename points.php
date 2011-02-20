<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
if($_GET['act'] == 'validate' || $_GET['act'] == 'faq' || $_GET['act'] == 'refundpolicy')
{
	eval($cms->SetPageAccess(ACCESS_ALL));
}
else
{
	eval($cms->SetPageAccess(ACCESS_REGISTERED));
}

//################ General Variables ################
$page_name[] = array($cms->config['websitename']." Points"=>"points.php");
WoW::getZonesArray();

//################ Page Functions ################
function FetchTransactionsPayPal($uid)
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`log_payments_paypal`")->Columns("*")->Where("`account_id` = '%s'", $uid)->Order("`transaction_id` DESC, `timestamp` ASC")->Build();
	$return = MMMySQLiFetch($DB->query($query, DBNAME));
	
	return $return;
}
function FetchTransactionsMoneyBookers($uid)
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`log_payments_moneybookers`")->Columns("*")->Where("`account_id` = '%s'", $uid)->Order("`transaction_id` DESC, `timestamp` ASC")->Build();
	$return = MMMySQLiFetch($DB->query($query, DBNAME));
		
	return $return;
}
function FetchTransactionsAlertPay($uid)
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`log_payments_alertpay`")->Columns("*")->Where("`account_id` = '%s'", $uid)->Order("`transaction_id` DESC, `timestamp` ASC")->Build();
	$return = MMMySQLiFetch($DB->query($query, DBNAME));
		
	return $return;
}
function FetchDonationRewards($rid)
{
	global $DB;
	
	$query = new MMQueryBuilder();
	$query->Select("`rewards_donation`")->Columns("*")->Where("`realm` = '%s'", $rid)->Build();
	$return = MMMySQLiFetch($DB->query($query, DBNAME));
	
	return $return;
}

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
			$page_name[] = array("Buy ".$cms->config['websitename']." Points Rewards"=>"points.php?act=spend");
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
			$rewards = FetchDonationRewards($_GET['rid']);
			$itemnames = FetchItemsData($rewards, $_GET['rid']);
			
			//If there is an error
			if($cms->ErrorExists())
			{
				$tplname = "points_spend";
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
				$tplname = "points_spend";
			}
		}
	break;
	
	case "faq":
		$page_name[] = array("FAQ"=>$_SERVER['REQUEST_URI']);
		$tplname = "points_faq";
	break;
	
	case "refundpolicy":
		$page_name[] = array("Refund Policy"=>$_SERVER['REQUEST_URI']);
		$tplname = "points_refundpolicy";
	break;
	
	default:
		$transactions_paypal = FetchTransactionsPayPal($USER['id']);
		$transactions_moneybookers = FetchTransactionsMoneyBookers($USER['id']);
		$transactions_alertpay = FetchTransactionsAlertPay($USER['id']);
		$tplname = "points_info";
	break;
}

if($tplname)
{
	eval($templates->Output($tplname));
}
?>
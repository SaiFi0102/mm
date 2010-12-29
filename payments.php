<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Resources ################ 

//################ General Variables ################
$page_name[] = array("Payments");

//################ Page Functions ################
function ModifyDonationPoints($accountid, $amount)
{
	global $DB, $ppd;
	
	//If amount is less than 1 it means that the transaction is a reversal or a refund
	if($amount < 1)
	{
		$times = 0;
	}
	else
	{
		$times = 1;
	}
	$amount = $amount * $ppd; //Amount of Dollars * Points per dollar specified by gateway
	
	$query = new MMQueryBuilder();
	$query->Update("`account_mm_extend`")->Where("`accountid` = '%s'", $accountid)
	->Columns(array("`donationpoints`"=>"`donationpoints` + '%s'", "`donated`"=>"`donated` + '%s'"), $amount, $times)->Build();
	
	$DB->query($query, DBNAME);
	return $DB->affected_rows;
}

if(empty($_POST) || count($_POST) == 0)
{
	header('Location: points.php');
	exit();
}

$ppd = 1;
switch($_GET['gateway'])
{
	case "PayPal":
		require_once(DOC_ROOT."/includes/PayPal.gateway.php");
		$gateway = new PayPal();
		$ppd = $cms->config['pointsperdollar_paypal'];
	break;
	case "AlertPay":
		require_once(DOC_ROOT."/includes/AlertPay.gateway.php");
		$gateway = new AlertPay();
		$ppd = $cms->config['pointsperdollar_alertpay'];
	break;
	case "MoneyBookers":
		require_once(DOC_ROOT."/includes/MoneyBookers.gateway.php");
		$gateway = new MoneyBookers();
		$ppd = $cms->config['pointsperdollar_moneybookers'];
	break;
	default:
		//trigger_error("noob_gateway = ".$_GET['gateway'], E_USER_WARNING);
	break;
}
$validation = $gateway->ValidateTransaction();
if($validation == "noob")
{
	trigger_error("noob", E_USER_WARNING);
}
if($validation != false && is_array($validation))
{
	ModifyDonationPoints($validation['accountid'], $validation['amount']);
}

?>
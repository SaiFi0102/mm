<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template

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

if(empty($_POST) || count($_POST) == 0)
{
	header('Location: points.php');
	exit();
}

switch($_GET['gateway'])
{
	case "PayPal":
		require_once(DOC_ROOT."/includes/PayPal.gateway.php");
		$gateway = new PayPal();
	break;
	case "AlertPay":
		require_once(DOC_ROOT."/includes/AlertPay.gateway.php");
		$gateway = new AlertPay();
	break;
	case "MoneyBookers":
		require_once(DOC_ROOT."/includes/MoneyBookers.gateway.php");
		$gateway = new MoneyBookers();
	break;
	default:
		exit("noob");
	break;
}

$validation = $gateway->ValidateTransaction();
if($validation != false && is_array($validation))
{
	ModifyDonationPoints($validation['accountid'], $validation['amount']);
}

?>
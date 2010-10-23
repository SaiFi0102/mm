<?php
//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

//Setting information about the transaction
$receivedSecurityCode = urldecode($_POST['ap_securitycode']);
$receivedMerchantEmailAddress = urldecode($_POST['ap_merchant']);	
$transactionStatus = urldecode($_POST['ap_status']);
$testModeStatus = urldecode($_POST['ap_test']);	 
$purchaseType = urldecode($_POST['ap_purchasetype']);
$totalAmountReceived = urldecode($_POST['ap_totalamount']);
$feeAmount = urldecode($_POST['ap_feeamount']);
$netAmount = urldecode($_POST['ap_netamount']);
$transactionReferenceNumber = urldecode($_POST['ap_referencenumber']);
$currency = urldecode($_POST['ap_currency']); 	
$transactionDate= urldecode($_POST['ap_transactiondate']);
$transactionType= urldecode($_POST['ap_transactiontype']);
	
//Setting the customer's information from the IPN post variables
$customerFirstName = urldecode($_POST['ap_custfirstname']);
$customerLastName = urldecode($_POST['ap_custlastname']);
$customerAddress = urldecode($_POST['ap_custaddress']);
$customerCity = urldecode($_POST['ap_custcity']);
$customerState = urldecode($_POST['ap_custstate']);
$customerCountry = urldecode($_POST['ap_custcountry']);
$customerZipCode = urldecode($_POST['ap_custzip']);
$customerEmailAddress = urldecode($_POST['ap_custemailaddress']);
	
//Setting information about the purchased item from the IPN post variables
$myItemName = urldecode($_POST['ap_itemname']);
$myItemCode = urldecode($_POST['ap_itemcode']);
$myItemDescription = urldecode($_POST['ap_description']);
$myItemQuantity = urldecode($_POST['ap_quantity']);
$myItemAmount = urldecode($_POST['ap_amount']);

//Setting extra information about the purchased item from the IPN post variables
$additionalCharges = urldecode($_POST['ap_additionalcharges']);
$shippingCharges = urldecode($_POST['ap_shippingcharges']);
$taxAmount = urldecode($_POST['ap_taxamount']);
$discountAmount = urldecode($_POST['ap_discountamount']);
 
//Setting your customs fields received from the IPN post variables
$myCustomField_1 = urldecode($_POST['apc_1']);
$myCustomField_2 = urldecode($_POST['apc_2']);
$myCustomField_3 = urldecode($_POST['apc_3']);
$myCustomField_4 = urldecode($_POST['apc_4']);
$myCustomField_5 = urldecode($_POST['apc_5']);
$myCustomField_6 = urldecode($_POST['apc_6']);

class AlertPay
{
	private $sql;
	
	/**
	 * Constructor
	 * @param $sql
	 */
	function __construct()
	{
		global $DB;
		$this->sql = $DB;
	}
	
	/**
	 * Validates Transaction and handles it according to its payment status
	 */
	public function ValidateTransaction()
	{
		global $myCustomField_2, $testModeStatus, $totalAmountReceived, $transactionStatus, $currency, $receivedMerchantEmailAddress, $purchaseType, $transactionType, $receivedSecurityCode;
		//Check for problems in POSTed items
		if(empty($_POST))
		{
			return false; //no data posted, skip.
		}
		if($totalAmountReceived == null || $transactionStatus == null || $currency == null || $receivedMerchantEmailAddress == null)
		{
			return false;
		}
		if($purchaseType != "item" || $transactionType != "purchase")
		{
			return false;
		}
		if($testModeStatus == "1")
		{
			return false;
		}
		if($myCustomField_2 != "_MM_PAYMENT")
		{
			return false;
		}

		//Check if payment is valid
		if($receivedSecurityCode == IPN_ALERTPAY_SECURITY_CODE)
		{
			return $this->HandleVerified();
		}
		else
		{
			$this->HandleInvalid();
			return false;
		}
	}
	
	/**
	 * Handles Invalid Payment Declared by AlertPay
	 */
	private function HandleInvalid()
	{
		global $customerFirstName, $customerLastName, $myCustomField_1, $myItemName, $customerEmailAddress, $transactionReferenceNumber, $totalAmountReceived, $transactionStatus, $currency, $receivedMerchantEmailAddress, $purchaseType, $transactionType, $receivedSecurityCode;
		
		//Insert information in invalid payments table
		$postdata = $this->GetPostData();
		
		$this->sql->Insert(
		array(
			"status"			=> "'1'",
			"transaction_id"	=> "'%s'",
			"sender_email"		=> "'%s'",
			"payment_status"	=> "'%s'",
			"item_name"			=> "'%s'",
			"amount"			=> "'%s'",
			"currency"			=> "'%s'",
			"account_id"		=> "'%s'",
			"first_name"		=> "'%s'",
			"last_name"			=> "'%s'",
			"post_data"			=> "'%s'",
			"extra_information"	=> "'INVALID TRANSACTION FROM ALERTPAY'",
		), "log_invalidpayments_alertpay", false,
		$transactionReferenceNumber,
		$customerEmailAddress,
		$transactionStatus,
		$myItemName,
		$totalAmountReceived,
		$currency,
		$myCustomField_1,
		$customerFirstName,
		$customerLastName,
		$postdata);
	}
	
	/**
	 * Handles Verified Payment declared by AlertPay
	 */
	private function HandleVerified()
	{
		global $cms;
		global $netAmount, $feeAmount, $customerFirstName, $customerLastName, $myCustomField_1, $myItemName, $customerEmailAddress, $transactionReferenceNumber, $totalAmountReceived, $transactionStatus, $currency, $receivedMerchantEmailAddress, $purchaseType, $transactionType, $receivedSecurityCode;
		
		$postdata = $this->GetPostData();
		
		//Check if some one is trying to HACK or SCAM us
		//Wrong receiver_email
		if($receivedMerchantEmailAddress != $cms->config['email_alertpay'])
		{
			$this->sql->Insert(
			array(
				"status"			=> "'1'",
				"transaction_id"	=> "'%s'",
				"sender_email"		=> "'%s'",
				"payment_status"	=> "'%s'",
				"item_name"			=> "'%s'",
				"amount_gross"		=> "'%s'",
				"amount_fee"		=> "'%s'",
				"amount_net"		=> "'%s'",
				"currency"			=> "'%s'",
				"account_id"		=> "'%s'",
				"first_name"		=> "'%s'",
				"last_name"			=> "'%s'",
				"post_data"			=> "'%s'",
				"extra_information"	=> "'WRONG RECEIVER_EMAIL(%s != %s)'",
				"details"			=> "'The payment was sent to someone else, please contact an administrator if you think this is an error'",
			), "log_payments_alertpay", false,
			$transactionReferenceNumber,
			$customerEmailAddress,
			$transactionStatus,
			$myItemName,
			$totalAmountReceived,
			$feeAmount,
			$netAmount,
			$currency,
			$myCustomField_1,
			$customerFirstName,
			$customerLastName,
			$postdata,
			$receivedMerchantEmailAddress,
			$cms->config['email_alertpay']);
			return false;
		}
		//Wrong currency
		if($currency != "USD")
		{
			$this->sql->Insert(
			array(
				"status"			=> "'1'",
				"transaction_id"	=> "'%s'",
				"sender_email"		=> "'%s'",
				"payment_status"	=> "'Failed'",
				"item_name"			=> "'%s'",
				"amount_gross"		=> "'%s'",
				"amount_fee"		=> "'%s'",
				"amount_net"		=> "'%s'",
				"currency"			=> "'%s'",
				"account_id"		=> "'%s'",
				"first_name"		=> "'%s'",
				"last_name"			=> "'%s'",
				"post_data"			=> "'%s'",
				"extra_information"	=> "'WRONG CURRENCY: %s'",
				"details"			=> "'The payment currency was not in $(USD), please contact an administrator to convert the currency to $(USD)'",
			), "log_payments_alertpay", false,
			$transactionReferenceNumber,
			$customerEmailAddress,
			$myItemName,
			$totalAmountReceived,
			$feeAmount,
			$netAmount,
			$currency,
			$myCustomField_1,
			$customerFirstName,
			$customerLastName,
			$postdata,
			$currency);
			return false;
		}
		
		//Handle payment according payment_status
		switch($transactionStatus)
		{
			//Completed Transactions
			case "Success":
				return $this->HandleStatusSuccess(false);
			break;
			
			//Failed Transactions
			default:
				return $this->HandleStatusFailed();
			break;
		}
	}
	
	/**
	 * 
	 * Handlers for payment_status
	 * 
	 */
	
	/**
	 * Handles succeded payments
	 */
	private function HandleStatusSuccess()
	{
		global $netAmount, $feeAmount, $customerFirstName, $customerLastName, $myCustomField_1, $myItemName, $customerEmailAddress, $transactionReferenceNumber, $totalAmountReceived, $transactionStatus, $currency, $receivedMerchantEmailAddress, $purchaseType, $transactionType, $receivedSecurityCode;
		
		$postdata		= $this->GetPostData();
		$accountid 		= $myCustomField_1;
		$amount			= floatval($totalAmountReceived);
		$currency		= $currency;
		
		//Check if transaction has been processed before and if it is "Completed"
		$checkrecycle = $this->sql->Query("SELECT status FROM log_payments_alertpay WHERE transaction_id = '%s' AND status = '0'", $transactionReferenceNumber);
		$numrecycle = $this->sql->numRows($checkrecycle);
		if($numrecycle > 0)
		{
			//Insert log in INVALID payments
			$this->sql->Insert(
			array(
				"status"				=> "'0'",
				"transaction_id"		=> "'%s'",
				"sender_email"			=> "'%s'",
				"payment_status"		=> "'%s'",
				"item_name"				=> "'%s'",
				"amount"				=> "'%s'",
				"currency"				=> "'%s'",
				"account_id"			=> "'%s'",
				"first_name"			=> "'%s'",
				"last_name"				=> "'%s'",
				"post_data"				=> "'%s'",
				"extra_information"		=> "'TRANSACTION RECYLCED WITH PAYMENTSTATUS COMPLETED'",
			), "log_invalidpayments_alertpay", false,
			$transactionReferenceNumber,
			$customerEmailAddress,
			$transactionStatus,
			$myItemName,
			$amount,
			$currency,
			$accountid,
			$customerFirstName,
			$customerLastName,
			$postdata);
			
			return false;
		}
		
		//Delete pending payments with same transaction id
		$this->sql->Delete("log_payments_alertpay", "WHERE transaction_id = '%s' AND status = '2'", $transactionReferenceNumber);
		
		//Everything is OK, Insert LOG for successful payment
		$details = "Transaction was successful";
		$this->sql->Insert(
		array(
			"status"				=> "'0'",
			"transaction_id"		=> "'%s'",
			"sender_email"			=> "'%s'",
			"payment_status"		=> "'%s'",
			"item_name"				=> "'%s'",
			"amount_gross"			=> "'%s'",
			"amount_fee"			=> "'%s'",
			"amount_net"			=> "'%s'",
			"currency"				=> "'%s'",
			"account_id"			=> "'%s'",
			"first_name"			=> "'%s'",
			"last_name"				=> "'%s'",
			"post_data"				=> "'%s'",
			"extra_information"		=> "'SUCCESSFUL PAYMENT!'",
			"details"				=> "'$details, Points were added to your account!'",
		), "log_payments_alertpay", false,
		$transactionReferenceNumber,
		$customerEmailAddress,
		$transactionStatus,
		$myItemName,
		$amount,
		$feeAmount,
		$netAmount,
		$currency,
		$accountid,
		$customerFirstName,
		$customerLastName,
		$postdata);
		
		return array("amount" => $amount, "accountid" => $accountid);
	}
	
	private function HandleStatusFailed()
	{
		global $netAmount, $feeAmount, $customerFirstName, $customerLastName, $myCustomField_1, $myItemName, $customerEmailAddress, $transactionReferenceNumber, $totalAmountReceived, $transactionStatus, $currency, $receivedMerchantEmailAddress, $purchaseType, $transactionType, $receivedSecurityCode;
		
		$postdata	= $this->GetPostData();
		$amount		= floatval($totalAmountReceived);
		$accountid	= $myCustomField_1;
		$currency	= $currency;
		
		//Insert log for failed transaction
		$this->sql->Insert(
		array(
			"status"			=> "'1'",
			"transaction_id"	=> "'%s'",
			"sender_email"		=> "'%s'",
			"payment_status"	=> "'%s'",
			"item_name"			=> "'%s'",
			"amount_gross"		=> "'%s'",
			"amount_fee"		=> "'%s'",
			"amount_net"		=> "'%s'",
			"currency"			=> "'%s'",
			"account_id"		=> "'%s'",
			"first_name"		=> "'%s'",
			"last_name"			=> "'%s'",
			"post_data"			=> "'%s'",
			"extra_information"	=> "'FAILED PAYMENT!'",
			"details"			=> "'Transaction Failed'",
		), "log_payments_alertpay", false,
		$transactionReferenceNumber,
		$customerEmailAddress,
		$transactionStatus,
		$myItemName,
		$amount,
		$feeAmount,
		$netAmount,
		$currency,
		$accountid,
		$customerFirstName,
		$customerLastName,
		$postdata);
		
		return false;
	}
	
	/**
	 * returns $_POST array to a string
	 */
	private function GetPostData()
	{
		$postdata = null;
		foreach($_POST as $key => $value)
		{
			$postdata .= "[$key] = $value\r\n";
		}
		return $postdata;
	}
	
}
?>
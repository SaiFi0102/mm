<?php
//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

class MoneyBookers
{
	private $sql;
	private $status;
	
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
		//Check for problems in POSTed items
		if(empty($_POST))
		{
			return false; //no data posted, skip.
		}
		if(!isset($_POST['amount']) || !isset($_POST['currency']) || !isset($_POST['pay_to_email']))
		{
			return false;
		}

		//Check if payment is valid
		$url = "https://www.moneybookers.com/app/query.pl?email=".$_POST['pay_to_email']."&password=".md5(IPN_MONEYBOOKERS_PASSWORD)."&action=status_trn&trn_id=".$_POST['mb_transaction_id'];
		$handle = fopen($url, 'r');
		
		if($handle == false)
		{
			$this->HandleInvalid();
			return false;
		}
		
		$status = fread($handle, 1024);
		$status = strtolower($status);
		if(($status_pos = strpos($status, "status=")) !== false)
		{
			$status = substr($status, $status_pos);
			$status_vars = explode("&", $status);
			$status = explode("=", $status_vars[0]);
			$status = urldecode($status[1]);
			
			switch($status)
			{
				case "2":
					$this->status = "Processed";
				break;
				case "1":
					$this->status = "Scheduled";
				break;
				case "0":
					$this->status = "Pending";
				break;
				case "-1":
					$this->status = "Cancelled";
				break;
				case "-2":
					$this->status = "Failed";
				break;
				case "-3":
					$this->status = "Chargeback";
				break;
				default:
					$this->status = "Failed";
				break;
			}
			
			//Verified payment
			fclose($sock);
			return $this->HandleVerified($status);
		}
		else
		{
			//Invalid payment or no access
			fclose($sock);
			$this->HandleInvalid();
			return false;
    	}
	}
	
	/**
	 * Handles Invalid Payment Declared by MoneyBookers
	 */
	private function HandleInvalid()
	{
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
			"extra_information"	=> "'INVALID TRANSACTION FROM MONEYBOOKERS'",
		), "log_invalidpayments_moneybookers", false,
		$_POST['mb_transaction_id'],
		$_POST['pay_from_email'],
		"Invalid",
		$_POST['detail1_description']. " " .$_POST['detail1_text'],
		$_POST['amount'],
		$_POST['currency'],
		$_POST['accountid'],
		$_POST['firstname'],
		$_POST['lastname'],
		$postdata);
	}
	
	/**
	 * Handles Verified Payment declared by MoneyBookers
	 */
	private function HandleVerified($status)
	{
		global $cms;
		$postdata = $this->GetPostData();
		
		//Check if some one is trying to HACK or SCAM us
		//Wrong receiver_email
		if($_POST['pay_to_email'] != $cms->config['email_moneybookers'])
		{
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
				"extra_information"	=> "'WRONG RECEIVER_EMAIL(%s != %s)'",
				"details"			=> "'The payment was sent to someone else, please contact an administrator if you think this is an error'",
			), "log_payments_moneybookers", false,
			$_POST['mb_transaction_id'],
			$_POST['pay_from_email'],
			$this->status,
			$_POST['detail1_description']. " " .$_POST['detail1_text'],
			$_POST['amount'],
			$_POST['currency'],
			$_POST['accountid'],
			$_POST['firstname'],
			$_POST['lastname'],
			$postdata,
			$_POST['pay_to_email'],
			$cms->config['email_moneybookers']);
			return false;
		}
		//Wrong currency
		if($_POST['currency'] != "USD")
		{
			$this->sql->Insert(
			array(
				"status"			=> "'1'",
				"transaction_id"	=> "'%s'",
				"sender_email"		=> "'%s'",
				"payment_status"	=> "'Failed'",
				"item_name"			=> "'%s'",
				"amount"			=> "'%s'",
				"currency"			=> "'%s'",
				"account_id"		=> "'%s'",
				"first_name"		=> "'%s'",
				"last_name"			=> "'%s'",
				"post_data"			=> "'%s'",
				"extra_information"	=> "'WRONG CURRENCY: %s'",
				"details"			=> "'The payment currency was not in $(USD), please contact an administrator to convert the currency to $(USD)'",
			), "log_payments_moneybookers", false,
			$_POST['mb_transaction_id'],
			$_POST['pay_from_email'],
			$_POST['detail1_description']. " " .$_POST['detail1_text'],
			$_POST['amount'],
			$_POST['currency'],
			$_POST['accountid'],
			$_POST['firstname'],
			$_POST['lastname'],
			$postdata,
			$_POST['currency']);
			return false;
		}
		
		//Handle payment according payment_status
		switch($status)
		{
			//Completed Transactions
			case "2":
				return $this->HandleStatusCompleted(false);
			break;
			
			//Reversals and Refunds
			case "-3":
				return $this->HandleStatusReversed();
			break;
			
			//Pending and Unfinished Transactions
			case "1":
				return $this->HandleStatusPending();
			break;
			case "0":
				return $this->HandleStatusPending();
			break;
			
			//Failed Transactions
			case "-1":
				return $this->HandleStatusFailed();
			break;
			case "-2":
				return $this->HandleStatusFailed();
			break;
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
	 * Handles completed payments
	 */
	private function HandleStatusCompleted($cancelled_reversal = false)
	{
		$postdata		= $this->GetPostData();
		$accountid 		= $_POST['accountid'];
		$amount			= floatval($_POST['amount']);
		$currency		= $_POST['currency'];
		$txn_id 		= $_POST['mb_transaction_id'];
		
		//Check if transaction has been processed before and if it is "Completed"
		$checkrecycle = $this->sql->Query("SELECT status FROM log_payments_moneybookers WHERE transaction_id = '%s' AND status = '0'", $_POST['mb_transaction_id']);
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
			), "log_invalidpayments_moneybookers", false,
			$txn_id,
			$_POST['pay_from_email'],
			$this->status,
			$_POST['detail1_description']. " " .$_POST['detail1_text'],
			$amount,
			$currency,
			$accountid,
			$_POST['firstname'],
			$_POST['lastname'],
			$postdata);
			
			return false;
		}
		
		//Delete pending payments with same transaction id
		$this->sql->Delete("log_payments_moneybookers", "WHERE transaction_id = '%s' AND status = '2'", $_POST['mb_transaction_id']);
		
		//Everything is OK, Insert LOG for successful payment
		$details = $cancelled_reversal ? "Reversal was cancelled" : "Transaction was successful";
		$this->sql->Insert(
		array(
			"status"				=> "'0'",
			"transaction_id"		=> "'%s'",
			"sender_email"			=> "'%s'",
			"payment_status"		=> "'%s'",
			"item_name"				=> "'%s'",
			"amount"			=> "'%s'",
			"currency"				=> "'%s'",
			"account_id"			=> "'%s'",
			"first_name"			=> "'%s'",
			"last_name"				=> "'%s'",
			"post_data"				=> "'%s'",
			"extra_information"		=> "'SUCCESSFUL PAYMENT!'",
			"details"				=> "'$details, Points were added to your account!'",
		), "log_payments_moneybookers", false,
		$txn_id,
		$_POST['pay_from_email'],
		$this->status,
		$_POST['detail1_description']. " " .$_POST['detail1_text'],
		$amount,
		$currency,
		$accountid,
		$_POST['firstname'],
		$_POST['lastname'],
		$postdata);
		
		return array("amount" => $amount, "accountid" => $accountid);
	}
	
	/**
	 * Handles refunded or reversed payments
	 */
	private function HandleStatusReversed()
	{
		$postdata	= $this->GetPostData();
		$amount		= floatval($_POST['amount']);
		$amount		= abs($amount);
		$accountid	= $_POST['accountid'];
		$currency	= $_POST['currency'];
		
		//Insert log for negative transaction with the amount in negative
		$this->sql->Insert(
		array(
			"status"				=> "'3'",
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
			"extra_information"		=> "'REVERSED PAYMENT!'",
			"details"				=> "'Transaction was reversed or refunded, Points was deducted from your account!'",
		), "log_payments_moneybookers", false,
		$_POST['mb_transaction_id'],
		$_POST['pay_from_email'],
		$this->status,
		$_POST['detail1_description']. " " .$_POST['detail1_text'],
		$amount,
		$currency,
		$accountid,
		$_POST['firstname'],
		$_POST['lastname'],
		$postdata);

		return array("amount" => -$amount, "accountid" => $accountid);
	}
	
	/**
	 * Handles transactions that are not completed yet but will be completed later
	 */
	private function HandleStatusPending()
	{
		$postdata	= $this->GetPostData();
		$amount		= floatval($_POST['amount']);
		$accountid	= $_POST['accountid'];
		$currency	= $_POST['currency'];
		
		//Insert log for pending transaction
		$this->sql->Insert(
		array(
			"status"			=> "'2'",
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
			"extra_information"	=> "'UNFINISHED PAYMENT!'",
			"details"			=> "'Transaction is currently pending, Points will be added once the transaction is completed'",
		), "log_payments_moneybookers", false,
		$_POST['mb_transaction_id'],
		$_POST['pay_from_email'],
		$this->status,
		$_POST['detail1_description']. " " .$_POST['detail1_text'],
		$amount,
		$currency,
		$accountid,
		$_POST['firstname'],
		$_POST['lastname'],
		$postdata);
		
		return false;
	}
	
	private function HandleStatusFailed()
	{
		$postdata	= $this->GetPostData();
		$amount		= floatval($_POST['amount']);
		$accountid	= $_POST['accountid'];
		$currency	= $_POST['currency'];
		
		//Insert log for failed transaction
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
			"extra_information"	=> "'FAILED PAYMENT!'",
			"details"			=> "'Transaction Failed'",
		), "log_payments_moneybookers", false,
		$_POST['mb_transaction_id'],
		$_POST['pay_from_email'],
		$this->status,
		$_POST['detail1_description']. " " .$_POST['detail1_text'],
		$amount,
		$currency,
		$accountid,
		$_POST['firstname'],
		$_POST['lastname'],
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



















<?php
if (!isset($_POST["session_id"]) && !isset($_POST["transaction_id"])) die();

$url = "https://www.moneybookers.com/app/query.pl?email=".$cms->config['email_moneybookers']."&password=".md5($moneybookers_password)."&action=status_trn&trn_id=".$_POST['transaction_id'];
$handle = fopen($url, 'r');

if ($handle)
{
	$status = fread($handle, 1024);
	$status = strtolower($status);
	if (($status_pos = strpos($status, "status=")) !== false)
	{
		$status = substr($status, $status_pos);
		$status_vars = explode("&", $status);
		$status = explode("=", $status_vars[0]);
		$status = $status[1];
    }
	else
    {
    	$status = "failed";
    }

	if ($status == 2)
    {
		$message = "Payment for ".$_POST['item']." received!\r\n";
		sendMail($_POST['pay_from_email'], "Payment for ".$_POST['item']." received!", "Moneybookers IPN Handler", "noreply@website.com");

		$message = "Moneybookers payment received!\r\n".
			"Payer: ".$_POST['pay_from_email']."\r\n".
			"Amount: ".$_POST["amount"]." ".$_POST["currency"]."\r\n".
			"Details: ".$_POST["item"]."\r\n".
			"Source: http://www.website.com\r\n";
		sendMail("admin@website.com", "Payment received!", $message, "Moneybookers IPN Handler", "noreply@website.com");
	}
    else if($status == 1)
    {
		$message = "Your payment is scheduled! \r\n".
			"In a few minutes the funds should be cleared. \r\n".
			"Once the funds have cleared we will contact you. \r\n\r\n".
			"Thank you!";
		sendMail($_POST['pay_from_email'], "Payment scheduled", $message, "Moneybookers IPN Handler", "noreply@website.com");

		$message = "Moneybookers payment is scheduled!\r\n".
			"Payer: ".$_POST['pay_from_email']."\r\n".
			"Amount: ".$_POST["amount"]." ".$_POST["currency"]."\r\n".
			"Details: ".$_POST["item"]."\r\n".
			"Source: http://www.website.com\r\n";
		sendMail("admin@website.com", "Payment is scheduled!", $message, "Moneybookers IPN Handler", "noreply@website.com");
    }
    else if($status == 0)
    {
		$message = "Your payment is pending! \r\n".
			"In a few minutes the funds should be cleared. \r\n".
			"Once the funds have cleared you will receive download link. \r\n\r\n".
			"Thank you!";
		sendMail($_POST['pay_from_email'], "Payment is pending", $message, "Moneybookers IPN Handler", "noreply@website.com");

		$message = "Moneybookers payment is pending!\r\n".
			"Payer: ".$_POST['pay_from_email']."\r\n".
			"Amount: ".$_POST["amount"]." ".$_POST["currency"]."\r\n".
			"Details: ".$_POST["item"]."\r\n".
			"Source: http://www.website.com\r\n";
		sendMail("admin@website.com", "Payment is pending!", $message, "Moneybookers IPN Handler", "noreply@website.com");
    }
    else if($status == -1)
    {
		$message = "Your payment cancelled! \r\n".
			"For more information please contact us. \r\n".
			"Thank you!";
		sendMail($_POST['pay_from_email'], "Payment cancelled", $message, "Moneybookers IPN Handler", "noreply@website.com");

		$message = "Moneybookers payment is cancelled!\r\n".
			"Payer: ".$_POST['pay_from_email']."\r\n".
			"Amount: ".$_POST["amount"]." ".$_POST["currency"]."\r\n".
			"Details: ".$_POST["item"]."\r\n".
			"Source: http://www.website.com\r\n";
		sendMail("admin@website.com", "Payment cancelled!", $message, "Moneybookers IPN Handler", "noreply@website.com");
    }
    else if($status == -2)
    {
		$message = "Your payment failed! \r\n".
			"Please verify that you have entered the correct credit card information, your credit card can be used for online payements and you have funds in our account. \r\n".
			"Thank you!";
		sendMail($_POST['pay_from_email'], "Payment failed", $message, "Moneybookers IPN Handler", "noreply@website.com");

		$message = "Moneybookers payment is failed!\r\n".
			"Payer: ".$_POST['pay_from_email']."\r\n".
			"Amount: ".$_POST["amount"]." ".$_POST["currency"]."\r\n".
			"Details: ".$_POST["item"]."\r\n".
			"Source: http://www.website.com\r\n";
		sendMail("admin@website.com", "Payment is pending!", $message, "Moneybookers IPN Handler", "noreply@website.com");
    }
	else
	{
		$message = "Unrecognized moneybookers payment received!\r\n".
			"Payer: ".$_POST['pay_from_email']."\r\n".
			"Amount: ".$_POST["amount"]." ".$_POST["currency"]."\r\n".
			"Details: ".$_POST["item"]."\r\n".
			"Source: http://www.website.com\r\n";
		sendMail("admin@website.com", "Unrecognized moneybookers payment received!", "Moneybookers IPN Handler", "noreply@website.com");
	}
}
else
{
	$message = "Access to moneybookers is failed! \r\n".
		"Please contact us as soon as possible. \r\n".
		"Thank you!";
	sendMail($_POST['pay_from_email'], "Access to moneybookers is failed", "Moneybookers IPN Handler", "noreply@website.com");

	$message = "Access to moneybookers is failed!\r\n".
		"Payer: ".$_POST['pay_from_email']."\r\n".
		"Amount: ".$_POST['amount']." ".$_POST["currency"]."\r\n".
		"Details: ".$_POST['item']." / ".$_POST["custom"]."\r\n".
		"Source: http://www.website.com\r\n";
	sendMail("admin@website.com", "Access to moneybookers is failed!", $message, "Moneybookers IPN Handler", "noreply@website.com");
}

function sendMail($_to, $_subject, $_message, $_fromname, $_fromemail)
{
	$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
    $mail_headers .= "From: \"".$_fromname."\" <".$_fromemail.">\r\n";
	$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
	mail($_to, $_subject, $_message, $mail_headers);
}

?>
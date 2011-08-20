<?php
//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: ../index.php');
	exit();
}

class PayPal
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
		//Check for problems in POSTed items
		if(empty($_POST))
		{
			return "noob"; //no data posted, skip.
		}
		if($_POST['txn_type'] != "web_accept")
		{
			return "noob";
		}

		//Check if payment is valid
		$post = 'cmd=_notify-validate';
		foreach($_POST as $key => $value)
		{
			$key = urlencode($key);
			$value = urlencode($value);
			$post .= "&$key=$value";
		}
		
		//Connect to PayPal
		$api_address = PAYPAL_GATEWAY_URL;
		$sock = fsockopen($api_address,80);
		if(!$sock)
		{
			trigger_error('Failed to connect to PayPal.',E_USER_ERROR);
		}
		
		//Send $post with $header to PayPal to check VALIDATION
		$header = "";
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n"; 
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
		$header .= "Content-Length: " . strlen($post) . "\r\n\r\n"; 
		fputs($sock,$header.$post,strlen($header.$post));
		while(!feof($sock))
		{
			$recv = fgets($sock,1024);
			if(strcmp($recv,'VERIFIED') == 0)
			{
				//Verified payment
				fclose($sock);
				return $this->HandleVerified();
			}
			if(strcmp($recv,'INVALID') == 0)
			{
				//Invalid payment
				fclose($sock);
				$this->HandleInvalid();
				return false;
			}
		}
	}
	
	/**
	 * Handles Invalid Payment Declared by PayPal
	 */
	private function HandleInvalid()
	{
		//Insert information in invalid payments table
		$postdata = $this->GetPostData();
		
		$this->LogPayment(
		array(
			"`status`"			=> "'1'",
			"`transaction_id`"	=> "'%s'",
			"`sender_email`"	=> "'%s'",
			"`payment_status`"	=> "'%s'",
			"`item_name`"		=> "'%s'",
			"`amount`"			=> "'%s'",
			"`currency`"		=> "'%s'",
			"`account_id`"		=> "'%s'",
			"`first_name`"		=> "'%s'",
			"`last_name`"		=> "'%s'",
			"`post_data`"		=> "'%s'",
			"`extra_information`"=> "'INVALID TRANSACTION FROM PAYPAL'",
		), PAYMENTTYPE_INVALID,
		$_POST['txn_id'],
		$_POST['payer_email'],
		$_POST['payment_status'],
		$_POST['item_name'],
		$_POST['mc_gross'],
		$_POST['mc_currency'],
		$_POST['custom'],
		$_POST['first_name'],
		$_POST['last_name'],
		$postdata);
	}
	
	/**
	 * Handles Verified Payment declared by PayPal
	 */
	private function HandleVerified()
	{
		global $cms;
		$postdata = $this->GetPostData();
		
		//Check if some one is trying to HACK or SCAM us
		//Wrong receiver_email
		if($_POST['receiver_email'] != $cms->config['email_paypal'])
		{
			$this->LogPayment(
			array(
				"`status`"			=> "'1'",
				"`transaction_id`"	=> "'%s'",
				"`sender_email`"	=> "'%s'",
				"`payment_status`"	=> "'%s'",
				"`item_name`"		=> "'%s'",
				"`amount_gross`"	=> "'%s'",
				"`amount_fee`"		=> "'%s'",
				"`amount_net`"		=> "'%s'",
				"`currency`"		=> "'%s'",
				"`account_id`"		=> "'%s'",
				"`first_name`"		=> "'%s'",
				"`last_name`"		=> "'%s'",
				"`post_data`"		=> "'%s'",
				"`extra_information`"=> "'WRONG RECEIVER_EMAIL(%s != %s)'",
				"`details`"			=> "'The payment was sent to someone else, please contact an administrator if you think this is an error'",
			), PAYMENTTYPE_VALID,
			$_POST['txn_id'],
			$_POST['payer_email'],
			$_POST['payment_status'],
			$_POST['item_name'],
			$_POST['mc_gross'],
			$_POST['mc_fee'],
			($_POST['mc_gross']-$_POST['mc_fee']),
			$_POST['mc_currency'],
			$_POST['custom'],
			$_POST['first_name'],
			$_POST['last_name'],
			$postdata,
			$_POST['receiver_email'],
			$cms->config['email_paypal']);
			return false;
		}
		//Wrong currency
		if($_POST['mc_currency'] != "USD")
		{
			$this->LogPayment(
			array(
				"`status`"			=> "'1'",
				"`transaction_id`"	=> "'%s'",
				"`sender_email`"	=> "'%s'",
				"`payment_status`"	=> "'Failed'",
				"`item_name`"		=> "'%s'",
				"`amount_gross`"	=> "'%s'",
				"`amount_fee`"		=> "'%s'",
				"`amount_net`"		=> "'%s'",
				"`currency`"		=> "'%s'",
				"`account_id`"		=> "'%s'",
				"`first_name`"		=> "'%s'",
				"`last_name`"		=> "'%s'",
				"`post_data`"		=> "'%s'",
				"`extra_information`"=> "'WRONG CURRENCY: %s'",
				"`details`"			=> "'The payment currency was not in $(USD), please contact an administrator to convert the currency to $(USD)'",
			), PAYMENTTYPE_VALID,
			$_POST['txn_id'],
			$_POST['payer_email'],
			$_POST['item_name'],
			$_POST['mc_gross'],
			$_POST['mc_fee'],
			($_POST['mc_gross']-$_POST['mc_fee']),
			$_POST['mc_currency'],
			$_POST['custom'],
			$_POST['first_name'],
			$_POST['last_name'],
			$postdata,
			$_POST['mc_currency']);
			return false;
		}
		
		//Handle payment according payment_status
		switch($_POST['payment_status'])
		{
			//Completed Transactions
			case "Completed":
				return $this->HandleStatusCompleted(false);
			break;
			case "Canceled_Reversal":
				return $this->HandleStatusCompleted(true);
			break;
			
			//Reversals and Refunds
			case "Reversed":
				return $this->HandleStatusReversed();
			break;
			case "Refunded":
				return $this->HandleStatusReversed();
			break;
			case "Partially_Refunded":
				return $this->HandleStatusReversed();
			break;
			
			//Pending and Unfinished Transactions
			case "In-Progress":
				return $this->HandleStatusPending();
			break;
			case "Pending":
				return $this->HandleStatusPending();
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
	 * Handles completed payments
	 */
	private function HandleStatusCompleted($cancelled_reversal = false)
	{
		$postdata		= $this->GetPostData();
		$accountid 		= $_POST['custom'];
		$amount			= floatval($_POST['mc_gross']);
		$currency		= $_POST['mc_currency'];
		$txn_id 		= $cancelled_reversal ? $_POST['parent_txn_id'] : $_POST['txn_id'];
		$real_txn_id	= $cancelled_reversal ? $_POST['txn_id'] : "";
		
		//Check if transaction has been processed before and if it is "Completed"
		//If it is a cancelled reversal
		if(!$cancelled_reversal)
		{
			$query = new Query();
			$query->Select("`log_payments_paypal`")->Columns(array("COUNT(*)"=>"numrows"))->Where("`transaction_id` = '%s' AND `status` = '0'", $_POST['txn_id'])->Build();
			$checkrecycle = MySQLiFetch($this->sql->query($query, DBNAME), "onerow: 1");
			$numrecycle = $checkrecycle['numrows'];
			
			if((int)$numrecycle > 0)
			{
				//Insert log in INVALID payments
				$this->LogPayment(
				array(
					"status"				=> "'0'",
					"transaction_id"		=> "'%s'",
					"real_transaction_id"	=> "'%s'",
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
				), PAYMENTTYPE_INVALID,
				$txn_id,
				$real_txn_id,
				$_POST['payer_email'],
				$_POST['payment_status'],
				$_POST['item_name'],
				$amount,
				$currency,
				$accountid,
				$_POST['first_name'],
				$_POST['last_name'],
				$postdata);
				
				return false;
			}
		}
		
		//Delete pending payments with same transaction id
		$query = new Query();
		$query->Delete("`log_payments_paypal`")->Where("`transaction_id` = '%s' AND `status` = '2'", $_POST['txn_id'])->Build();
		$this->sql->query($query, DBNAME);
		
		//Everything is OK, Insert LOG for successful payment
		$details = $cancelled_reversal ? "Reversal was cancelled" : "Transaction was successful";
		$this->LogPayment(
		array(
			"`status`"				=> "'0'",
			"`transaction_id`"		=> "'%s'",
			"`real_transaction_id`"	=> "'%s'",
			"`sender_email`"		=> "'%s'",
			"`payment_status`"		=> "'%s'",
			"`item_name`"			=> "'%s'",
			"`amount_gross`"		=> "'%s'",
			"`amount_fee`"			=> "'%s'",
			"`amount_net`"			=> "'%s'",
			"`currency`"			=> "'%s'",
			"`account_id`"			=> "'%s'",
			"`first_name`"			=> "'%s'",
			"`last_name`"			=> "'%s'",
			"`post_data`"			=> "'%s'",
			"`extra_information`"	=> "'SUCCESSFUL PAYMENT!'",
			"`details`"				=> "'$details, Points were added to your account!'",
		), PAYMENTTYPE_VALID,
		$txn_id,
		$real_txn_id,
		$_POST['payer_email'],
		$_POST['payment_status'],
		$_POST['item_name'],
		$amount,
		$_POST['mc_fee'],
		($_POST['mc_gross']-$_POST['mc_fee']),
		$currency,
		$accountid,
		$_POST['first_name'],
		$_POST['last_name'],
		$postdata);
		
		return array("amount" => $amount, "accountid" => $accountid);
	}
	
	/**
	 * Handles refunded or reversed payments
	 */
	private function HandleStatusReversed()
	{
		$postdata	= $this->GetPostData();
		$amount		= floatval($_POST['mc_gross']);
		$amount		= abs($amount);
		$accountid	= $_POST['custom'];
		$currency	= $_POST['mc_currency'];
		
		//Insert log for negative transaction with the amount in negative
		$this->LogPayment(
		array(
			"`status`"				=> "'3'",
			"`transaction_id`"		=> "'%s'",
			"`real_transaction_id"	=> "'%s'",
			"`sender_email`"		=> "'%s'",
			"`payment_status`"		=> "'%s'",
			"`item_name`"			=> "'%s'",
			"`amount_gross`"		=> "'%s'",
			"`amount_fee`"			=> "'%s'",
			"`amount_net`"			=> "'%s'",
			"`currency`"			=> "'%s'",
			"`account_id`"			=> "'%s'",
			"`first_name`"			=> "'%s'",
			"`last_name`"			=> "'%s'",
			"`post_data`"			=> "'%s'",
			"`extra_information`"	=> "'REVERSED PAYMENT!'",
			"`details`"				=> "'Transaction was reversed or refunded, Points were deducted from your account!'",
		), PAYMENTTYPE_VALID,
		$_POST['parent_txn_id'],
		$_POST['txn_id'],
		$_POST['payer_email'],
		$_POST['payment_status'],
		$_POST['item_name'],
		$amount,
		$_POST['mc_fee'],
		($_POST['mc_gross']-$_POST['mc_fee']),
		$currency,
		$accountid,
		$_POST['first_name'],
		$_POST['last_name'],
		$postdata);

		return array("amount" => -$amount, "accountid" => $accountid);
	}
	
	/**
	 * Handles transactions that are not completed yet but will be completed later
	 */
	private function HandleStatusPending()
	{
		$postdata	= $this->GetPostData();
		$amount		= floatval($_POST['mc_gross']);
		$accountid	= $_POST['custom'];
		$currency	= $_POST['mc_currency'];
		
		//Insert log for pending transaction
		$this->LogPayment(
		array(
			"`status`"			=> "'2'",
			"`transaction_id`"	=> "'%s'",
			"`sender_email`"	=> "'%s'",
			"`payment_status`"	=> "'%s'",
			"`item_name`"		=> "'%s'",
			"`amount_gross`"	=> "'%s'",
			"`amount_fee`"		=> "'%s'",
			"`amount_net`"		=> "'%s'",
			"`currency`"		=> "'%s'",
			"`account_id`"		=> "'%s'",
			"`first_name`"		=> "'%s'",
			"`last_name`"		=> "'%s'",
			"`post_data`"		=> "'%s'",
			"`extra_information`"=> "'UNFINISHED PAYMENT!'",
			"`details`"			=> "'Transaction is currently pending, Points will be added once the transaction is completed'",
		), PAYMENTTYPE_VALID,
		$_POST['txn_id'],
		$_POST['payer_email'],
		$_POST['payment_status'],
		$_POST['item_name'],
		$amount,
		$_POST['mc_fee'],
		($_POST['mc_gross']-$_POST['mc_fee']),
		$currency,
		$accountid,
		$_POST['first_name'],
		$_POST['last_name'],
		$postdata);
		
		return false;
	}
	
	private function HandleStatusFailed()
	{
		$postdata	= $this->GetPostData();
		$amount		= floatval($_POST['mc_gross']);
		$accountid	= $_POST['custom'];
		$currency	= $_POST['mc_currency'];
		
		//Insert log for failed transaction
		$this->LogPayment(
		array(
			"`status`"			=> "'1'",
			"`transaction_id`"	=> "'%s'",
			"`sender_email`"	=> "'%s'",
			"`payment_status`"	=> "'%s'",
			"`item_name`"		=> "'%s'",
			"`amount_gross`"	=> "'%s'",
			"`amount_fee`"		=> "'%s'",
			"`amount_net`"		=> "'%s'",
			"`currency`"		=> "'%s'",
			"`account_id`"		=> "'%s'",
			"`first_name`"		=> "'%s'",
			"`last_name`"		=> "'%s'",
			"`post_data`"		=> "'%s'",
			"`extra_information`"=> "'FAILED PAYMENT!'",
			"`details`"			=> "'Transaction Failed'",
		), PAYMENTTYPE_VALID,
		$_POST['txn_id'],
		$_POST['payer_email'],
		$_POST['payment_status'],
		$_POST['item_name'],
		$amount,
		$_POST['mc_fee'],
		($_POST['mc_gross']-$_POST['mc_fee']),
		$currency,
		$accountid,
		$_POST['first_name'],
		$_POST['last_name'],
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
	
	private function LogPayment($columns, $paymenttype)
	{
		$args = func_get_args();
		array_shift($args); array_shift($args);
		
		if($paymenttype == PAYMENTTYPE_VALID)
		{
			$table = "log_payments_paypal";
		}
		else
		{
			$table = "log_invalidpayments_paypal";
		}
		
		$query = new Query();
		$query->Insert("`$table`")->Columns($columns, $args)->Build();
		$result = $this->sql->query($query, DBNAME);
		
		return $result;
	}
	
}

 ?>
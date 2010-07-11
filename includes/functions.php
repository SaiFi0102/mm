<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

/**
 * Validates Email address format
 * 
 * @param string $data Email address
 * @param boolean $strict
 * @return boolean
 */
function ValidateEmail($data, $strict = false) 
{ 
	$regex = $strict? '/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' : '/^([*+!.&#$�\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i';
	if(preg_match($regex, trim($data), $matches))
	{ 
		return true;
	}
	else
	{ 
		return false; 
	} 
}

/**
 * Formats the timestamp to time()'s format
 *
 * @param string $string time()'s format
 * @return string
 */
function FormatTime($string = '')
{
	if(empty($string))
	{
		// use "now":
		$time = time();
	}
	
	elseif (preg_match('/^\d{14}$/', $string))
	{
		// it is mysql timestamp format of YYYYMMDDHHMMSS?			
		$time = mktime(substr($string, 8, 2),substr($string, 10, 2),substr($string, 12, 2),
					   substr($string, 4, 2),substr($string, 6, 2),substr($string, 0, 4));
	}
	elseif (is_numeric($string))
	{
		// it is a numeric string, we handle it as timestamp
		$time = (int)$string;
	}
	else
	{
		// strtotime should handle it
		$time = strtotime($string);
		if ($time == -1 || $time === false)
		{
			// strtotime() was not able to parse $string, use "now":
			$time = time();
		}
	}
	return $time;
}

/**
 * Converts A-Z to lowercase only
 *
 * @param string $string
 * @return string
 */
function _strtolower($string)
{
	return strtr($string, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
}

/**
 * Converts a-z to uppercase only
 *
 * @param string $string
 * @return string
 */
function _strtoupper($string)
{
	return strtr($string, 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
}

/**
 * Prints arrays and objects with <PRE> tags wrapped up
 * &Alias of print_r
 *
 * @param mixed $expression
 * @param unknown_type $return
 */
function _print_r($expression, $return = null)
{
	print "<pre>";
	print_r($expression, $return);
	print "</pre>";
}

/**
 * Prints arrays and objects with they data variable information
 * with <PRE> tags wrapped up
 * &Alias of var_dump()
 *
 * @param mixed $expression1
 * @param unknown_type $expression2
 */
function _var_dump($expression1, $expression2 = null)
{
	print "<pre>";
	var_dump($expression1, $expression2);
	print "</pre>";
}

/**
 * Sets the first character to upper case and returns
 *
 * @param string $str
 * @return string
 */
function FirstCharUpper($str)
{
	$firstletter = substr($str, 0, 1);
	$restletters = substr($str, 1);
	$firstletter = strtoupper($firstletter);
	return $firstletter.$restletters;
}

/**
 * Converts MySQL Timestamp to date(); format
 *
 * @param string $timestamp TimeStamp. For now set null or dont set $timestamp
 * @param string $format Date(); format. For information check www.php.net/date
 * @return string
 */
function ConvertMysqlTimestamp($timestamp = null, $format = "j F Y, g:i a")
{
	$dt = date_create($timestamp);
	return date_format($dt, $format);
}

/**
 * Sha1 Password Format
 * 
 * @param $user
 * @param $pass
 * @return string
 */
function Sha1Pass($user, $pass)
{
	$str = strtoupper($user). ":" . strtoupper($pass);
	return sha1($str);
}

/**
 * Returns the the time in unix format $offset minutes before actual time
 * @param $offset
 * @return Int
 */
function UserTimeout($offset = 0)
{
	global $USERTIMEOUT;
	if($offset == 0)
	{
		$offset = $USERTIMEOUT;
	}
	return (time() - ($offset*60));
}

/**
 * Returns Date of Ban Timeout but if timestamp is 0 or null it means banned for ever
 * @param $timestamp
 * @return unknown_type
 */
function BanTimeOut($timestamp, $format = "j F Y, g:i a")
{
	if(!$timestamp)
	{
		return 'Never';
	}
	else
	{
		return date($format, $timestamp);
	}
}

/**
 * Checks if username includes illegal characters
 * @param $username
 * 
 * @return array
 */
function CheckUsername($username)
{
	global $cms, $LOGONDB;
	if(empty($username) || $username == null)
	{
		return USERNAME_EMPTY;
	}
	if(preg_match("#\t#i", $username) || preg_match("#\n#i", $username) || preg_match("#\r#i", $username))
	{
		return USERNAME_ILLEGAL_CHARACTER;
	}
	if(preg_match("#\s#i", $username))
	{
		return USERNAME_ILLEGAL_SPACE;
	}
	if(strlen($username) < $cms->config['userminlen'])
	{
		return USERNAME_LENTH_BELOW;
	}
	if(strlen($username) > $cms->config['usermaxlen'])
	{
		return USERNAME_LENTH_ABOVE;
	}
	
	$q = $LOGONDB->Query("SELECT username FROM account WHERE username = '%s'", $username);
	if($LOGONDB->numRows($q) > 0)
	{
		return USERNAME_EXISTS;
	}
	return 0;
}

/**
 * Checks email if it is valid
 * @param $email
 * @param $confirmemail
 */
function CheckEmail($email, $confirmemail=null)
{
	global $LOGONDB;
	if(empty($email) || $email == null)
	{
		return EMAIL_EMPTY;
	}
	if(!ValidateEmail($email))
	{
		return EMAIL_FORMAT;
	}
	if($confirmemail != null)
	{
		if($email != $confirmemail)
		{
			return EMAIL_CONFIRM;
		}
	}
	
	$q = $LOGONDB->Query("SELECT email FROM account WHERE email='%s'", $email);
	if($LOGONDB->numRows($q) > 0)
	{
		return USERNAME_EXISTS;
	}
	return 0;
}

/**
 * Just a solution for Js DOM value changers
 * @param $flags
 */
function FixExpansionFlags($flags)
{
	if((int)$flags > 2)
	{
		$return = 2;
	}
	elseif((int)$flags < 0)
	{
		$return = 0;
	}
	else
	{
		$return = $flags;
	}
	return $return;
}

/**
 * Changes GMLevel from mangos to group names
 * @param $access
 */
function AccessLevelToGroup($access, $uppercase = false)
{
	switch((int)$access)
	{
		case -1:
			if($uppercase) return "Guest";
			return "guest";
		break;
		case 0:
			if($uppercase) return "Player";
			return "player";
		break;
		case 1:
			if($uppercase) return "Lower Game Master";
			return "lower game master";
		break;
		case 2:
			if($uppercase) return "Lower Game Master";
			return "lower game master";
		break;
		case 3:
			if($uppercase) return "Game Master";
			return "game master";
		break;
		case 4:
			if($uppercase) return "Executive";
			return "executive";
		break;
		
		default:
			if($uppercase) return "Player";
			return "player";
		break;
	}
}

/**
 * Escapes HTML and Quotes for forms
 * @param $string
 */
function EscapeHtml($string)
{
	//Remove '\' from before quotes for forms
	$return = str_replace(array("\\'", "\\\""), array("'", "\""), $string);
	$return = htmlspecialchars($return);
	return $return;
}

/**
 * Converts WoW Money into an array for copper silver and gold
 * @param $money
 */
function ConvertGold($money)
{
	$gold['copper'] = null;
	$gold['silver'] = null;
	$gold['gold'] = null;
	
	if($money >= 10000)
	{
		$gold['copper'] = (int)substr($money, -2);
		$gold['silver'] = (int)substr($money, -4, 2);
		$gold['gold'] = (int)substr($money, 0, -4);
	}
	if($money >= 100 && $money < 10000)
	{
		$gold['copper'] = (int)substr($money, -2);
		$gold['silver'] = (int)substr($money, 0, -2);
	}
	if($money < 100)
	{
		$gold['copper'] = (int)$money;
	}
	return $gold;
}

/**
 * Parses [gold] tags to number of copper silver and gold with their icons
 * @param $str
 */
function ParseGold(&$str)
{
	preg_match_all("#\[gold\](.+?)\[/gold\]#s", $str, $moneys);
	foreach($moneys[0] as $moneyy)
	{
		$money = str_replace(array("[gold]", "[/gold]"), "", $moneyy);
		$money = ConvertGold($money);
		$moneyhtml = null;
		if($money['gold'])
		{
			$moneyhtml .= $money['gold']." <img src='images/icons/money_gold.gif' alt='Gold' /> ";
		}
		if($money['silver'])
		{
			$moneyhtml .= $money['silver']." <img src='images/icons/money_silver.gif' alt='Silver' /> ";
		}
		if($money['copper'])
		{
			$moneyhtml .= $money['copper']." <img src='images/icons/money_copper.gif' alt='Copper' />";
		}
		$str = str_replace($moneyy, $moneyhtml, $str);
	}
	$str = str_replace("[gold][/gold]", "0 <img src='images/icons/money_copper.gif' alt='Copper' />", $str);
	return $str;
}

/**
 * Turns rewards items column into array with itemid and itemcount
 * @param $items
 */
function RewardsItemsColumnToArray($items)
{
	global $DB;
	
	//Separate Items
	$itemarray = explode(",", $items);
	
	//Build array
	$return = array();
	foreach($itemarray as $item)
	{
		//Separate itemid and itemcount
		$itemdata = explode(":", $item);
		$itemid = $itemdata[0];
		$itemcount = $itemdata[1];
		
		if(empty($itemid))
		{
			continue;
		}
		if(empty($itemcount))
		{
			$itemcount = 1;
		}
		
		$return[] = array("itemid"=>$itemid, "itemcount"=>$itemcount);
	}
	return $return;
}

/**
 * From WoW Quality ID to Quality Colour CSS Class
 * @param $quality
 */
function ItemQualityToColorClass($quality=1)
{
	switch($quality)
	{
		default:
			return "qc-common";
		break;
		case null:
			return "qc-common";
		break;
		
		case 0:
			return "qc-poor";
		break;
		case 1:
			return "qc-common";
		break;
		case 2:
			return "qc-uncommon";
		break;
		case 3:
			return "qc-rare";
		break;
		case 4:
			return "qc-epic";
		break;
		case 5:
			return "qc-legendary";
		break;
		case 6:
			return "qc-artifact";
		break;
		case 7:
			return "qc-heirloom";
		break;
	}
}

/**
 * Fetches Items Data
 * @param $rewards
 * @param $rid
 */
function FetchItemsData($rewards, $rid)
{
	global $WORLDDB, $DB;
	$items = array();
	foreach($rewards as $reward)
	{
		$rewarditems = explode(",", $reward['items']);
		foreach($rewarditems as $rewarditem)
		{
			$rewarditem = explode(":", $rewarditem);
			$rewarditem = $rewarditem[0];
			if($rewarditem != '0')
			{
				$items[$rewarditem] = "";
			}
		}
	}
	
	//Build $last
	if(!count($items))
	{
		return;
	}
	else
	{
		$last = "WHERE entry IN(";
		foreach($items as $itemid => $useless)
		{
			$last .= "$itemid, ";
		}
		$last = substr($last, 0, -2);
		$last .= ")";
	}
	
	//Fetch
	$ir = $WORLDDB[$rid]->Select(array("entry","name","quality"), "item_template", $last, false);
	$itemarray = array();
	foreach($ir as $iir)
	{
		$itemarray[$iir['entry']] = array($iir['name'],$iir['quality']);
	}
	return $itemarray;
}
/**
 * ItemID to ItemName
 * @param $itemarray
 * @param $itemid
 */
function ItemIdToName($itemarray, $itemid)
{
	if(empty($itemarray[$itemid]))
	{
		$return = "Unknown Item(ID: $itemid)";
		return $return;
	}
	return $itemarray[$itemid][0];
}

/**
 * Returns time left for voting to start
 * @param $votedtime
 */
function VoteTimeLeft($votedtime)
{
	$expiretime = ($votedtime + (60*60*12));
	return StrTimeLeft($expiretime);
}

/**
 * Returns an array with vote gateways
 */
function FetchVoteGateways()
{
	global $DB;
	$return = $DB->Select("*", "vote_gateways");
	
	return $return;
}
/**
 * Returns an array with hours minutes and seconds left!
 * @param $timestamp
 */
//FUNCTION FOR TIME LEFT 
function StrTimeLeft($integer) 
{
	$integer -= time();
	$seconds=$integer;
	
	if($seconds/60 >=1)
	{
		$minutes=floor($seconds/60);
	
		if($minutes/60 >= 1)
		{ # Hours
			$hours=floor($minutes/60);
		
			if($hours/24 >= 1)
			{ #days
				
				$days=floor($hours/24);
			
				if($days/7 >=1)
				{ #weeks
					$weeks=floor($days/7);
				
					if($weeks>=2) $return="$weeks Weeks";
				
					else $return="$weeks Week";
			
				} #end of weeks
			
				$days=$days-(floor($days/7))*7;
			
				if($weeks>=1 && $days >=1) $return="$return, ";
			
				if($days >=2) $return="$return $days days"; 
			
				if($days ==1) $return="$return $days day"; 
			
			} #end of days 
		
			$hours=$hours-(floor($hours/24))*24;
		
			if($days>=1 && $hours >=1) $return="$return, ";
		
			if($hours >=2) $return="$return $hours hours"; 
		
			if($hours ==1) $return="$return $hours hour"; 
		
		} #end of Hours 
	
		$minutes=$minutes-(floor($minutes/60))*60;
	
		if($hours>=1 && $minutes >=1) $return="$return, ";
	
		if($minutes >=2) $return="$return $minutes minutes";
	
		if($minutes ==1) $return="$return $minutes minute";

	} #end of minutes

	$seconds=$integer-(floor($integer/60))*60;

	if($minutes>=1 && $seconds >=1) $return="$return, ";

	if($seconds >=2) $return="$return $seconds seconds"; 

	if($seconds ==1) $return="$return $seconds second"; 

	return $return;
}

/**
 * Returns random 50 player names with thier guid
 * @param $rid
 */
function RandomOnlinePlayers($rid)
{
	global $CHARACTERDB;
	$return = $CHARACTERDB[$rid]->Select(array("guid", "name"), "characters", "WHERE online <> '0' ORDER BY RAND() LIMIT 50");
	return $return;
}

function RemoveGetRefFromLogin($url)
{
	$url = urldecode($url);
	$url = preg_replace(array("#\?ref=(.+)#i", "#\&ref=(.+)#i"), "", $url);
	return $url;
}

/**
 * Generates a random characters based on lower case, upper case letters and numbers
 * while it doesnt lets more than 4 consecutive type(lower,upper,number) be generated
 * @param $length
 */
function RandomCharacters($length)
{
	$upperletters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$lowerletters = "abcdefghijklmnopqrstuvwxyz";
	
	$countupper = 0;
	$countlower = 0;
	$countnumbers = 0;
	$countletters = 0;
	$return = null;
	
	$i = 1;
	while($length > $i)
	{
		if((rand(0, 1) == 0 && $countletters <= 3) || $countnumbers > 3) //0 means letters, 1 means numbers
		{
			if((rand(0, 1) == 0 && $countlower <= 3) || $countupper > 3)//0 means lower case, 1 means upper case
			{
				$round = rand(0, strlen($lowerletters));
				$return .= substr($lowerletters, ($round - 1), 1);
				$countlower++;
				if($countupper > 3)
				{
					$countupper = 0;
				}
			}
			else
			{
				$round = rand(0, strlen($upperletters));
				$return .= substr($upperletters, ($round - 1), 1);
				$countupper++;
				if($countlower > 3)
				{
					$countlower = 0;
				}
			}
			$countletters++;
			if($countnumbers > 3)
			{
				$countnumbers = 0;
			}
		}
		else
		{
			$return .= rand(0, 9);
			$countnumbers++;
			if($countletters > 3)
			{
				$countletters = 0;
			}
		}
		$i++;
	}
	
	return $return;
}

/**
 * Sends an email using SWIFTMAILER package
 *
 * @param mixed $to Array('email'=>'name')/Array('email') OR String('email')
 * @param string $subject
 * @param string $body
 * @param string $bodytype text/plain OR text/html
 * @param string $from Leave it null or emtpy if you want to use default from email
 * @param string $attatch Path to attatchment file
 * @param string $characterset Dont change or set this value if u dont know what you are doing
 * 
 * @return array
 */
function SendEmail($to, $subject, $body, $from = '', $bodytype = 'text', $attatch = '', $characterset = 'UTF-8')
{
	global $email;
	
	//Create Email Transporter
	//Default to MAIL_PHPMAIL
	if($email['type'] == MAIL_SMTP)
	{
		$transport = Swift_SmtpTransport::newInstance($email['smtp']['host'], $email['smtp']['port']);
		if(!empty($email['smtp']['username'])) $transport->setUsername($email['smtp']['username']);
		if(!empty($email['smtp']['password'])) $transport->setPassword($email['smtp']['password']);
		if($email['smtp']['encrypted']) $transport->setEncryption('ssl');
	}
	if($email['type'] == MAIL_SENDMAIL)
	{
		$transport = Swift_SendmailTransport::newInstance('/usr/sbin/exim -bs');
	}
	else
	{
		$transport = Swift_MailTransport::newInstance();
	}
	
	//Create Email Message
	if($bodytype == 'text') $bodytype = 'text/plain';
	if($bodytype == 'plain') $bodytype = 'text/plain';
	if($bodytype == 'html') $bodytype = 'text/html';
	
	if(!$from) $from = $email['from'];
	
	$message = Swift_Message::newInstance()->setCharset($characterset);
	$message->setSubject($subject);
	$message->setBody($body, $bodytype);
	$message->setFrom($from);
	$message->setTo($to);
	if($attatch) $message->attach(Swift_Attachment::fromPath($attatch));
	
	//Ready and Send the message
	$mailer = Swift_Mailer::newInstance($transport);
	$result = $mailer->send($message, $fail);
	return array('result'=>$result, 'fail'=>$fail);
}

?>
<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

class Realm
{
	public $rid;
	
	private $realmconf;
	private $chdb;
	private $wdb;
	
	/**
	 * Constructor
	 * @param int $rid
	 */
	function __construct($rid)
	{
		global $REALM, $CHARACTERDB, $DB;
		if(!isset($REALM[$rid]))
		{
			trigger_error("Wrong realm ID", E_USER_ERROR);
		}
		
		$this->rid = $rid;
		$this->realmconf = $REALM[$rid];
		$this->chdb = $CHARACTERDB[$rid];
		$this->db = $DB;
	}
	
	/**
	 * Fetches characters with USERID in ARGUMENT #2 
	 * @param string $extra
	 * @param mixed $userid
	 * @param mixed ...
	 * 
	 * @return array
	 */
	public function FetchCharactersByAccountID($extra = "", $uid)
	{
		$args = func_get_args();
		array_shift($args);
		
		$last  = "WHERE `account` = '%s' ";
		$last .= $extra;
		
		return $this->_FetchCharacters($last, false, "*", $args);
	}
	
	/**
	 * Fetches character with CHARACTERID in ARGUMENT #2
	 * @param string $extra
	 * @param mixed $cid
	 * @param mixed ...
	 */
	public function FetchCharacterByCharacterID($extra = "", $cid)
	{
		$args = func_get_args();
		array_shift($args);
		
		$last  = "WHERE `guid` = '%s' ";
		$last .= $extra;
		
		return $this->_FetchCharacters($last, true, "*", $args);
	}
	
	/**
	 * Fetches character homebind data with CHARACTERID in ARGUMENT #2
	 * @param string $extra
	 * @param mixed $cid
	 */
	public function FetchCharacterBindDataByCharacterID($extra = "", $cid)
	{
		$args = func_get_args();
		array_shift($args);
		
		$last  = "WHERE `guid` = '%s' ";
		$last .= $extra;
		
		return $this->chdb->Select("*", "character_homebind", $last, true, $args);
	}
	
	function CheckRealmStatusAndOnlinePlayers($rid)
	{
		global $LOGONDB;
		$status = pfsockopen($this->realmconf['IP'], $this->realmconf['PORT'], $errno, $errstr, 5);
		
		$uptime = $LOGONDB->Select(array("starttime"), "uptime", "WHERE realmid='%s' ORDER BY starttime DESC LIMIT 1", true, $this->rid);
		$maxonline = $LOGONDB->Select("maxplayers", "uptime", "WHERE realmid='%s' ORDER BY maxplayers DESC LIMIT 1", true, $this->rid);
		$struptime = $uptime['starttime'];
		$struptime = StrDateDiff(time(), $struptime);
		
		if($status == false)
		{
			return array("status"=>false, "online"=>0, "uptime"=>"Offline", "maxplayers"=>$maxonline['maxplayers']);
		}
		
		$online = $this->chdb->Select("guid", "characters", "WHERE online <> '0'", true);
		return array("status"=>true, "online"=>$this->chdb->AffectedRows, "uptime"=>$struptime, "maxplayers"=>$maxonline['maxplayers']);
	}
	
	/**
	 * Executed a command on the server
	 * @param string $command
	 * 
	 * @return boolean
	 */
	public function ExecuteSoapCommand($command)
	{
		//Setup SOAP Client
		$client = new SoapClient(NULL,
		array(
			"location" => "http://".$this->realmconf['IP'].":".$this->realmconf['SOAP']['port']."/",
			"uri" => "urn:MaNGOS",
			"style" => SOAP_RPC,
			"login" => $this->realmconf['SOAP']['user'],
			"password" => $this->realmconf['SOAP']['pass'],
		));
		
		
		try //Try to execute function
		{
			$result = $client->executeCommand(new SoapParam($command, "command"));
		}
		catch(Exception $e) //Don't give fatal error if there is a problem
		{
			$this->_LogSoapError($e);
			return array('sent'=>false, 'message'=>$e->getMessage());
		}
		return array('sent'=>true, 'message'=>$result);
	}
	
	/**
	 * Checks and Sends Rewards By WoW Mail
	 * @param mixed $rewardid
	 * @param mixed $characterid
	 * @param integer $votedonate
	 * 
	 * @return array(message,bool)
	 */
	public function SendReward($rewardid, $characterid, $votedonate)
	{
		global $USER, $LOGONDB;
		
		//If $votedonate is incorrect
		if($votedonate != REWARD_VOTE && $votedonate != REWARD_DONATE)
		{
			return array('message'=>"There was an internal error, please contact an administrator!", 'bool'=>false);
		}
		
		//Setting a log session
		$reward_delivery_table = $votedonate ? "log_donatereward_delivery" : "log_votereward_delivery"; //$votedonate
		$session = $this->db->Select("max(session) as maxsession", $reward_delivery_table, null, true);
		$session = $session['maxsession'];
		if(empty($session))
		{
			$session = 1;
		}
		else
		{
			$session = intval($session) + 1;
		}
		
		//Fetch Reward and Check for errors
		$rewards_table = $votedonate ? "rewards_donation" : "rewards_voting"; //$votedonate
		$reward = $this->db->Select("*", $rewards_table, "WHERE id = '%s' AND realm = '%s'", true, $rewardid, $this->rid);
		if(!count($reward)) //If reward does not exists
		{
			return array('message'=>"The reward you selected does not exists on the selected realm.", 'bool'=>false);
		}
		
		//If the user doesnt have enuff points
		$points_var = $votedonate ? $USER['donationpoints'] : $USER['votepoints']; //$votedonate
		if($points_var < $reward['points'])
		{
			return array('message'=>"You do not have enough points to get this reward! Please get more points.", 'bool'=>false);
		}
		
		//Fetch Character and check if it exists
		$character = $this->chdb->Select("name", "characters", "WHERE guid = '%s' AND account='%s'", true, $characterid, $USER['id']);
		if(!count($character))
		{
			return array('message'=>"The character you selected does not exists on the selected realm.", 'bool'=>false);
		}
		
		//Success(If sending mail succeeds then $success=true)
		$success = false;
		
		//Build Items Array
		$rdatas = RewardsItemsColumnToArray($reward['items']);
		$rewardarray = array();
		$r_i = 1;
		$key = 1;
		foreach($rdatas as $rkey => $rdata) //Split into array with 10 items limited in one key
		{
			if($r_i >= 10)
			{
				$key++;
				$r_i = 1;
			}
			$rewardarray[$key][] = $rdata;
			$r_i++;
		}
		
		//Try to send the items first
		foreach($rewardarray as $rarr)
		{
			//$command = .send items #playername "#subject" "#text" itemid1[:count1] itemid2[:count2] ... itemidN[:countN]
			$command  = ".send items ";
			$command .= $character['name']; //Character Name
			$command .= " \"Thank you!\""; //Subject
			$command .= " \"Dear ". FirstCharUpper($character['name']) .",\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\n{$cms->config['websitename']} Staff\""; //Body
			//Items
			foreach($rarr as $r)
			{
				$command .= " ". $r['itemid'].":".$r['itemcount'];
			}
			
			//Now connect to SOAP and send the item
			$result = $this->ExecuteSoapCommand($command);
			if($result['sent'] != false)
			{
				$success = true;
				$logsent = true;
			}
			else
			{
				$logsent = false;
				$return = array('message'=>"There was a problem with the server, please try again in a few minutes. If this problem persists, please contact an administrator!", 'bool'=>false);
			}
			
			//Log Delivery
			$this->_LogRewardDelivery($session, $command, $result['message'], $characterid, $rewardid, $logsent, $votedonate);
		}
		
		//Try to send the gold now. $command = .send money #playername "#subject" "#text" #money
		if($reward['gold'] > 0)
		{
			$command  = ".send money ";
			$command .= $character['name']; //Character Name
			$command .= " \"Thank you!\""; //Subject
			$command .= " \"Dear ". FirstCharUpper($character['name']) .",\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\n{$cms->config['websitename']} Staff\""; //Body
			$command .= $reward['gold'];
			
			//Now connect to SOAP and send the gold
			$result = $this->ExecuteSoapCommand($command);
			if($result['sent'] != false)
			{
				$success = true;
				$logsent = true;
			}
			else
			{
				$logsent = false;
				$return = array('message'=>"There was a problem with the server, please try again in a few minutes. If this problem persists, please contact an administrator!", 'bool'=>false);
			}
			
			//Log Gold Delivery
			$this->_LogRewardDelivery($session, $command, $result['message'], $characterid, $rewardid, $logsent, $votedonate);
		}
		
		//Deduct points if success = true
		if($success)
		{
			$pointcolumn = $votedonate ? "donationpoints" : "votepoints"; //$votedonate
			$LOGONDB->Update(array("$pointcolumn"=>"$pointcolumn - '%s'"), "account_mm_extend", "WHERE accountid = '%s'", $reward['points'], $USER['id']);
			
			//Update $USER variables so that it doesnt confuse player that no points were deducted cuz it takes one extra reload to reload USER vars because of positionning!
			if($votedonate == REWARD_DONATE)
			{
				$USER['donationpoints'] -= $reward['points'];
			}
			if($votedonate == REWARD_VOTE)
			{
				$USER['votepoints'] -= $reward['points'];
			}
		}
		
		//Return message and bool
		if(isset($return['bool']) && $return['bool'] == false && $success == true)
		{
			$return['message']  = "Some of the items were not sent because " . $return['message'];
			$return['message'] .= " Please relogin and check your mailbox in 5 minutes for your reward.";
			$return['bool']		= true;
		}
		if(!isset($return) && $success == true)
		{
			$return = array('message'=>"You've successfully received the reward. Please relogin and check your mailbox in 5 minutes for your reward.", 'bool'=>true);
		}
		return $return;
	}
	
	/**
	 * PRIVATE FUNCTIONS
	 */
	
	/**
	 * Handler to fetch characters according to sub functions
	 * @param string $last
	 * @param boolean $oneRow
	 * @param mixed $columns
	 * 
	 * @return array
	 */
	private function _FetchCharacters($last, $oneRow = false, $columns = "*", $args = array())
	{
		$CharacterDataArr = $this->chdb->Select($columns, "characters", $last, $oneRow, $args);
		return $CharacterDataArr;
	}
	
	/**
	 * Logs soap error with variable $e class Exception into file soaperror.log in adminstration/logs
	 * @param Exception $e
	 */
	private function _LogSoapError($e)
	{
		$date = date('D d/m/Y');
		$time = date('G:i:s');
		$ip = $_SERVER['REMOTE_ADDR'];
		$error = $e->getMessage();
		$errorcode = $e->getCode();
		$file = $e->getFile();
		$line = $e->getLine();
		
$errorstring = "\r\n
|----------------------------SOAP Command Error-----------------------------------
|Date: $date, Time: $time, From: $ip
|Where: $file(Line: $line) Error Code: $errorcode
|Error: $error
|----------------------------SOAP Command Error-----------------------------------";
		
		$f = fopen(DOC_ROOT."/administration/logs/soaperror.log", "a+");
		fwrite($f, $errorstring);
		fclose($f);
	}
	
	private function _LogRewardDelivery($session, $command, $message, $cid, $rewardid, $sent, $votedonate)
	{
		//Table name
		switch($votedonate)
		{
			case REWARD_DONATE:
				$table = "log_donatereward_delivery";
				//Log donated for character in characters table
				if($sent)
				{
					$this->chdb->Insert(array("guid"=>"'%s'", "donated"=>"'1'"), "character_mm_extend", true, $cid);
				}
			break;
			case REWARD_VOTE:
				$table = "log_votereward_delivery";
			break;
			default:
				return;
			break;
		}
		$sent = $sent ? 1 : 0;
		
		//Insert into DB
		$this->db->Insert(array(
			'session'		=> "'%s'",
			'command'		=> "'%s'",
			'message'		=> "'%s'",
			'characterid'	=> "'%s'",
			'realmid'		=> "'%s'",
			'rewardid'		=> "'%s'",
			'sent'			=> "'%s'",),
		$table, false,
		$session, $command, $message, $cid, $this->rid, $rewardid, $sent);
	}
	
}

?>
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
	public $remoteconn;
	
	private $realmconf;
	private $db;
	
	/**
	 * Constructor
	 * @param int $rid
	 */
	function __construct($rid)
	{
		global $REALM, $DB;
		if(!isset($REALM[$rid]))
		{
			trigger_error("Wrong realm ID", E_USER_ERROR);
		}
		
		$this->rid = $rid;
		$this->realmconf = $REALM[$rid];
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
		$args = func_get_args(); array_shift($args);
		
		$Where = "`account` = '%s' ";
		$Where .= $extra;
		
		$query = new MMQueryBuilder();
		$query->Select("`characters`")->Columns("*")->Where($Where, $args)->Build();
		$data = MMMySQLiFetch($this->db->query($query, $this->realmconf['CH_DB']));
		
		return $data;
	}
	
	/**
	 * Fetches character with CHARACTERID in ARGUMENT #2
	 * @param string $extra
	 * @param mixed $cid
	 * @param mixed ...
	 */
	public function FetchCharacterByCharacterID($extra = "", $cid)
	{
		$args = func_get_args(); array_shift($args);
		
		$Where  = "`guid` = '%s' ";
		$Where .= $extra;
		
		$query = new MMQueryBuilder();
		$query->Select("`characters`")->Columns("*")->Where($Where, $args)->Build();
		$data = MMMySQLiFetch($this->db->query($query, $this->realmconf['CH_DB']), "onerow: 1");
		
		return $data;
	}
	
	/**
	 * Fetches character homebind data with CHARACTERID in ARGUMENT #2
	 * @param string $extra
	 * @param mixed $cid
	 */
	public function FetchCharacterBindDataByCharacterID($extra = "", $cid)
	{
		$args = func_get_args(); array_shift($args);
		
		$Where  = "`guid` = '%s' ";
		$Where .= $extra;
		
		$query = new MMQueryBuilder();
		$query->Select("`character_homebind`")->Columns("*")->Where($Where, $args)->Build();
		$data = MMMySQLiFetch($this->db->query($query, $this->realmconf['CH_DB']));
		
		return $data;
	}
	
	function CheckRealmStatusAndOnlinePlayers()
	{
		$status = pfsockopen($this->realmconf['IP'], $this->realmconf['PORT'], $errno, $errstr, 5);
		$startimee = microtime(1);
		//Uptime Query
		$query = new MMQueryBuilder();
		$query->Select("`uptime`")->Columns("`starttime`")->Where("`realmid` = '%s'", $this->rid)
		->Order("`starttime` DESC")->Limit("1")->Build();
		$uptime = MMMySQLiFetch($this->db->query($query, DBNAME), "onerow: 1");
		
		//Max Online Players Query
		$query = new MMQueryBuilder();
		$query->Select("`uptime`")->Columns(array("MAX(`maxplayers`)"=>"maxplayers"))->Where("`realmid` = '%s'", $this->rid)->Build();
		$maxonline = MMMySQLiFetch($this->db->query($query, DBNAME), "onerow: 1");
		
		if($uptime['starttime'] == null)
		{
			$uptime['starttime'] = time();
		}
		if($maxonline['maxplayers'] == null)
		{
			$maxonline['maxplayers'] = 0;
		}
		
		//Uptime String
		$struptime = StrDateDiff(time(), $uptime['starttime']);
		
		//If server is offline no need to go furthur
		if($status == false)
		{
			return array(
				"status"	=> false,
				"online"	=> 0,
				"horde"		=> 0,
				"alliance"	=> 0,
				"uptime"	=> "Offline",
				"maxplayers"=> $maxonline['maxplayers']
			);
		}
		
		//Online Players Query
		$query = new MMQueryBuilder();
		$query->Select("`characters`")->Where("`online` <> 0")
		->Columns(array("COUNT(*)"=>"numrows", "(SELECT COUNT(*) FROM `characters` WHERE `online` <> 0 AND (`race`='1' OR `race`='3' OR `race`='4' OR `race`='7' OR `race`='11'))"=>"numalliance"))
		->Build();
		
		$result = MMMySQLiFetch($this->db->query($query, $this->realmconf['CH_DB']), "onerow: 1");
		
		return array(
			"status"	=> true,
			"online"	=> $result['numrows'],
			"horde"		=> (int)$result['numrows']-(int)$result['numalliance'],
			"alliance"	=> $result['numalliance'],
			"uptime"	=> $struptime,
			"maxplayers"=> $maxonline['maxplayers'],
		);
	}
	
	/**
	 * Executed a command on the server
	 * @param string $command
	 * 
	 * @return boolean
	 */
	public function ExecuteRemoteCommand($command)
	{
		global $REMOTE_TYPE;
		
		//SOAP
		if($REMOTE_TYPE == REMOTE_SOAP)
		{
			//Setup SOAP Client
			if(!$this->remoteconn)
			{
				$this->remoteconn = new SoapClient(NULL,
				array(
					"location" =>		"http://".$this->realmconf['IP'].":".$this->realmconf['SOAP']['port']."/",
					"uri"				=> "urn:TC",
					"style"				=> SOAP_RPC,
					"login"				=> strtoupper($this->realmconf['SOAP']['user']),
					"password"			=> $this->realmconf['SOAP']['pass'],
					"connection_timeout"=> 10,
				));
			}
			
			
			try //Try to execute function
			{
				$result = $this->remoteconn->executeCommand(new SoapParam($command, "command"));
			}
			catch(Exception $e) //Don't give fatal error if there is a problem
			{
				$this->_LogSoapError($e, $command);
				return array('sent'=>false, 'message'=>$e->getMessage());
			}
			return array('sent'=>true, 'message'=>$result);
		}
		
		//RA
		if($REMOTE_TYPE == REMOTE_RA)
		{
			if($this->remoteconn)
			{
				fclose($this->remoteconn);
			}
			
			$this->remoteconn = fsockopen($this->realmconf['IP'], $this->realmconf['SOAP']['port'], $errno, $errstr, 10);
			if(!$this->remoteconn)
			{
				fclose($this->remoteconn);
				return array('sent'=>false, 'message'=>$errstr);
			}
			
			// get the message of the day
			$motd = fgets($this->remoteconn);
			
			//Authorize
			fwrite($this->remoteconn, strtoupper($this->realmconf['SOAP']['user'])."\n");
			usleep(100);
			fwrite($this->remoteconn, $this->realmconf['SOAP']['pass']."\n");
			usleep(300);
			
			//Authorization results
			$authresult = trim(fgets($this->remoteconn));
			if(strpos($authresult, "failed") !== false)
			{
				fclose($this->remoteconn);
				return array('sent'=>false, 'message'=>"Authorization failed for user " . strtoupper($this->realmconf['SOAP']['user']));
			}
			
			//Send command
			fwrite($this->remoteconn, $command."\n");
			$result = fgets($this->remoteconn, 5000);
			fclose($this->remoteconn);
			
			//if command was incorrect
			if(stripos($result, "there is no such"))
			{
				return array('sent'=>false, 'message'=>$result);
			}
			//if $result is sent as false or empty or etc
			if(!$result)
			{
				return array('sent'=>false, 'message'=>'Result was false');
			}
			
			return array('sent'=>true, 'message'=>$result);
		}
		
		//Config error
		return array('sent'=>false, 'message'=>$result);
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
		global $USER, $cms;
		
		//If $votedonate is incorrect
		if($votedonate != REWARD_VOTE && $votedonate != REWARD_DONATE)
		{
			return array('message'=>"There was an internal error, please contact an administrator!", 'bool'=>false);
		}
		
		//Setting a log session
		$reward_delivery_table = $votedonate ? "log_donatereward_delivery" : "log_votereward_delivery"; //$votedonate
		$query = new MMQueryBuilder();
		$query->Select("`".$reward_delivery_table."`")->Columns(array("MAX(`session`)"=>"maxsession"))->Build();
		$session = MMMySQLiFetch($this->db->query($query, DBNAME), "onerow: 1");
		$session = $session['maxsession'];
		
		if(empty($session))
		{
			$session = 1;
		}
		else
		{
			$session = (int)$session + 1;
		}
		
		//Fetch Reward and Check for errors
		$rewards_table = $votedonate ? "rewards_donation" : "rewards_voting"; //$votedonate
		$query = new MMQueryBuilder();
		$query->Select("`".$rewards_table."`")->Columns("*")->Where("`id` = '%s' AND `realm` = '%s'", $rewardid, $this->rid)->Build();
		$reward = MMMySQLiFetch($this->db->query($query, DBNAME), "onerow: 1");
		
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
		$query = new MMQueryBuilder();
		$query->Select("`characters`")->Columns("`name`")->Where("`guid` = '%s' AND `account` = '%s'", $characterid, $USER['id'])->Build();
		$character = MMMySQLiFetch($this->db->query($query, $this->realmconf['CH_DB']), "onerow: 1");
		
		if(!count($character))
		{
			return array('message'=>"The character you selected does not exists on the selected realm.", 'bool'=>false);
		}
		
		//Success(If sending mail succeeds then we'll $success=true)
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
			$result = $this->ExecuteRemoteCommand($command);
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
			$this->_LogRewardDelivery($session, $command, $result['message'], $characterid, $rewardid, $logsent, $votedonate, $reward['points']);
		}
		
		//Try to send the gold now. $command = .send money #playername "#subject" "#text" #money
		if($reward['gold'] > 0)
		{
			$command  = ".send money ";
			$command .= $character['name']; //Character Name
			$command .= " \"Thank you!\""; //Subject
			$command .= " \"Dear ". FirstCharUpper($character['name']) .",\r\n\r\nThank you for supporting our server. We hope you enjoy your play!\r\n\r\nRegards,\r\n{$cms->config['websitename']} Staff\""; //Body
			$command .= " " . $reward['gold'];
			
			//Now connect to SOAP and send the gold
			$result = $this->ExecuteRemoteCommand($command);
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
			$this->_LogRewardDelivery($session, $command, $result['message'], $characterid, $rewardid, $logsent, $votedonate, $reward['points']);
		}
		
		//Deduct points if success = true
		if($success)
		{
			$pointcolumn = $votedonate ? "donationpoints" : "votepoints"; //$votedonate
			$query = new MMQueryBuilder();
			$query->Update("`account_mm_extend`")->Columns(array("`".$pointcolumn."`"=>"`".$pointcolumn."` - '%s'"), $reward['points'])->Where("`accountid` = '%s'", $USER['id'])->Build();
			$this->db->query($query, DBNAME);
			
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
	 * Logs soap error with variable $e class Exception into file soaperror.log in adminstration/logs
	 * @param Exception $e
	 */
	private function _LogSoapError($e, $command)
	{
		$date = date('D d/m/Y');
		$time = date('G:i:s');
		$ip = GetIp();
		$error = $e->getMessage();
		$errorcode = $e->getCode();
		$file = $e->getFile();
		$line = $e->getLine();
		
$errorstring = "\r\n
|----------------------------SOAP Command Error-----------------------------------
|Date: $date, Time: $time, From: $ip
|Where: $file(Line: $line) Error Code: $errorcode
|Command: $command
|Error: $error
|----------------------------SOAP Command Error-----------------------------------";
		
		$f = fopen(DOC_ROOT."/administration/logs/soaperror.log", "a+");
		fwrite($f, $errorstring);
		fclose($f);
	}
	
	private function _LogRewardDelivery($session, $command, $message, $cid, $rewardid, $sent, $votedonate, $cost)
	{
		//Table name
		switch($votedonate)
		{
			case REWARD_DONATE:
				$table = "`log_donatereward_delivery`";
				//Log donated for character in characters table
				if($sent)
				{
					$query = new MMQueryBuilder();
					$query->Replace("`character_mm_extend`")->Columns(array("`guid`"=>"'%s'", "`donated`"=>"'1'"), $cid)->Build();
					$this->db->query($query, $this->realmconf['CH_DB']);
				}
			break;
			case REWARD_VOTE:
				$table = "`log_votereward_delivery`";
			break;
			default:
				return;
			break;
		}
		$sent = $sent ? 1 : 0;
		
		//Insert into DB
		$query = new MMQueryBuilder();
		$query->Insert($table)->Columns(array(
			'`session`'		=> "'%s'",
			'`command`'		=> "'%s'",
			'`message`'		=> "'%s'",
			'`characterid`'	=> "'%s'",
			'`realmid`'		=> "'%s'",
			'`rewardid`'	=> "'%s'",
			'`sent`'		=> "'%s'",
			'`cost`'		=> "'%s'",
		), $session, $command, $message, $cid, $this->rid, $rewardid, $sent, $cost)->Build();
		
		return $this->db->query($query, DBNAME);
	}
	
}

?>
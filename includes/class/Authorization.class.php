<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

class Authorization
{
	private $db;
	
	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		global $DB;
		$this->db = $DB;
	}
	
	/**
	 * Sets User's Globals if Username and Password are correct
	 * 
	 */
	public function UserGlobals()
	{
		global $LOGON_CRAWLERUSERNAME, $LOGON_CRAWLERUSERPASS;
		$iscrawler = false;
		
		if(preg_match("/".CRAWLERS_LIST."/i", $_SERVER['HTTP_USER_AGENT'])) //If visitor is a crawler
		{
			$iscrawler = true;
		}
		if((!isset($_COOKIE['username']) || !isset($_COOKIE['password'])) && $iscrawler == false)
		{
			return false;
		}
		
		//Check if visitor is a crawler
		if($iscrawler) //If visitor is a crawler then login with crawler's user
		{
			$user = $this->FetchUserData($LOGON_CRAWLERUSERNAME, Sha1Pass($LOGON_CRAWLERUSERNAME, $LOGON_CRAWLERUSERPASS));
		}
		else
		{
			$user = $this->FetchUserData($_COOKIE['username'], $_COOKIE['password']);
		}
		if($user == false)
		{
			$this->Logout();
			return false;
		}
		else
		{
			return $user;
		}
	}
	
	/**
	 * Checks username and password if correct creates session
	 * and returns boolean
	 * 
	 * @param string $login Username
	 * @param string $password Password
	 * 
	 * @return boolean
	 */
	public function Login($login, $password, $lifetime)
	{
		global $cookies;
		$sha1pass = Sha1Pass($login, $password);
		$check = $this->FetchUserData($login, $sha1pass);
		if($check == false)
		{
			return false;
		}
		if($cookies->SetCookie("username", $login, $lifetime) && $cookies->SetCookie("password", $sha1pass, $lifetime))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * LogOut the current user by using COOKIE
	 * 
	 * @param mixed $uid
	 * @return void
	 */
	public function Logout($uid = null)
	{
		global $cookies;
		if($cookies->DeleteCookie("username") && $cookies->DeleteCookie("password"))
		{
			if($uid)
			{
				$query = new MMQueryBuilder();
				$query->Update("`online`")->Columns(array("`online`" => "'0'"))->Where("`uid` = '%s'", $uid)->Build();
				$this->db->query($query, DBNAME);
			}
			return true;
		}
		return false;
	}
	
	public function FetchOnlineUsers()
	{
		$q = $this->db->Select("*", "online", "WHERE online='1'");
		return $q;
	}
	
	/**
	 * Returns an array with ban details if banned or returns false
	 * 
	 * @param $uid
	 * @param $uip
	 * @return boolean
	 */
	public function BanCheck($uid = null, $uip = null)
	{
		$ipbanned = false;
		$userbanned = false;
		if($uid)
		{
			//Build up query
			$query = new MMQueryBuilder();
			$query->Select("`account_banned`")->Columns("*")
			->Where("`id` = '%s' AND `active` = '1' AND `unbandate` > '%s'", $uid, time())->Build();
			$result = $this->db->query($query, DBNAME);
			$userban = MMMySQLiFetch($result, "onerow: 1", "freeresult: 0");
			
			//If user banned
			if($result->num_rows)
			{
				$userbanned = true;
			}
			$result->close(); //Free up memory
			unset($result);
		}
		if($uip)
		{
			//Build up query
			$query = new MMQueryBuilder();
			$query->Select("`ip_banned`")->Columns("*")
			->Where("`ip` = '%s' AND `unbandate` > '%s'", $uip, time())->Build();
			$result = $this->db->query($query, DBNAME);
			$ipban = MMMySQLiFetch($result, "onerow: 1", "freeresult: 0");
						
			//If ip banned
			if($result->num_rows)
			{
				$ipbanned = true;
			}
			$result->close(); //Free up memory
			unset($result);
		}
		
		//IP Ban first so that if user is banned reason will be taken by userban
		if($ipbanned)
		{
			$reason = $ipban['banreason'];
			$banstart = $ipban['bandate'];
			$banend = $ipban['unbandate'];
			$banby = $ipban['bannedby'];
			
		}
		if($userbanned)
		{
			$reason = $userban['banreason'];
			$banstart = $userban['bandate'];
			$banend = $userban['unbandate'];
			$banby = $userban['bannedby'];
		}
		
		//If not banned
		if(!$ipbanned && !$userbanned)
		{
			return false;
		}
		else
		{
			if($ipbanned && $userbanned)
			{
				$type = BAN_BOTH;
			}
			else
			{
				if($ipbanned) $type = BAN_IP;
				if($userbanned) $type = BAN_ACCOUNT;
			}
			return array('reason'=>$reason, 'start'=>$banstart, 'end'=>$banend, 'type'=>$type, 'by'=>$banby);
		}
		return false;
	}
	
	/**
	 * Checks and return USER details in an array
	 * if the login and password are correct
	 * 
	 * @param string $login Username
	 * @param string $password Password
	 * 
	 * @return mixed
	 */
	private function FetchUserData($login, $password)
	{
		//SQL Query
		$query = new MMQueryBuilder();
		$query->Select("`account`")->Columns("*")
		->Join("`account_mm_extend`", "LEFT")->JoinOn("`account`.`id`", "`account_mm_extend`.`accountid`")
		->Where("`username` = '%s' AND `sha_pass_hash` = '%s'", $login, $password)->Build();
		$result = $this->db->query($query, DBNAME);
		$data = MMMySQLiFetch($result, "freeresult: 0", "onerow: 1");
		
		//If user not found
		if(!$result->num_rows)
		{
			return false;
		}
		$result->close(); //Free results
		unset($result);
		
		if($data['accountid'] == null || empty($data['accountid']))
		{
			$query = new MMQueryBuilder();
			$query->Insert("`account_mm_extend`")->Columns(array("`accountid`"=>"'%s'"), $data['id'])->Build();
			$this->db->query($query, DBNAME);
		}
		
		return $data;
	}
	
	
}
?>
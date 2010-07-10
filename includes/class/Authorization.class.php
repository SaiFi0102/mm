<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

class Authorization
{
	private $logdb;
	private $db;
	
	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		global $LOGONDB, $DB;
		$this->logdb = $LOGONDB;
		$this->db = $DB;
	}
	
	/**
	 * Sets User's Globals if Username and Password are correct
	 * 
	 */
	public function UserGlobals()
	{
		if(!isset($_COOKIE['username']) || !isset($_COOKIE['password']))
		{
			return false;
		}
		$user = $this->FetchUserData($_COOKIE['username'], $_COOKIE['password']);
		if ($user == false)
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
			if($uid) $this->db->Update(array("online" => '"0"'), "online", "WHERE uid='$uid'");
			return true;
		}
		else
		{
			return false;
		}
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
			$userban = $this->logdb->Select("*", "account_banned", "WHERE id = '%d' AND active = '1' AND unbandate > '%s'", true, $uid, time());
			if($userban) $userbanned = true;
		}
		if($uip)
		{
			$ipban = $this->logdb->Select("*", "ip_banned", "WHERE ip = '%s'  AND unbandate > '%s'", true, $uip, time());
			if($ipban) $ipbanned = true;
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
		$data = $this->logdb->Select("*", "account",
		"LEFT JOIN account_mm_extend ON account.id = account_mm_extend.accountid WHERE username='%s' AND sha_pass_hash='%s'"
		, true, $login, $password);
		
		if(!$data)
		{
			return false;
		}
		
		if($data['accountid'] == null || empty($data['accountid']))
		{
			$this->logdb->Insert(array("accountid"=>"'%s'"), "account_mm_extend", false, $data['id']);
		}
		return $data;
	}
	
	
}
?>
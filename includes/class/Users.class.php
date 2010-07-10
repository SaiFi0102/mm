<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

class Users
{
	/**
	 * Users Handler
	 * 
	 * @param string $eventname
	 * @return string
	 * 
	 */
	
	public $user = array();
	public $ban;
	public $banned;
	private $db;
	
	/**
	 * Constructor
	 *
	 * @param array $udata array data of table users
	 */
	function __construct($udata, &$uvar)
	{
		global $DB;
		$this->user = $udata;
		$this->db = $DB;
		
		$this->SetUserGlobals($uvar);
		$this->ClearOfflineUsers();
		$this->ClearExpiredVotes();
		$this->BanStatus();
		$this->LoginUpdate();
	}
	
	/**
	 * Sets USER Super Global Variables
	 *
	 * @param array $this->user
	 */
	public function SetUserGlobals(&$USER)
	{
		if(!$this->user)
		{
			$this->user['loggedin'] = false;
			$this->user['access'] = 0;
			$USER['loggedin'] = false;
			$USER['access'] = -1;
			return $USER;
		}
		$this->user['loggedin'] = true;
		$USER = $this->user;
		$USER['access'] = $USER['gmlevel'];
		unset($USER['gmlevel']);
		return $USER;
	}
	
	/**
	 * Clears online status from users that have closed
	 * thier browser or havent done anything on the website
	 * for 5 minutes
	 *
	 */
	public function ClearOfflineUsers()
	{
		$timeout = UserTimeout();
		$this->db->Update(array("online" => "'0'"), "online", "WHERE lastvisit < '$timeout' AND online='1'");
	}
	
	/**
	 * Clears Votes that have expired meaning that are more than
	 * 12hours old from DB
	 */
	function ClearExpiredVotes()
	{
		$time = (time()-12*60*60);
		$this->db->Delete("log_votes", "WHERE time < %s", $time);
	}
	
	/**
	 * Updates logged in status and logged in time to prevent
	 * getting offline
	 *
	 */
	public function LoginUpdate()
	{
		$time = time();
		$uid = $this->user['loggedin'] ? $this->user['id'] : 0;
		$this->db->Insert(array('uid'=>"'%d'", 'ip'=>"'%s'", 'lastvisit'=>"'%s'", 'online'=>"'1'"), "online", true, $uid, $_SERVER['REMOTE_ADDR'], $time);
	}
	
	public function BanStatus()
	{
		global $auth;
		$uid = false;
		if($this->user['loggedin'])
		{
			$uid = $this->user['id'];
		}
		$ban = $auth->BanCheck($uid, $_SERVER['REMOTE_ADDR']);
		if(!$ban)
		{
			$this->ban = false;
			$this->banned = false;
		}
		else
		{
			$this->ban = $ban;
			$this->banned = true;
		}
	}
}

?>
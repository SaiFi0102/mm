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
		if($GLOBALS['AJAX_PAGE'] != true)
		{
			$this->ClearOfflineUsers();
			$this->ClearExpiredVotes();
			$this->BanStatus();
			$this->LoginUpdate();
		}
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
	public function ClearOfflineUsers($offset = 0)
	{
		$timeout = UserTimeout($offset);
		$query = new MMQueryBuilder();
		$query->Update("`online`")->Columns(array("`online`" => "'0'"))->Where("`lastvisit` < '%s' AND `online` = '1'", $timeout)->Build();
		$this->db->query($query, DBNAME);
	}
	
	/**
	 * Clears Votes that have expired meaning that are more than
	 * 12hours old from DB
	 */
	function ClearExpiredVotes()
	{
		$time = (time()-12*60*60);
		$query = new MMQueryBuilder();
		$query->Delete("`log_votes`")->Where("`time` < '%s'", $time)->Build();
		$this->db->query($query, DBNAME);
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
		
		//Build Query
		$query = new MMQueryBuilder();
		$query->Replace("`online`")
		->Columns(array('`uid`'=>"'%s'", '`ip`'=>"'%s'", '`lastvisit`'=>"'%s'", '`online`'=>"'1'"), $uid, GetIp(), time())->Build();
		$result = $this->db->query($query, DBNAME);
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
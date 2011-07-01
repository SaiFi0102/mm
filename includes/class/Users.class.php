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
	public $firstvisit = false;
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
			$this->IsFirstVisit();
		}
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
		$USER['access'] =  0; //TODO: $USER['access'] = $USER['gmlevel'];
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
		
		//Build UPDATE Query for existing entry
		$query = new MMQueryBuilder();
		$query->Update("`online`")->Where("`uid` = '%s' AND `ip` = '%s'", $uid, GetIp())
		->Columns(array(
			'`lastvisit`'	=> "'%s'",
			'`online`'		=> "'1'",	
		), time());
		
		if($this->firstvisit) //If firstvisit
		{
			$query->AddColumns(array('`firstvisit`' => "CURRENT_TIMESTAMP"));
		}
		if($GLOBALS['AJAX_PAGE'] != true) //If it is not an ajax page, add extra columns
		{
			$query->AddColumns(array(
				'`request_uri`' => "'%s'",
				'`visits`' => "`visits` + 1",
				'`header_host`'			=> "'%s'",
				'`header_connection`'	=> "'%s'",
				'`header_user_agent`'	=> "'%s'",
				'`header_cache_control`'=> "'%s'",
				'`header_accept`'		=> "'%s'",
				'`header_accept_encoding`'=>"'%s'",
				'`header_accept_language`'=>"'%s'",
				'`header_accept_charset`'=> "'%s'",
			), $_SERVER['REQUEST_URI'], $_SERVER['HTTP_HOST'], $_SERVER['HTTP_CONNECTION'], $_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_CACHE_CONTROL'], $_SERVER['HTTP_ACCEPT'], $_SERVER['HTTP_ACCEPT_ENCODING'], $_SERVER['HTTP_ACCEPT_LANGUAGE'], $_SERVER['HTTP_ACCEPT_CHARSET']);
		}
		$query->Build();
		$result = $this->db->query($query, DBNAME);
		
		//Build INSERT Query for new entry
		if($this->db->affected_rows < 1 && $GLOBALS['AJAX_PAGE'] != true)
		{
			$query = new MMQueryBuilder();
			$query->Insert("`online`")
			->Columns(array(
				'`uid`'					=> "'%s'",
				'`ip`'					=> "'%s'",
				'`lastvisit`'			=> "'%s'",
				'`online`'				=> "'1'",
				'`visits`'				=> "'1'",
				'`request_uri`'			=> "'%s'",
				'`header_host`'			=> "'%s'",
				'`header_connection`'	=> "'%s'",
				'`header_user_agent`'	=> "'%s'",
				'`header_cache_control`'=> "'%s'",
				'`header_accept`'		=> "'%s'",
				'`header_accept_encoding`'=>"'%s'",
				'`header_accept_language`'=>"'%s'",
				'`header_accept_charset`'=> "'%s'",	
			),
			$uid, GetIp(), time(), $_SERVER['REQUEST_URI'], $_SERVER['HTTP_HOST'], $_SERVER['HTTP_CONNECTION'], $_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_CACHE_CONTROL'], $_SERVER['HTTP_ACCEPT'], $_SERVER['HTTP_ACCEPT_ENCODING'], $_SERVER['HTTP_ACCEPT_LANGUAGE'], $_SERVER['HTTP_ACCEPT_CHARSET']);
	
			if($this->firstvisit)
			{
				$query->AddColumns(array('`firstvisit`' => "CURRENT_TIMESTAMP"));
			}
			$query->Build();
			$result = $this->db->query($query, DBNAME);
		}
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
	
	public function IsFirstVisit()
	{
		global $USER;
		$query = new MMQueryBuilder();
		$query->Select("`online`")->Columns("visits")->Where("`ip` = '%s'", GetIp())->Build();
		$online = MMMySQLiFetch($this->db->query($query, DBNAME), "onerow: 1");
		
		if($online['visits'] == null)
		{
			$this->firstvisit = true;
		}
		if($online['visits'] == null)
		{
			$USER['visits'] = 1;
		}
		else
		{
			$USER['visits'] = (int)$online['visits'] + 1;
		}
	}
}

?>
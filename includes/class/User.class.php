<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: ../../index.php');
	exit();
}

/**
 * Holds User(Account) data and contains
 * methods to perform action on the user
 * 
 * @author Saif <saifi0102@gmail.com>
 *
 */
class User
{
	//Basics
	public $Id;
	public $Username;
	public $Email;
	public $SHA1Password;
	public $Expansion;
	
	//Personal
	public $CountryCode;
	public $SecretQuestion1;
	public $SecretQuestion2;
	public $SecretAnswer1;
	public $SecretAnswer2;
	public $Points;
	public $VotePoints;
	public $TimesPaid;
	public $TimesVoted;
	
	//Logs
	public $LastIpServer;
	public $LastLoginServer;
	public $FirstVisit;
	public $FailedLogins;
	public $JoinDate;
	public $ResetCode;
	public $MuteTime;
	
	//Online
	public $IsOnlineServer;
	public $IsOnlineWebsite;
	
	//Bools
	public $IsMuted;
	public $IsLocked;
	public $IsFirstVisit; //Boolean of weather this is the first visit or not
	
	//Linked objects
	public $Bans; //Bans object for this User
	public $Access; //UserAccess object for this User
	
	//Database raw data
	public $Data = array(); //Raw User Data from Database
	
	protected $DB; //$DB reference
	
	/**
	 * Constructor
	 *
	 */
	public function __construct($uid = "", $username = "", $email = "", $password = "", $sha1pass = "")
	{
		global $DB;
		$this->DB = &$DB;
		
		//Use SHA1 Password, if not given, convert and use raw password as SHA1 hash
		if(!empty($password) && empty($sha1pass))
		{
			$sha1pass = Sha1Pass($username, $password);
		}
		
		//Preloaded Variables
		$this->Id = $uid;
		$this->Username = $username;
		$this->Email = $email;
		$this->SHA1Password = $sha1pass;
	}
	
	/**
	 * Loads user data from MySQL with atleast one key user data
	 * and processes it
	 * 
	 * @return bool
	 */
	public function LoadUserDataFromDB($LoadBannedTable = true, $LoadAccessTable = true, $LoadOnlineTable = false)
	{
		//If all predefined data were empty
		if(empty($this->Id) && empty($this->Username) && empty($this->Email) && empty($this->SHA1Password))
		{
			throw new User_LoadException("None of the user key value were given, atleast one key is required", 1);
			return;
		}
		//If only using password
		if(empty($this->Id) && empty($this->Username) && empty($this->Email))
		{
			throw new User_LoadException("Its unsafe to use only password to get user", 2);
		}
		
		//Initial Query
		$query = new Query();
		$query->Select("`account`")->Group("`account`.`id`")->Limit("1")
		->Columns(array("`account`.*", "`account_mm_extend`.*"))
		->Join("`account_mm_extend`", "LEFT")->JoinOn("`account`.`id`", "`account_mm_extend`.`id`");
		
		//Account Ban records
		if($LoadBannedTable)
		{
			$query->AddJoin("`account_banned`", "LEFT")->AddJoinOn("`account`.`id`", "`account_banned`.`id`")
			->AddColumns(array("GROUP_CONCAT(DISTINCT CONCAT_WS('^,', `account_banned`.`bandate`, `account_banned`.`unbandate`, `account_banned`.`bannedby`, `account_banned`.`banreason`, `account_banned`.`active`) SEPARATOR ';')" => "`bansdata`"));
		}
		
		//Account Access records
		if($LoadAccessTable)
		{
			$query->AddJoin("`account_access`", "LEFT")->AddJoinOn("`account`.`id`", "`account_access`.`id`")
			->AddColumns(array("GROUP_CONCAT(DISTINCT CONCAT_WS(':', `account_access`.`RealmID`, `account_access`.`gmlevel`))" => "`access`"));
		}
		
		//Account Online records
		if($LoadOnlineTable)
		{
			$query->AddJoin("`online`", "LEFT")->AddJoinOn("`account`.`id`", "`online`.`uid`")
			->AddColumns(array("GROUP_CONCAT(DISTINCT CONCAT_WS('^,', `online`.`ip`, `online`.`lastvisit`, `online`.`firstvisit`, `online`.`ip`, `online`.`online`, `online`.`visits`, `online`.`request_uri`) SEPARATOR ';')" => "`onlinedata`"));
		}
		
		//Conditions
		if(!empty($this->Id))
		{
			$query->AddWhere("AND", "`account`.`id` = '%s'", $this->Id);
		}
		if(!empty($this->Username))
		{
			$query->AddWhere("AND", "`account`.`username` = '%s'", $this->Username);
		}
		if(!empty($this->Email))
		{
			$query->AddWhere("AND", "`account`.`email` = '%s'", $this->Email);
		}
		if(!empty($this->SHA1Password))
		{
			$query->AddWhere("AND", "`account`.`sha_pass_hash` = '%s'", $this->SHA1Password);
		}
		
		//Build and run query
		$query->Build();
		$result = $this->DB->query($query, DBNAME);
		
		//User not found
		if(!$result->num_rows)
		{
			$result->close(); //Free memory
			return false;
		}
		
		//Fetch data and define variables
		$data = MySQLiFetch($result, "onerow: 1");
		$this->SetUserData($data);
		
		//Set Bans object
		if($LoadBannedTable)
		{
			$this->SetBanData($this->Data['bansdata']);
		}
		
		//Sets Access object
		if($LoadAccessTable)
		{
			$this->SetAccessData($this->Data['access']);
		}
		
		/*//Sets OnlineRecords object
		if($LoadOnlineTable)
		{
			$this->SetOnlineData($this->Data['onlinedata']);
		}*/
		
		return true;
	}
	
	/**
	 * Loads User data with array argument
	 * @param array $data
	 * @return bool
	 */
	public function LoadUserDataFromArray(array $data)
	{
		//Load initial data
		$this->SetUserData($data);
		
		//Sets Bans object
		if($this->Data['bansdata'])
		{
			$this->SetBanData($this->Data['bansdata']);
		}
		
		//Sets Access object
		if($this->Data['access'])
		{
			$this->SetAccessData($this->Data['access']);
		}
		
		//Sets OnlineRecords object
		if($this->Data['onlinedata'])
		{
			$this->SetOnlineData($this->Data['onlinedata']);
		}
		
		return true;
	}
	
/**
	 * Sets user data
	 * @param array $data
	 */
	protected function SetUserData(array $data)
	{
		$this->Data = $data;
		
		//Basics
		$this->Id = $data['id'];
		$this->Username = $data['username'];
		$this->Email = $data['email'];
		$this->SHA1Password = $data['sha_pass_hash'];
		$this->Expansion = $data['expansion'];
		
		//Personal
		$this->CountryCode = $data['countrycode'];
		$this->SecretQuestion1 = (int)$data['secretquestion1'];
		$this->SecretQuestion2 = (int)$data['secretquestion2'];
		$this->SecretAnswer1 = $data['secretanswer1'];
		$this->SecretAnswer2 = $data['secretanswer1'];
		$this->Points = (int)$data['donationpoints'];
		$this->VotePoints = (int)$data['votepoints'];
		$this->TimesPaid = (int)$data['donated'];
		$this->TimesVoted = (int)$data['voted'];
		
		//Logs
		$this->JoinDate = $data['joindate'];
		$this->LastIpServer = $data['last_ip'];
		$this->LastLoginServer = $data['last_login'];
		$this->FailedLogins = (int)$data['failed_logins'];
		$this->ResetCode = $data['resetcode'];
		$this->MuteTime = (int)$data['mutetime'];
		
		//Online
		$this->IsOnlineServer = ((int)$data['online']) ? true : false;
		$this->IsLocked = ((int)$data['locked']) ? true : false;
		$this->IsMuted = ((int)$data['mutetime'] > time()) ? true : false;
	}
	
	/**
	 * Sets ban data
	 */
	protected function SetBanData($bansdata)
	{
		$this->Bans = new Bans($this, $bansdata);
	}
}

/**
 * Extension of User class for
 * User as the visitor on the webpage
 * 
 * @author Saif <saifi0102@gmail.com>
 *
 */
class UserSelf extends User
{
	protected $LoadOnlineTable = true;
	
	/**
	 * Extends and also loads User access data
	 * 
	 * @return bool
	 * @see User::LoadUserData()
	 */
	public function LoadUserData($LoadBannedTable = true, $LoadAccessTable = true, $LoadOnlineTable = true)
	{
		if(!parent::LoadUserData())
		{
			return false;
		}
		
		//Load user access data
		$query = new Query();
		$query->Select("`account_access`")->Columns(array("`gmlevel`", "`RealmID`"))->Where("`id` = '%s'", $this->Id)->Build();
		
		return true;
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
				$query = new Query();
				$query->Update("`online`")->Columns(array("`online`" => "'0'"))->Where("`uid` = '%s'", $uid)->Build();
				$this->db->query($query, DBNAME);
			}
			return true;
		}
		return false;
	}
}

class User_Exception extends Exception {}
class User_LoadException extends User_Exception {}
/**
	 * Sets User's Globals if Username and Password are correct
	 * 
	 
	public function UserGlobals()
	{
		global $LOGON_CRAWLERUSERNAME, $LOGON_CRAWLERUSERPASS;
		$iscrawler = false;
		
		if(preg_match('/'.CRAWLERS_LIST.'/i', $_SERVER['HTTP_USER_AGENT'])) //If visitor is a crawler
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
	}*/
?>
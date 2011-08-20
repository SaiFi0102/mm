<?php

/**
 * Holds list of bans and summarizes them of a User
 * @author Saif <saifi0102@gmail.com>
 *
 */
class Bans
{
	public $IsBanned = false; //Boolean weather User is banned or not
	public $WasBanned = false; //Boolean weather User was ever banned or not. If $IsBanned is true and if there is no previous ban entry, $WasBanned will be false 
	
	public $Bans = array(); //Array of Ban objects
	public $User; //User object reference
	
	/**
	 * Constructor
	 * @param User $User
	 * @param string|array $bansdata
	 */
	public function __construct(User &$User, $bansdata)
	{
		$this->User = &$User;
		
		//Load
		if(is_array($bansdata))
		{
			$this->LoadArrayBansData($bansdata);
		}
		else
		{
			$this->LoadStringBansData($bansdata);
		}
	}
	
	/**
	 * Handles bansdata string and converts it to
	 * separate Ban objects completes this object
	 * @param string $bansdata
	 */
	protected function LoadStringBansData($bansdata)
	{
		//Convert to string if needed
		if(!is_string($bansdata))
		{
			$bansdata = (string)$bansdata;
		}
		
		//If empty
		if(empty($bansdata))
		{
			return;
		}
		
		//Get array of bans
		$arraybans = explode(";", $bansdata);
		$this->LoadArrayBansData($arraybans);
	}
	
	/**
	 * Handles bansdata array and converts it to
	 * separate Ban objects completes this object
	 * @param array $bansdata
	 */
	protected function LoadArrayBansData(array $bansdata)
	{
		foreach($bansdata as $bandata)
		{
			$thisBan = new Ban($this, $this->User, $bandata);
			
			//If Ban is active
			if($thisBan->IsActive)
			{
				$this->IsBanned = true;
			}
			else
			{
				$this->WasBanned = true;
			}
			
			//array push
			$this->Bans[] = $thisBan;
			unset($thisBan);
		}
	}
}

/**
 * Holds a specific Ban's data
 * @author Saif <saifi0102@gmail.com>
 *
 */
class Ban
{
	public $IsActive = false; //Boolean of weather ban was removed or not
	public $IsPermanent = false; //Boolean of weather ban is parmanent or not
	protected $StartDate; //Unix timestamp of time of getting ban
	protected $EndDate; //Unix timestamp of time of getting unbanned
	protected $By; //User ID of User who banned
	protected $Reason; //Reason of ban
	
	public $Bans; //Bans object reference
	public $User; //User object reference
	
	/**
	 * Constructor
	 * @param Bans $Bans
	 * @param User $User
	 * @param string|array $bandata
	 */
	public function __construct(Bans &$Bans, User &$User, $bandata)
	{
		$this->Bans = &$Bans;
		$this->User = &$User;
		
		//Load
		if(is_array($bandata))
		{
			$this->LoadArrayBanData($bandata);
		}
		else
		{
			$this->LoadStringBanData($bandata);
		}
	}
	
	/**
	 * Ban start date in unix time
	 * @return int
	 */
	public function GetIntStartDate()
	{
		return $this->StartDate;
	}
	
	/**
	 * Ban start date in date() string format
	 * @param string $format See http://www.php.net/date
	 * @return string
	 */
	public function GetStringStartDate($format = "j F Y, g:i a")
	{
		return date($format, $this->StartDate);
	}
	
	/**
	 * Ban end date in unix time
	 * @return int
	 */
	public function GetIntEndDate()
	{
		return $this->EndDate;
	}
	
	/**
	 * Ban end date in date() string format
	 * @param string $format See http://www.php.net/date
	 * @return string
	 */
	public function GetStringEndDate($format = "j F Y, g:i a")
	{
		if($this->IsPermanent)
		{
			return "permanent";
		}
		return date($format, $this->EndDate);
	}
	
	/**
	 * Character name of who banned this User
	 * @return int
	 */
	public function GetBannedByCharacterName()
	{
		return $this->By;
	}
	
	/**
	 * Character object of who banned this User
	 * @return Character
	 * @todo Character object
	 */
	public function GetBannedByCharacterObject()
	{
		
	}
	
	/**
	 * Reason of being banned
	 * @return string
	 */
	public function GetReason()
	{
		if(empty($this->Reason))
		{
			return "reason not specified";
		}
		return $this->Reason;
	}
	
	/**
	 * Handles bandata string and converts it to
	 * Ban object to completes this object
	 * @param string $bandata
	 */
	protected function LoadStringBanData($bandata)
	{
		//Convert to string if needed
		if(!is_string($bandata))
		{
			$bandata = (string)$bandata;
		}
		
		//If empty
		if(empty($bandata))
		{
			return;
		}
		
		//Get array of ban data
		$arraybandata = explode("^,", $bandata);
		$this->LoadArrayBanData($arraybandata);
	}
	
	/**
	 * Handles bandata array and converts it to
	 * Ban object to completes this object
	 * @param array $bandata
	 */
	protected function LoadArrayBanData(array $bandata)
	{
		$this->StartDate = (int)$bandata[0]; //Unix time
		$this->EndDate = (int)$bandata[1]; //Unix time
		$this->BannedBy = $bandata[2]; //Character name
		$this->Reason = $bandata[3]; //String
		$this->IsActive = ((int)$bandata[4]) ? true : false; //1 = Active; 0 = Inactive
		$this->IsPermanent = ($this->EndDate < time()) ? true : false; //If EndDate is less then current time, ban is permanent
	}
}
?>
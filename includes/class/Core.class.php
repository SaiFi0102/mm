<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: ../../index.php');
	exit();
}

class Core
{
	//Config and Page specific
	public $config = array();
	public $page_access;
	public $banned_allowed = false;
	
	//Error Handler Variables
	public $error_list = array();
	public $error_heading = array();
	public $error_footer = array();
	public $error_stopped = array();
	
	//Rest are for cleaning GLOBAL variables and others
	public $input;
	private $get_magic_quotes;
	private $vars;
	private $db;
	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $DB;
		$this->page_access = ACCESS_ALL;
		$this->db = &$DB;
		$this->parseIncoming();
		$this->LoadConfigs();
	}
	
	/**
	 * Makes an array of all the configs with thier categories name in the MySQL database
	 *
	 * @return array
	 */
	public function LoadConfigs()
	{		
		//Fetch Tables
		$query = new Query();
		$query->Select("`configs`")->Columns("*")->Build();
		$result = $this->db->query($query, DBNAME);
		$configs = MySQLiFetch($result);
		
		//Config Varialbes are array with string only
		//Now to set the variable with its value
		foreach($configs as $config)
		{
			//String "true" or "false" to boolean true or false
			if($config['type'] == "boolean")
			{
				if(strpos($config['content'], "true") !== false || strpos($config['content'], "1") !== false || strpos($config['content'], "TRUE") !== false)
				{
					$config['content'] = true;
				}
				if(strpos($config['content'], "false") !== false || strpos($config['content'], "0") !== false || strpos($config['content'], "FALSE") !== false)
				{
					$config['content'] = false;
				}
			}
			//String "1" or any integer to integer 1 or the integer
			if($config['type'] == "integer")
			{
				$config['content'] = (int)$config['content'];
			}
			//String "0.00" to float 0.00 or any other float number
			if($config['type'] == "float")
			{
				$config['content'] = (float)$config['content'];
			}
			if($config['type'] == "double")
			{
				$config['content'] = (double)$config['content'];
			}
			if($config['type'] == "array")
			{
				$arrcontent = explode(",", $config['content']);
				$config['content'] = array();
				foreach($arrcontent as $arrc)
				{
					$arrc = explode("=", $arrc);
					if(count($arrc) == 1)
					{
						$config['content'][] = $arrc[0];
					}
					else
					{
						$config['content'][$arrc[0]] = $arrc[1];
					}
				}
			}
			
			$this->config[$config['name']] = $config['content'];
		}
		return $this->config;
	}
	
	/**
	 * Parse _GET _POST data
	 *
	 * Clean up and unHTML
	 *
	 * @return	void
	 */
	private function parseIncoming()
	{
		//-----------------------------------------
		// Attempt to switch off magic quotes
		//-----------------------------------------
		
		@set_magic_quotes_runtime(0);
		
		$this->get_magic_quotes = @get_magic_quotes_gpc();
		
		//-----------------------------------------
		// Clean globals, first.
		//-----------------------------------------
	
		$this->cleanGlobals($_GET);
		$this->cleanGlobals($_POST);
		$this->cleanGlobals($_COOKIE);
		$this->cleanGlobals($_REQUEST);
		
		# GET first
		$input = $this->parseIncomingRecursively($_GET, array());
		
		# Then overwrite with POST
		$input = $this->parseIncomingRecursively($_POST, $input);
		
		$this->input = $input;
		
		unset($input);
		
		# Assign request method
		$this->input['request_method'] = strtolower($this->myGetEnv('REQUEST_METHOD'));
	}
	
	/**
	 * Performs basic cleaning
	 * Null characters, etc
	 */
	private function cleanGlobals(&$data, $iteration = 0)
	{
		// Crafty hacker could send something like &foo[][][][][][]....to kill Apache process
		// We should never have a globals array deeper than 10..
		
		if($iteration >= 10)
		{
			return $data;
		}
		
		if(count($data))
		{
			foreach($data as $k => $v)
			{
				if (is_array($v))
				{
					$this->cleanGlobals($data[$k], $iteration++);
				}
				else
				{	
					# Null byte characters
					$v = preg_replace('/\\\0/'  , '', $v);
					$v = preg_replace('/\\x00/' , '', $v);
					$v = str_replace ('%00'     , '', $v);
					
					# File traversal
					$v = str_replace ('../'    , '&#46;&#46;/', $v);
					
					$data[$k] = $v;
				}
			}
		}
	}
	
	/**
	 * Recursively cleans keys and values and
	 * inserts them into the input array
	 */
	private function parseIncomingRecursively(&$data, $input=array(), $iteration = 0)
	{
		// Crafty hacker could send something like &foo[][][][][][]....to kill Apache process
		// We should never have an input array deeper than 10..
	
		if($iteration >= 10)
		{
			return $input;
		}
		
		if(count($data))
		{
			foreach($data as $k => $v)
			{
				if (is_array($v))
				{
					//$input = $this->parse_incoming_recursively( $data[ $k ], $input );
					$input[$k] = $this->parseIncomingRecursively( $data[ $k ], array(), $iteration++);
				}
				else
				{	
					$k = $this->parseCleanKey($k);
					$v = $this->parseCleanValue($v);
					
					$input[$k] = $v;
				}
			}
		}
		
		return $input;
	}
	
	/**
	 * Get an environment variable value
	 *
	 * Abstract layer allows us to user $_SERVER or getenv()
	 *
	 * @param	string	Env. Variable key
	 * @return	string
	 */
	
	private function myGetEnv($key)
	{
		$return = array();
		
		if (is_array($_SERVER) && count($_SERVER))
		{
			if(isset($_SERVER[$key]))
			{
				$return = $_SERVER[$key];
			}
		}
		
		if (!$return)
		{
			$return = getenv($key);
		}
		
		return $return;
	}
	
	/**
	 * Clean _GET _POST key
	 *
	 * @param	string	Key name
	 * @return	string	Cleaned key name
	 */
	private function parseCleanKey($key)
	{
		if ($key == "")
		{
			return "";
		}
		
		$key = htmlspecialchars(urldecode($key));
		$key = str_replace (".."                , ""   , $key);
		$key = preg_replace('/\_\_(.+?)\_\_/'   , ""   , $key);
		$key = preg_replace('/^([\w\.\-\_]+)$/' , "$1" , $key);
		
		return $key;
	}
	
	/**
	 * UnHTML and stripslashes _GET _POST value
	 *
	 * @param	string	Input
	 * @return	string	Cleaned Input
	 */
	private function parseCleanValue($val)
	{
		if ($val == "")
		{
			return "";
		}
		
		$val = str_replace("&#032;", " ", $this->txtStripslashes($val));
		
		if (isset($this->vars['strip_space_chr']) AND $this->vars['strip_space_chr'])
		{
			$val = str_replace(chr(0xCA), "", $val);  //Remove sneaky spaces
		}
		
		$val = str_replace ("&"				, "&amp;"         , $val);
		$val = str_replace ("<!--"			, "&#60;&#33;--"  , $val);
		$val = str_replace ("-->"			, "--&#62;"       , $val);
		$val = preg_replace( "/<script/i"	, "&#60;script"   , $val);
		$val = str_replace (">"				, "&gt;"          , $val);
		$val = str_replace ("<"				, "&lt;"          , $val);
		$val = str_replace ('"'				, "&quot;"        , $val);
		$val = str_replace ("\n"			, "<br />"        , $val); // Convert literal newlines
		$val = str_replace ("$"				, "&#036;"        , $val);
		$val = str_replace ("\r"			, ""              , $val); // Remove literal carriage returns
		$val = str_replace ("!"				, "&#33;"         , $val);
		$val = str_replace ("'"				, "&#39;"         , $val); // IMPORTANT: It helps to increase sql query safety.
		
		// Ensure unicode chars are OK
		$val = preg_replace('/&amp;#([0-9]+);/s', "&#\\1;", $val);
		
		//-----------------------------------------
		// Try and fix up HTML entities with missing ;
		//-----------------------------------------
		
		$val = preg_replace('/&#(\d+?)([^\d;])/i', "&#\\1;\\2", $val);
		
		return $val;
	}
	
	/**
	 * Remove slashes if magic_quotes enabled
	 *
	 * @param	string	Input String
	 * @return	string	Parsed string
	 */
	private function txtStripslashes($t)
	{
		if ($this->get_magic_quotes)
		{
			$t = stripslashes($t);
			$t = preg_replace('/\\\(?!&amp;#|\?#)/', "&#092;", $t);
		}
		
		return $t;
	}
	
	/**
	 * Sets page's access
	 *
	 * @param mixed $access
	 */
	public function SetPageAccess($access)
	{
		$this->page_access = $access;
		//CHECK
		if($this->CheckAccess())
		{
			return;
		}
		else
		{
			$problem = $this->AccessProblem();
			if($problem == "login")
			{
				return '$page_name[] = array("No Access");
				$page_name[] = array("Login"=>"login.php?ref=".urlencode(RemoveGetRefFromLogin($_SERVER[\'REQUEST_URI\'])));
				$REDIRECT_MESSAGE = "You have to be logged in to visit this page. You are being redirected to the login page..";
				$REDIRECT_LOCATION = "login.php?ref=".urlencode($_SERVER[\'REQUEST_URI\']);
				$REDIRECT_INTERVAL = 2000;
				$REDIRECT_TYPE = "notice";
				eval($templates->Redirect());
				exit();';
			}
			return '$page_name[] = array("No Access"); 
			eval($templates->Output("'.$problem.'")); 
			exit();';
		}
	}
	
	public function CheckAccess()
	{
		global $USER, $uclass, $UserSelf;
		if($uclass->banned && $this->banned_allowed == false)
		{
			return false;
		}
		if((int)$this->page_access == ACCESS_UNREGISTERED && (int)$USER['access'] > -1)
		{
			return false;
		}
		if((int)$USER['access'] < (int)$this->page_access)
		{
			return false;
		}
		return true;
	}
	
	/**
	 * Check the type of access problem and return template name to use
	 * @return string
	 */
	public function AccessProblem()
	{
		global $USER, $uclass;
		if($uclass->banned == true)
		{
			return "banned";
		}
		if($USER['loggedin'] == false && $this->page_access == ACCESS_REGISTERED)
		{
			return "login";
		}
		return "noaccess";
	}
	
	/**
	 * Gives access to banned user to the page if $bool = true
	 * @param bool $bool
	 */
	public function BannedAccess($bool)
	{
		if(is_bool($bool))
		{
			$this->banned_allowed = $bool;
		}
	}
	
	/**
	 * Populates the list of errors in a page
	 * @param mixed $input
	 * @param mixed $listno
	 */
	public function ErrorPopulate($input, $listno=1)
	{
		if(!isset($this->error_stopped[$listno]))
		{
			$this->error_stopped[$listno] = false;
		}
		if($this->error_stopped[$listno])
		{
			return;
		}
		
		if(is_array($input))
		{
			foreach($input as $errstr)
			{
				$this->error_list[$listno][] = $errstr;
			}
		}
		else
		{
			$this->error_list[$listno][] = $input;
		}
	}
	
	/**
	 * Sets the heading of the error list
	 * @param string $str
	 * @param mixed $listno
	 */
	public function ErrorSetHeading($str, $listno=1)
	{
		$this->error_heading[$listno] = $str;
	}
	
	/**
	 * Sets the footer of the error list
	 * @param string $str
	 * @param mixed $listno
	 */
	public function ErrorSetFooter($str, $listno=1)
	{
		$this->error_footer[$listno] = $str;
	}
	
	/**
	 * Stops adding more errors into list
	 * @param mixed $listno
	 */
	public function ErrorStopList($listno=1)
	{
		$this->error_stopped[$listno] = true;
	}
	
	public function ErrorExists($listno=1)
	{
		if(isset($this->error_list[$listno]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Returns error list with style, heading, list and footer
	 * @param mixed $listno
	 * @param string $styleheader
	 * @param string $stylefooter
	 */
	public function ErrorOutput($listno=1, $styleheader="<div width='100%' class='errorbox'><span>", $stylefooter="</span></div>")
	{
		if(!isset($this->error_list[$listno]) || count($this->error_list[$listno]) == 0)
		{
			return null;
		}
		$return = null;
		$return .= $styleheader;
		
		//Heading
		if(isset($this->error_heading[$listno]))
		{
			$return .= $this->error_heading[$listno];
		}
		else
		{
			$return .= "The following errors occured.";
		}
		//$return .= "<br />";
		
		//List
		$return .= "<ol>";
		foreach($this->error_list[$listno] as $li)
		{
			$return .= "<li>{$li}</li>";
		}
		$return .= "</ol>";
		
		//Footer
		if(isset($this->error_footer[$listno]))
		{
			$return .= $this->error_footer[$listno];
		}
		
		//Return with style :)
		$return .= $stylefooter;
		return $return;
	}
	
}

?>
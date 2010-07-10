<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

class MySQL
{
	/**
	* MySQL Connection
	*
	* @var resource
	*/
	private $con;

	/**
	* MySQL last insert ID
	*/
	public $InsertId;

	/**
	* MySQL affected rows
	*/
	public $AffectedRows;
	
	/**
	* Last MySQL Query
	* 
	* @var string
	*/
	public $LastQry;
	
	/**
	* Number of MySQL Queries
	* 
	* @var integer
	*/
	public $numQueries = 0;
	
	/**
	 * Array of Queries
	 * 
	 * @var array
	 */
	public $ArrQry = array();


	/**
	* Constructor
	*
	* @param string $host MySQL host (standard: localhost)
	* @param string $user MySQL user
	* @param string $pass MySQL password
	* @param string $db MySQL database
	* @param int $port MySQL port
	*/
	public function __construct($host, $user, $pass, $db, $port = 3306)
	{
		$this->con = new mysqli($host, $user, $pass, $db, $port);
		
		if(mysqli_connect_errno())
		{
			error_reporting(0);
			global $email;
			
			$date = date('D d/m/Y');
			$time = date('G:i:s');
			$ip = $_SERVER['REMOTE_ADDR'];
			$error = $this->con->connect_error;
			$errorcode = mysqli_connect_errno();
			$file = $_SERVER['PHP_SELF'];
			$request = $_SERVER['REQUEST_URI'];
			
$errorstring = "\r\n
|----------------------------Connection Error-----------------------------------
|Date: $date, Time: $time, From: $ip
|Where: $file($request) Error Code: $errorcode
|Error: $error
|----------------------------Connection Error-----------------------------------";
			
			$f = fopen(DOC_ROOT."/administration/logs/mysqlerror.log", "a+");
			fwrite($f, $errorstring);
			fclose($f);
			
			SendEmail($email['adminemail'], "There was an error connecting to database", $errorstring);
			
			if($GLOBALS['DEBUG'])
			{
				print "<pre>";
				print $errorstring;
				print "</pre>";
			}
			exit("<div align='center' width='100%'><h3>There was a technical problem with the website please try again later. Sorry for inconvenience.</h3></div>");
		}
	}
	
	/**
	* Destructor
	*/
	public function __destruct()
	{
		if($this->con)
		{
			$this->con->close();
		}
	}



	/*****************************
	* Functions to read out data *
	*****************************/



	/**
	* mysqli_query() but secure with sprintf
	*
	* @param string $query MySQL query to execute
	* @param string ... sprintf compatible arguments
	*
	* @return resource
	*
	* @example $db->Query("SELECT username FROM table WHERE uid = %u AND pass = '%s'", $_POST['uid'], $_POST['pass']);
	*/
	public function Query($query) {
		$args	= func_get_args();
		$vargs	= array();
		if(isset($args[1]) && is_array($args[1]))
		{
			for($i = 0; $i < count($args[1]); $i++)
			{
				//If even this is an array:
				if(is_array($args[1][$i]))
				{
					foreach($args[1][$i] as $argz)
					{
						$vargs[] = mysqli_real_escape_string( $this->con, htmlentities($argz) );
					}
				}
				$vargs[] = mysqli_real_escape_string( $this->con, htmlentities($args[1][$i]) );
			}
		}
		else
		{
			for($i = 1; $i < func_num_args(); $i++)
			{
				//If even this is an array:
				if(is_array($args[$i]))
				{
					foreach($args[$i] as $argz)
					{
						$vargs[] = mysqli_real_escape_string( $this->con, htmlentities($argz) );
					}
				}
				$vargs[] = mysqli_real_escape_string( $this->con, htmlentities($args[$i]) );
			}
		}
		$query	= vsprintf($query, $vargs);
		$res	= @mysqli_query($this->con, $query, MYSQLI_STORE_RESULT);
		if(!$res)
		{
			$this->error($query);
			return false;
		}


		$this->AffectedRows = mysqli_affected_rows($this->con);
		$this->LastQry = $query;
		$this->ArrQry[] = $query;
		$this->numQueries++;
		return $res;
	}

	/**
	* Selects data from a table
	*
	* @param mixed $columns Columns to select, more columns = array, one column = string
	* @param string $table Table
	* @param string $last Optional, End of the query (WHERE, ORDER BY, LIMIT, ...)
	* @param bool $onerow
	* @param string ... sprintf compatible arguments
	*
	* @example $db->Select(array("login", "gm"), "accounts", "WHERE gm = '%s' ORDER BY login", true, "a")
	* this would select the columns `login` and `gm` from the table `accounts` where gm is 'a' and it would be ordered by column `login`
	*
	* @return mixed
	*/
	public function Select($columns, $table, $last = null, $onerow = false) {
		$args	= func_get_args();
		array_shift($args); array_shift($args); array_shift($args); array_shift($args);

		$nargs	= array();
		foreach($args as $value) {
			if(is_array($value)) {
				foreach($value as $value2)
					$nargs[]	= $value2;
			} else {
				$nargs[]	= $value;
			}
		}
		$args	= $nargs;


		$onecolumn	= false;

		// Build query
		$query	= "SELECT ";

		if(is_array($columns)) {
			foreach($columns as $column)
				$query	.= $column . ", ";

			$query	= substr($query, 0, strlen($query) - 2);
		} else {
			$query	.= $columns;

			$onecolumn	= true;
		}


		$query	.= " FROM " . $table;

		if($last != null)
			$query	.= " " . $last;
		
		$qry	= $this->Query($query, $args);
		$numrows = $this->numRows($qry);

		// FETCH
		if(!$onerow)
		{
			$array = array();
			$i = 0;
			while($rs = $this->FetchArray($qry))
			{
				$array[$i] = $rs;
				$i++;
			}
		}
		else
		{
			$array = $this->FetchArray($qry);
		}
		return $array;
	}


	/**
	* Inserts data in a table
	*
	* @param array $data Array with the data (table => value)
	* @param string $table Table
	* @param string ... sprintf compatible arguments
	*
	* @example $db->Insert( array("login"=>"'%s'","gm"=>"'%s'"), "accounts", $_POST['login'], 'a' );
	* Inserts a row with `login`=$_POST['login'] and `gm`='a' to table `accounts`
	*
	* @return resource
	*/
	public function Insert($data, $table, $replace = false) {
		$args	= func_get_args();
		array_shift($args); array_shift($args); array_shift($args);

		$nargs	= array();
		foreach($args as $value) {
			if(is_array($value)) {
				foreach($value as $value2)
					$nargs[]	= $value2;
			} else {
				$nargs[]	= $value;
			}
		}
		$args	= $nargs;


		// Build query
		$query = "";
		$query = $replace ? "REPLACE" : "INSERT";
		$query	.= " INTO " . $table . " (";

		$columns	= "";
		$values		= "";

		foreach($data as $column => $value) {
			$columns	.= $column . ", ";
			$values		.= $value . ", ";
		}

		$columns	= substr($columns, 0, strlen($columns)-2);
		$values		= substr($values, 0, strlen($values)-2);

		$query .= $columns . ") VALUES(" . $values . ")";

		$qry	= $this->Query($query, $args);
		$this->InsertId	= $this->con->insert_id;

		return $qry;
	}


	/**
	* Updates data
	*
	* @param array $data Array with the new data (column => new value)
	* @param string $table Table to update
	* @param string $last End of the query (WHERE, ORDER BY, LIMIT, ...)
	* @param string ... sprintf compatible arguments
	*
	* @return resource
	*/
	public function Update($data, $table, $last = null) {
		$args	= func_get_args();
		array_shift($args); array_shift($args); array_shift($args);

		$nargs	= array();
		foreach($args as $value) {
			if(is_array($value)) {
				foreach($value as $value2)
					$nargs[]	= $value2;
			} else {
				$nargs[]	= $value;
			}
		}
		$args	= $nargs;

		// Build query
		$query	= "UPDATE " . $table . " SET ";

		foreach($data as $column => $value)
			$query	.= $column . " = " . $value . ", ";

		$query	= substr($query, 0, strlen($query)-2);

		if($last != null)
			$query	.= " " . $last;

		return $this->Query($query, $args);
	}

	/**
	* Delets rows in a table
	*
	* @param string $table Tables
	* @param string $last End of the query (WHERE, LIMIT, ...)
	* @param string ... sprintf compatible arguments
	*
	* @example $db->Delete("accounts", "WHERE login='%s'", $_POST['login']);
	* Deletes a row from the table `accounts` where `login` is $_POST['login']
	*
	* @return resource
	*/
	public function Delete($table, $last = null) {
		$args	= func_get_args();
		array_shift($args); array_shift($args);

		if(isset($args[0]) && is_array($args[0]))
			$args	= $args[0];


		// Build query
		$query	= "DELETE FROM " . $table;

		if($last != null)
			$query	.= " " . $last;

		return $this->Query($query, $args);
	}


	/**
	* mysqli_fetch_assoc() alias
	*
	* @param resource $query
	*
	* @return array
	*/
	public function FetchAssoc($query)
	{
		return mysqli_fetch_assoc($query);
	}

	/**
	* mysqli_fetch_array() alias
	*
	* @param resource $query
	*
	* @return array
	*/
	public function FetchArray($query, $type = MYSQLI_ASSOC)
	{
		return mysqli_fetch_array($query, $type);
	}
	
	/**
	* mysqli_fetch_row() alias
	*
	* @param resource $query
	*
	* @return array
	*/
	public function FetchRow($query)
	{
		return mysqli_fetch_row($query);
	}
	
	/**
	 * Fetch a result as an object.
	 *
	 * @param resource $result
	 * @return object
	 */
	
	public function FetchObject($query)
	{
		return mysqli_fetch_object($query);
	}
	
	/**
	 * Get number of rows found on the query
	 *
	 * @param resource $query
	 * @return int
	 */
	
	public function numRows($query = null)
	{
		if($query == null)
		{
			$query = self::LastQry;
		}
		return mysqli_num_rows($query);
	}
	
	/**
	 * Free result memory 
	 *
	 * @param resource $query
	 * @return unknown
	 */
	
	public function FreeResult($query = null)
	{
		if($query == null)
		{
			$query = self::LastQry;
		}
		return mysql_free_result($query);
	}
	
	/**
	 * Escapes the string, so it's ready for MySQL insertion.
	 *
	 * @param string $string
	 * @return string
	 */
	
	public function escape($string)
	{
		return mysqli_real_escape_string($this->con, $string);
	}
	
	public function Ping()
	{
		return mysqli_ping($this->con);
	}


	/**********************
	*   Other functions   *
	**********************/


	function error($query, $exit = true)
	{
		if($exit) error_reporting(0);
		
		global $email;
		
		$date = date('D d/m/Y');
		$time = date('G:i:s');
		$ip = $_SERVER['REMOTE_ADDR'];
		$error = $this->con->error;
		$errorcode = mysqli_errno();
		$file = $_SERVER['PHP_SELF'];
		$request = $_SERVER['REQUEST_URI'];
		
$errorstring = "\r\n
|------------------------------Query Error-------------------------------------
|Date: $date, Time: $time, From: $ip
|Where: $file($request) Error Code: $errorcode
|Query: $query
|Error: $error
|------------------------------Query Error-------------------------------------";
		
		$f = fopen(DOC_ROOT."/administration/logs/mysqlerror.log", "a+");
		fwrite($f, $errorstring);
		fclose($f);
		
		SendEmail($email['adminemail'], "There was a query error", $errorstring);
		
		if($exit)
		{
			if($GLOBALS['DEBUG'])
			{
				print "<pre>";
				print $errorstring;
				print "</pre>";
			}
			exit("<div align='center' width='100%'><h3>There was a technical problem with the website please try again later. Sorry for inconvenience.</h3></div>");
		}
	}
}
?>
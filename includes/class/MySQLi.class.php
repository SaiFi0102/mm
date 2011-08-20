<?php

//################ Redirect if not included ################
if(!defined("INCLUDED"))
{
	header('Location: ../../index.php');
	exit();
}

/**
 * Extension of PHP's SPL mysqli
 * Including Error reporting and logging,
 * profiling queries and using a safe way to execute queries etc.
 * 
 * @author Saif <saifi0102@gmail.com>
 * @uses mysqli
 *
 */
class MMySQLi extends mysqli
{
	public $LastQuery; //Last Query String
	public $CurrentDatabase; //Current database pointer string
	public $NumQueries = 0; //Total number of queries executed
	public $QueriesExecutionTime = 0.0; //Total time taken for query execution
	
	private $ArrQuery = array();
	
	/**
	 * 
	 * Connects to MySQL using MySQLi and checks if any error exists in connection
	 * @param string $host Prepend "p:" for persistent connections.
	 * @param string $user MySQL Username
	 * @param string $pass MySQL Password
	 * @param string $db MySQL Database
	 * @param mixed $port Default 3306
	 * 
	 * @see mysqli::__construct()
	 */
	public function __construct($host, $user, $pass, $db, $port = 3306)
	{
		//Connect
		$this->connect($host, $user, $pass, $db, $port);
		
		//Check if there was an error connecting
		if($this->connect_error)
		{
			error_reporting(0); //MySQL errors will be handled separately and will not be logged with other PHP errors
			
			//Variables for logging mysql error in file
			$time = date('D d/m/Y, g:i a');
			$ip = GetIp();
			$error = $this->connect_error;
			$request = $_SERVER['REQUEST_URI'];
			
$errorstring = "|----------------------------Connection Error-----------------------------------\r\n|Time: $time, From: $ip\r\n|Where: $request\r\n|Error: $error\r\n|----------------------------Connection Error-----------------------------------\r\n\r\n";
			
			$f = fopen(DOC_ROOT."/administration/logs/mysqlerror.log", "a+");
			fwrite($f, $errorstring);
			fclose($f);
			
			global $email;
			SendEmail($email['adminemail'], "There was an error connecting to database", $errorstring);
			
			if($GLOBALS['DEBUG'])
			{
				print "<pre>";
				print $errorstring;
				print "</pre>";
			}
			exit("<div align='center' width='100%'><h3>There is a technical problem with the website, please try again later or contact an administrator. Sorry for any inconvenience.</h3></div>");
		}
		
		$this->set_charset("utf8"); //Mangos is best with utf-8 and also SOAP only accepts utf8 strings
		$this->CurrentDatabase = $db;
	}
	
	/**
	 * Closes MySQL connection
	 */
	public function __destruct()
	{
		if(isset($this->connect_error) && !$this->connect_error)
		{
			$this->close();
		}
	}
	
	/**
	 * Performs a MySQL query with string escaped using printf method
	 * @param string $query Query String
	 * @param string $database Database Name
	 * @param mixed ...
	 * 
	 * @return mysqli_result
	 * @see mysqli::query()
	 */
	public function query($query, $database = null)
	{
		//Variables
		$qstarttime = microtime(1);
		$args = func_get_args(); array_shift($args); array_shift($args); //Get array of extra arguments only
		$vargs = array();
		$resultmode = MYSQLI_STORE_RESULT;
		
		//If object is provided in $query
		if(is_object($query))
		{
			$args = array_merge($query->FormatParamArray, $args);
			$query = $query->QueryString;
		}
		
		//Turn multi dimentional array to 1 single array
		foreach($args as $arg)
		{
			if(is_array($arg)) //If that argument is an array
			{
				foreach($arg as $ar)
				{
					$vargs[] = $this->real_escape_string($ar); //then all the keys are put in 1 array
				}
			}
			else //else just add that value in that "1" array
			{
				//This argument is the argument for resultmode so we wont add it in that "1" array instead edit resultmode
				if(stripos($arg, "resultmode: ") !== false)
				{
					$substrarg = substr($arg, 12);
					$resultmode = (int)$substrarg;
				}
				else
				{
					$vargs[] = $this->real_escape_string($arg);
				}
			}
		}
		
		//Build up query by replacing the arguments
		$query = vsprintf($query, $vargs);
		
		if($database != $this->CurrentDatabase && !empty($database))
		{
			if($this->select_db($database))
			{
				$this->CurrentDatabase = $database;
			}
		}
		$result = parent::query($query, $resultmode);
		if($result == false)
		{
			//If query failed return false and log the error
			$this->error($query);
			return false;
		}
		
		//...
		$executiontime = (microtime(1)-$qstarttime);
		$this->ArrQuery[] = array("string"=>$query, "executiontime"=> $executiontime);
		$this->LastQuery = $query;
		$this->NumQueries++;
		$this->QueriesExecutionTime += $executiontime;
		
		return $result;
	}
	
	/**
	 * 
	 * Produces, logs MySQL query error and exits php execution
	 * @param string $query
	 * @param bool $exit
	 */
	function error($query, $exit = true)
	{
		if($exit) error_reporting(0); //MySQL errors will be handled separately and will not be logged with other PHP errors
			
		//Variables for logging mysql error in file
		$time = date('D d/m/Y, g:i a');
		$ip = GetIp();
		$error = $this->error;
		$request = $_SERVER['REQUEST_URI'];
		
$errorstring = "|----------------------------Query Error-----------------------------------\r\n|Time: $time, From: $ip\r\n|Where: $request\r\n|Query: $query\r\n|Error: $error\r\n|----------------------------Query Error-----------------------------------\r\n\r\n";
		
		$f = fopen(DOC_ROOT."/administration/logs/mysqlerror.log", "a+");
		fwrite($f, $errorstring);
		fclose($f);
		
		global $email;
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

/**
 * Fetches data from mysqli_result in an array
 * @param mysqli_result $mysqli_result
 * 
 * @return array
 */
function MySQLiFetch($mysqli_result)
{
	//Arguments
	$args = func_get_args(); array_shift($args); //Custom Arguments
	//Defaults
	$onerow = 0;
	$startoffset = 0;
	$resulttype = MYSQLI_ASSOC;
	$freeresult = true;
	
	//Check if any arguments are supposed to change settings
	foreach($args as $arg)
	{
		if(($pos = stripos($arg, "resulttype: ")) !== false)
		{
			$substrarg = substr($arg, 12);
			$resulttype = (int)$substrarg;
		}
		if(($pos = stripos($arg, "startoffset: ")) !== false)
		{
			$substrarg = substr($arg, 13);
			$startoffset = (int)$substrarg;
		}
		if(($pos = stripos($arg, "onerow: ")) !== false)
		{
			$substrarg = substr($arg, 8);
			$onerow = (int)$substrarg;
		}
		if(($pos = stripos($arg, "freeresult: ")) !== false)
		{
			$substrarg = substr($arg, 12);
			$freeresult = (int)$substrarg;
		}
	}
	
	//If start offset is provided
	if($startoffset)
	{
		$mysqli_result->data_seek($startoffset);
	}
	
	//If more than 1 rows... return a multi-dementional array
	if(!$onerow)
	{
		$array = array();
		while($rs = $mysqli_result->fetch_array($resulttype))
		{
			$array[] = $rs;
		}
	}
	else
	{
		$array = $mysqli_result->fetch_array($resulttype);
	}
	
	//Free results?
	if($freeresult)
	{
		$mysqli_result->close();
	}
	
	return $array;
}

$totalbuildtime = 0.0;

/**
 * Builds MySQL Queries step by step
 * @author Saif <saifi0102@gmail.com>
 *
 */
class Query
{
	//Variables
	public $QueryType; //Query type like SELECT INSERT etc.
	public $MMQryType = MMQryType_Unset; //Query type in integer to determine what kind of instructions to follow in other functions
	public $Columns; //Columns are in array
	public $Table; //Table Name
	public $Where; //Where Clause
	public $Order; //Order Clause
	public $Group; //Group Clause
	public $Join; //Join Statement
	public $JoinOn; //Join Conditions
	public $Limit;
	
	private $ColumnsFormatParam = array();
	private $TableFormatParam = array();
	private $WhereFormatParam = array();
	private $OrderFormatParam = array();
	private $GroupFormatParam = array();
	private $JoinFormatParam = array();
	private $JoinOnFormatParam = array();
	private $LimitFormatParam = array();
	
	public $QueryString; //Built Query String
	public $FormatParamArray = array(); //Array of all format string parameters
	
	//Query Types
	
	/**
	 * SELECT Query type, used for start building a query
	 * @param string $tablename
	 * @return Query $this
	 */
	public function Select($tablename)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		
		$distinct = false;
		foreach($args as $arg)
		{
			if($arg == "DISTINCT")
			{
				$distinct = true;
				array_shift($args);
			}
		}
		
		$this->TableFormatParam = $args;
		
		$this->QueryType = "SELECT";
		if($distinct)
		{
			$this->QueryType .= " DISTINCT";
		}
		$this->MMQryType = MMQryType_Select;
		$this->Table = $tablename;
		return $this;
	}
	
	/**
	 * UPDATE Query type, used for start building a query
	 * @param string $tablename
	 * @return Query $this
	 */
	public function Update($tablename)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->TableFormatParam = $args;
		
		$this->QueryType = "UPDATE";
		$this->MMQryType = MMQryType_Update;
		$this->Table = $tablename;
		return $this;
	}
	
	/**
	 * INSERT Query type, used for start building a query
	 * @param string $tablename
	 * @return Query $this
	 */
	public function Insert($tablename)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->TableFormatParam = $args;
		
		$this->QueryType = "INSERT";
		$this->MMQryType = MMQryType_Insert;
		$this->Table = $tablename;
		return $this;
	}
	
	/**
	 * REPLACE Query type, used for start building a query
	 * @param string $tablename
	 * @return Query $this
	 */
	public function Replace($tablename)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->TableFormatParam = $args;
		
		$this->QueryType = "REPLACE";
		$this->MMQryType = MMQryType_Insert;
		$this->Table = $tablename;
		return $this;
	}
	
	/**
	 * DELETE Query type, used for start building a query
	 * @param string $tablename
	 * @return Query $this
	 */
	public function Delete($tablename)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->TableFormatParam = $args;
		
		$this->QueryType = "DELETE";
		$this->MMQryType = MMQryType_Delete;
		$this->Table = $tablename;
		return $this;
	}
	
	//Columns
	
	/**
	 * Adds initial columns
	 * @param string|array $columns
	 * @return Query this
	 */
	public function Columns($columns)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->ColumnsFormatParam = $args;
		
		$this->Columns = $columns;
		return $this;
	}
	
	/**
	 * Adds extra columns
	 * @param string|array $columns
	 * @return Query $this
	 */
	public function AddColumns($columns)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->ColumnsFormatParam = array_merge($this->ColumnsFormatParam, $args);
		
		if(empty($this->Columns))
		{
			settype($this->Columns, gettype($columns));
		}
		
		if(is_array($columns))
		{
			$this->Columns = array_merge($this->Columns, $columns);
		}
		else
		{
			$this->Columns .= ", " . $columns;
		}
		return $this;
	}
	
	//Join
	/**
	 * Adds initial join clause with table name and type. Query::JoinOn MUST also be called
	 * @param string $jointable
	 * @param string $jointype
	 * @return Query $this
	 */
	public function Join($jointable, $jointype = "INNER")
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args); array_shift($args);
		$this->JoinFormatParam = array();
		$this->JoinFormatParam[] = $args;
		
		$this->Join = array();
		$this->Join[] = array(
			'Type'	=> $jointype,
			'Table'	=> $jointable,
		);
		return $this;
	}
	
	/**
	 * Adds initial join on clause with a condition. Query::Join MUST also be called
	 * @param string $var1
	 * @param string $var2
	 * @return Query $this
	 * 
	 * @todo $var3, $var4, ...
	 */
	public function JoinOn($var1, $var2)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args); array_shift($args);
		$this->JoinOnFormatParam = array();
		$this->JoinOnFormatParam[] = $args;
		
		$this->JoinOn = array();
		$this->JoinOn[] = $var1 . " = " . $var2;
		return $this;
	}
	
	/**
	 * @see Query::Join
	 * @param string $jointable
	 * @param string $jointype
	 * @return Query $this
	 */
	public function AddJoin($jointable, $jointype = "INNER")
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args); array_shift($args);
		$this->JoinFormatParam[] = $args;
		
		$this->Join[] = array(
			'Type'	=> $jointype,
			'Table'	=> $jointable,
		);
		return $this;
	}
	
	/**
	 * @see Query::JoinOn
	 * @param string $var1
	 * @param string $var2
	 * @return Query $this
	 */
	public function AddJoinOn($var1, $var2)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args); array_shift($args);
		$this->JoinOnFormatParam[] = $args;
		
		$this->JoinOn[] = $var1 . " = " . $var2;
		return $this;
	}
	
	//Where, Order and Limit
	/**
	 * Adds initial where clause
	 * @param string $string
	 * @return Query $this
	 */
	public function Where($string)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->WhereFormatParam = $args;
		
		$this->Where = $string;
		return $this;
	}
	
	/**
	 * Adds where clause
	 * @param string $operator
	 * @param string $string
	 * @return Query $this
	 */
	public function AddWhere($operator, $string)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args); array_shift($args);
		$this->WhereFormatParam = array_merge($this->WhereFormatParam, $args);
		
		if(strlen($this->Where) > 0)
		{
			$this->Where .= " " . $operator;
		}
		$this->Where .= " " . $string;
		
		return $this;
	}
	
	/**
	 * Adds initial order clase
	 * @param string $string
	 * @return Query $this
	 */
	public function Order($string)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->OrderFormatParam = $args;
		
		$this->Order = $string;
		return $this;
	}
	
	/**
	 * Adds extra order clause
	 * @param string $string
	 * @return Query $this
	 */
	public function AddOrder($string)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->OrderFormatParam = array_merge($this->OrderFormatParam, $args);
		
		$this->Order .= ", " . $string;
		return $this;
	}
	
	/**
	 * Adds group clause
	 * @param string $string
	 * @return Query $this
	 */
	public function Group($string)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args);
		$this->GroupFormatParam = array_merge($this->GroupFormatParam, $args);
		
		$this->Group = $string;
		return $this;
	}
	
	/**
	 * Adds limit clause with start and repeat
	 * @param string $var1
	 * @param string $var2
	 * @return Query $this
	 */
	public function Limit($var1, $var2 = null)
	{
		//Merge FormatParamArray with this function's Format String Arguments
		$args = func_get_args(); array_shift($args); array_shift($args);
		$this->LimitFormatParam = $args;
		
		if($var2 == null)
		{
			$this->Limit = $var1;
		}
		else
		{
			$this->Limit = $var1 . ", " . $var2;
		}
		return $this;
	}
	
	//Build
	/**
	 * Builds the Query with data given
	 * 
	 * @return string Query String
	 */
	public function Build()
	{
		global $totalbuildtime;
		$bstarttime = microtime(1);
		//Query Type was not set
		if($this->MMQryType == MMQryType_Unset)
		{
			return false;
		}
		
		$query = $this->QueryType; //Declare Query String
		
		//Columns
		if($this->MMQryType == MMQryType_Select)
		{
			$columnstring = ""; //Declare Columns String
			if(is_array($this->Columns)) //If its an array then we'll get data from it
			{
				foreach($this->Columns as $key => $val)
				{
					if(is_int($key)) //If Key wasnt user provided
					{
						$columnstring .= $val . ", "; //Simple Column
					}
					else
					{
						$columnstring .= $key . " AS " . $val . ", "; //Aliased Column
					}
				}
				$columnstring = substr($columnstring, 0, -2); //Remove the trailing ", "
			}
			else //Or else just copy it
			{
				$columnstring = $this->Columns;
			}
			
			//Now we'll append the query string
			$this->FormatParamArray = array_merge($this->FormatParamArray, $this->ColumnsFormatParam);
			$query .= " " . $columnstring;
			unset($columnstring);
		}
		
		//FROM and INTO
		if($this->MMQryType == MMQryType_Select || $this->MMQryType == MMQryType_Delete)
		{
			$query .= " FROM";
		}
		if($this->MMQryType == MMQryType_Insert)
		{
			$query .= " INTO";
		}
		
		//Table Name
		$this->FormatParamArray = array_merge($this->FormatParamArray, $this->TableFormatParam);
		$query .= " " . $this->Table;
		
		//Update Columns
		if($this->MMQryType == MMQryType_Update)
		{
			$columnstring = "SET "; //Declare Columns String
			if(is_array($this->Columns)) //If its an array then we'll get data from it
			{
				foreach($this->Columns as $column => $val)
				{
					$columnstring .= $column . " = " . $val . ", "; //`column/key` = 'value'
				}
				$columnstring = substr($columnstring, 0, -2); //Remove the trailing ", "
			}
			else //Or else just copy it
			{
				$columnstring = $this->Columns;
			}
			
			//Now we'll append the query string
			$this->FormatParamArray = array_merge($this->FormatParamArray, $this->ColumnsFormatParam);
			$query .= " " . $columnstring;
			unset($columnstring);
		}
		
		//Insert Values
		if($this->MMQryType == MMQryType_Insert)
		{
			$columnstring = "";
			$valuesstring = "";
			$insertvalues = "";
			if(is_array($this->Columns)) //If its an array then we'll get data from it
			{
				if(IsAssocArray($this->Columns))
				{
					foreach($this->Columns as $column => $val)
					{
						$columnstring .= $column . ", ";
						$valuesstring .= $val . ", ";
					}
				}
				else
				{
					foreach($this->Columns as $val)
					{
						$valuesstring .= $val . ", ";
					}
				}
				
				$columnstring = substr($columnstring, 0, -2); //Remove the trailing ", "
				$valuesstring = substr($valuesstring, 0, -2); //Remove the trailing ", "
				
				if($columnstring)
				{
					$insertvalues .= "(" . $columnstring . ") ";
				}
				$insertvalues .= "VALUES (" . $valuesstring . ")";
				
				unset($columnstring); unset($valuesstring);
			}
			else //Or else just copy it
			{
				$insertvalues = " " . $this->Columns;
			}
			
			$this->FormatParamArray = array_merge($this->FormatParamArray, $this->ColumnsFormatParam);
			$query .= " " . $insertvalues;
			unset($insertvalues);
		}
		
		//Join
		if($this->MMQryType == MMQryType_Select)
		{
			if($this->Join)
			{
				foreach($this->JoinFormatParam as $JoinFormatParam)
				{
					$this->FormatParamArray = array_merge($this->FormatParamArray, $this->JoinFormatParam);
				}
				foreach($this->JoinOnFormatParam as $JoinOnFormatParam)
				{
					$this->FormatParamArray = array_merge($this->FormatParamArray, $this->JoinOnFormatParam);
				}
				foreach($this->Join as $key => $join)
				{
					$query .= " " . $join['Type'] . " JOIN " . $join['Table'] . " ON " . $this->JoinOn[$key];
				}
			}
		}
		
		//Where, Order and Limit
		if($this->Where)
		{
			$this->FormatParamArray = array_merge($this->FormatParamArray, $this->WhereFormatParam);
			$query .= " WHERE " . $this->Where;
		}
		if($this->Group)
		{
			$this->FormatParamArray = array_merge($this->FormatParamArray, $this->GroupFormatParam);
			$query .= " GROUP BY " . $this->Group;
		}
		if($this->Order)
		{
			$this->FormatParamArray = array_merge($this->FormatParamArray, $this->OrderFormatParam);
			$query .= " ORDER BY " . $this->Order;
		}
		if($this->Limit)
		{
			$this->FormatParamArray = array_merge($this->FormatParamArray, $this->LimitFormatParam);
			$query .= " LIMIT " . $this->Limit;
		}
		
		//Update QueryString
		$this->QueryString = $query;
		$totalbuildtime += (microtime(1)-$bstarttime);
		return $this->QueryString;
	}
}
?>
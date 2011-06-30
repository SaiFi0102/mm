<?php
//If not included from website
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

//Vote popup data
$SHOWVOTEPOPUP = false;
if(strpos($_SERVER['PHP_SELF'], "vote.php") === false && strpos($_SERVER['PHP_SELF'], "login.php") === false && strpos($_SERVER['PHP_SELF'], "register.php") === false && strpos($_SERVER['PHP_SELF'], "dominate.php") === false && strpos($_SERVER['PHP_SELF'], "logout.php") === false)
{
	$query = new MMQueryBuilder();
	$query->Select("`log_votes`")->Columns(array("COUNT(*)"=>"numrows"));
	if($USER['loggedin'])
	{
		$query->Where("`ip` = '%s' OR `accountid` = '%s'", GetIp(), $USER['id']);
	}
	else
	{
		$query->Where("`ip` = '%s'", GetIp());
	}
	$query->Build();
	$result = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");
	if((int)$result['numrows'] == 0)
	{
		$SHOWVOTEPOPUP = true;
	}
}

//Website online users' data
$website_onlines = array();
$query = new MMQueryBuilder();
$query->Select("`online`")->Columns(array("COUNT(*)"=>"numrows", "(SELECT COUNT(*) FROM `online` WHERE `online` <> '0' AND `uid` = '0')"=>"numrows_guests"))
->Where("`online` <> '0'")->Build();
$website_onlines = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");

//Random online's data
$rand_online = array();
foreach($REALM as $rid => $rdata)
{
	$rand_online[$rid] = RandomOnlinePlayers($rid);
}

//Slider
$slider_data = array();
$query = new MMQueryBuilder();
$query->Select("`catacylsm_slider`")->Columns("*")->Build();
$slider_data = MMMySQLiFetch($DB->query($query, DBNAME));

?>
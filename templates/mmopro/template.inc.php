<?php
//If not included from website
if(!defined("INCLUDED"))
{
	header('Location: index.php');
	exit();
}

//Sliding area data
$MMOPRO_SLIDER = array();
$MMOPRO_SLIDER = $DB->Select("*", "mmopro_slidingarea", "ORDER BY `order` ASC");

//Vote popup data
$SHOWVOTEPOPUP = false;
if(strpos($_SERVER['PHP_SELF'], "vote.php") === false && strpos($_SERVER['PHP_SELF'], "login.php") === false && strpos($_SERVER['PHP_SELF'], "register.php") === false && strpos($_SERVER['PHP_SELF'], "dominate.php") === false && strpos($_SERVER['PHP_SELF'], "logout.php") === false)
{
	if($USER['loggedin'])
	{
		$alreadyvoted = $DB->Select("gateway", "log_votes", "WHERE ip='%s' OR accountid='%s'", true, $_SERVER['REMOTE_ADDR'], $USER['id']);
	}
	else
	{
		$alreadyvoted = $DB->Select("gateway", "log_votes", "WHERE ip='%s'", true, $_SERVER['REMOTE_ADDR']);
	}
	if($DB->AffectedRows == 0)
	{
		$SHOWVOTEPOPUP = true;
	}
}

//Website online users' data
$website_onlines = $DB->Select("*, (SELECT username FROM {$LOGON_DATABASE['db']}.account WHERE account.id=online.uid) AS username", "online", "WHERE online <> 0");

//Random online's data
$rand_online = array();
foreach($REALM as $rid => $rdata)
{
	$rand_online[$rid] = RandomOnlinePlayers($rid);
}

?>
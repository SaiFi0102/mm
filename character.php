<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
$AJAX_PAGE = false;

//################ Required Files ################
require_once("init.php");

//################ PAGE ACCESS ################
$cms->BannedAccess(false);
eval($cms->SetPageAccess(ACCESS_REGISTERED));

//################ General Variables ################
$page_name[] = array("Characters"=>"characters.php");
WoW::getZonesArray();

//################ Page Functions ################
if(empty($_GET['cid']) || empty($_GET['rid']) || empty($REALM[$_GET['rid']]))
{
	//Redirect to characters list
	$REDIRECT_MESSAGE = "No Character Selected";
	$REDIRECT_LOCATION = "characters.php";
	$REDIRECT_INTERVAL = 0;
	$REDIRECT_TYPE = "notice";
	eval($templates->Redirect());
	exit();
}

$rclass = new Realm($_GET['rid']);
$_cdata = $rclass->FetchCharacterByCharacterID("", $_GET['cid']);

$page_name[] = array($_cdata['name']=>"character.php?rid={$_GET['rid']}&cid={$_GET['cid']}");
if($_cdata['account'] == $USER['id'])
{
	$CHARACTERLIST_SHOW_TOOLS = true;
}
else
{
	$CHARACTERLIST_SHOW_TOOLS = false;
}
$CHARACTERLIST_RID = $_GET['rid'];
$CHARACTERLIST_MUSTBEONLINE = false;
$CHARACTERLIST_MUSTBEOFFLINE = false;
$CHARACTERLIST_SELECTION = false;

if(isset($_GET['act']) && $_cdata['account'] == $USER['id'])
{
	if($_GET['act'] == "unstuck")
	{
		$page_name[] = array("Unstuck/Revive"=>$_SERVER['REQUEST_URI']);
		if(isset($_POST['submit']))
		{
			if($_cdata['online'] != 0) //Must be offline or else it can be used to cheat on pvps and etc
			{
				$cms->ErrorPopulate("You must be logged out to use this tool.");
			}
			if(!$cms->ErrorExists()) //If no errors
			{
				$location = null;
				if(WoW::$arrFactionId[$_cdata['race']] == 1) //Get faction id and check location accordingly
				{
					$location = $REALM[$_GET['rid']]['UNSTUCK']['alliance'];
				}
				if(WoW::$arrFactionId[$_cdata['race']] == 2)
				{
					$location = $REALM[$_GET['rid']]['UNSTUCK']['horde'];
				}
				$teleresult = $rclass->ExecuteSoapCommand("tele name {$_cdata['name']} {$location}"); //Teleport
				$ressresult = $rclass->ExecuteSoapCommand("revive {$_cdata['name']}"); //and then revive
				if(!$teleresult['sent'] && !$ressresult['sent']) //If both functions did not work, return an error
				{
					$cms->ErrorPopulate("There was a problem with the server, please try again later. If this problem persists, please contact an administrator!");
				}
				else //Output success page
				{
					eval($templates->Output("unstuck_success"));
					exit();
				}
			}
		}
	}
	if($_GET['act'] == "customize")
	{
		$page_name[] = array("Rename/Customize"=>$_SERVER['REQUEST_URI']);
		if(isset($_POST['submit']))
		{
			//Usermust have required vote points
			if((int)$USER['votepoints'] < $cms->config['cost_customizetool']) //Cost is $cms->config['cost_customizetooll'] points
			{
				$cms->ErrorPopulate("The cost of this tool is {$cms->config['cost_customizetooll']} Vote Points. You only have {$USER['votepoints']} Vote Points. <a href='vote.php'>Click here to vote for us</a>.");
			}
			//Check if user was ever banned
			$query = new MMQueryBuilder();
			$query->Select("`account_banned`")->Columns(array("COUNT(*)"=>"numrows"))->Where("`id` = '%s'", $USER['id'])->Build();
			$result = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");
			if((int)$result['numrows'] > 0)
			{
				$cms->ErrorPopulate("You are not allowed to use this tool.");
			}
			
			if(!$cms->ErrorExists()) //If no errors
			{
				//execute command first to check if ther are no errors
				$customresult = $rclass->ExecuteSoapCommand("character customize {$_cdata['name']}");
				$sent = $customresult['sent'] ? 1 : 0;
				
				if($customresult['sent'])
				{
					//Deduct points
					$query = new MMQueryBuilder();
					$query->Update("`account_mm_extend`")->Columns(array("`votepoints`"=>"`votepoints` - '%s'"), $cms->config['cost_customizetool'])->Where("`accountid` = '%s'", $USER['id'])->Build();
					$result = $DB->query($query, DBNAME);
					
					//Duduct Points from GLOBAL varaible to remove confusion because of positioning
					if($result)
					{
						$USER['votepoints'] -= $cms->config['cost_customizetool'];
					}
				}
				else //If there was an error do not duduct points
				{
					$cms->ErrorPopulate("There was a problem with the server, please try again later. If this problem persists, please contact an administrator!");
				}
				
				//Insert entry in log
				$query = new MMQueryBuilder();
				$query->Insert("`log_customizetool`")->Columns(array(
					"`account`"		=> "'%s'",
					"`character`"	=> "'%s'",
					"`oldname`"		=> "'%s'",
					"`command`"		=> "'%s'",
					"`message`"		=> "'%s'",
					"`sent`"		=> "'%s'",
				), $_cdata['account'], $_cdata['guid'], $_cdata['name'], "character customize {$_cdata['name']}", $customresult['message'], $sent)->Build();
				$DB->query($query, DBNAME);
				
				//If everything is ok
				if($customresult['sent'])
				{
					eval($templates->Output("customize_success"));
					exit();
				}
			}
		}
	}
}

eval($templates->Output('character'));
?>
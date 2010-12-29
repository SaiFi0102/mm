<?php
define("INCLUDED", true); //This is for returning a die message if INCLUDED is not defined on any of the template
set_include_path("../../");
$AJAX_PAGE = true;

//################ Required Files ################
require_once("init.php");

//################ Required Data ################
if(!isset($_POST['gameid']))
{
	exit("Error: Please report an administrator!");
}

//################ Ajax has ALL ACCESS ################
$cms->BannedAccess(true);
eval($cms->SetPageAccess(ACCESS_ALL));

//################ Page Functions ################
$uclass->LoginUpdate();
$uclass->ClearOfflineUsers(1);

$query = new MMQueryBuilder();
$query->Select("`quadropop_games`")->Columns(array("*"))
->Where("`id` = '%s'", $_POST['gameid'])->Build();
$game = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");

$players = array();
$pidarray = array();
$onlinesqueryin = "";
$playas = explode(";", $game['players']);
foreach($playas as $playa)
{
	$play = explode(",", $playa);
	$players[$play[0]] = array("color"=>$play[1], "icon"=>$play[2]);
	$pidarray[] = $play[0];
	$onlinesqueryin = $play[0] . ", ";
}

$onlinesqueryin = substr($onlinesqueryin, 0, -2);
if(empty($onlinesqueryin))
{
	$onlinesqueryin = "-1";
}

$query = new MMQueryBuilder();
$query->Select("`online`")->Columns(array("(SELECT `username` FROM `account` WHERE `id` = `online`.`uid`)"=>"username", "`uid`"))
->Where("`uid` IN (%s) AND `online` = '1'", $onlinesqueryin)->Build();
$onlineplayers = MMMySQLiFetch($DB->query($query, DBNAME));

$query = new MMQueryBuilder();
$query->Select("`account`")->Columns("`username`")->Where("`id` = '%s'", $pidarray[$game['turn']])->Build();
$turnusername = MMMySQLiFetch($DB->query($query, DBNAME), "onerow: 1");
$game['turnusername'] = $turnusername['username'];

//Data
$data = array();
foreach((explode(";", $game['data'])) as $deta)
{
	$dita[] = explode(",", $deta);
}
foreach($dita as $dota)
{
	$_x = $dota[0];
	$_y = $dota[1];
	$_u = $dota[2];
	$data["x".$_x."y".$_y] = $_u;
}
unset($deta);unset($dita);unset($dota);

if(isset($_POST['enter_x']))
{
	if($USER['id'] == $pidarray[$game['turn']] && $_POST['enter_x'] <= $game['length_x'])
	{
		for($y=1; $y<=$game['length_y']; $y++)
		{
			if(isset($data["x".$_POST['enter_x']."y".($y+1)]) || $y >= $game['length_y'])
			{
				$availible_y = $y;
				break;
			}
		}
		if(!isset($availible_y))
		{
			exit('error');
		}
		
		if(!empty($game['data']))
		{
			$newdata = $game['data'] . ";".$_POST['enter_x'].",".$availible_y.",".$USER['id'];
		}
		else
		{
			$newdata = $_POST['enter_x'].",".$availible_y.",".$USER['id'];
		}
		
		$totalplayers = count($pidarray) - 1;
		$nextturn = $game['turn'] + 1;
		if($nextturn > $totalplayers)
		{
			$nextturn = 0;
		}
		
		$query = new MMQueryBuilder();
		$query->Update("`quadropop_games`")->Columns(array("`data`"=>"'%s'", "turn"=>"'%s'"),$newdata, $nextturn)->Where("`id` = '%s'", $game['id'])->Build();
		$DB->query($query, DBNAME);
		print $DB->affected_rows;
	}
	exit();
}

//################ Template's Output ################
?>
<script type="text/javascript">
var myTurn = <?php if($pidarray[$game['turn']] == $USER['id']) print "true"; else print "false"; ?>;
var EnterX = function(x)
{
	if(myTurn)
	{
		$.ajax({
			url: "includes/ajax/quadropop_load.php",dataType: "html",data: {gameid: '<?php print $_POST['gameid']; ?>', enter_x: x},type: "POST",
			success: function(msg){
				LoadGame();
			},
			error: function(){
				alert("Error :)");
			}
		});
	}
};
</script>

<div class="right" style="border-left: 1px solid #cccccc; padding-left: 2px;">
	<h3>Onlines</h3>
	<div id="ONLINE_PLAYERS" align="center">
<?php
foreach($onlineplayers as $onlineplayer)
{
	print "<span style='font-weight: bold;color: " . $players[$onlineplayer['uid']]['color'] . ";'>" . $onlineplayer['username'] . " (" . $players[$onlineplayer['uid']]['icon'] . ")</span><br />";
}
?>
	</div>
</div>
<table width="80%">
	<tr>
		<th colspan="<?php print $game['length_x']; ?>" align="center">Gaem ON!</th>
	</tr>
	<?php
	for($y=1; $y<=$game['length_y']; $y++)
	{
		print "<tr id='Y_{$game['length_y']}'>";
		for($x=1; $x<=$game['length_x']; $x++)
		{
			$extra = null;
			$next_y = $y+1;
			if((isset($data["x".$x."y".$next_y]) || $y >= $game['length_y']) && !isset($data["x".$x."y".$y]))
			{
				$nomore = true;
				$extra = " class='AVAILIBLE' onclick=\"EnterX('$x');\"";
			}
			print "<td id='X_{$game['length_x']}'{$extra}>";
			
			if(isset($data["x".$x."y".$y]))
			{
				print "<span style='font-weight:bold;color:".$players[$data["x".$x."y".$y]]['color']."';>" . $players[$data["x".$x."y".$y]]['icon'] . "</span>";
			}
			print "</td>";
		}
		print "</tr>";
	}
	?>
</table>
OWAIS.. UPER THERE WILL BE LINES AND ROWS AND COLUMNS ... CLIKC ON THE LOWEST COLUMN FOR UR BARI!
<h1 align="center">It's <b><u><?php print $game['turnusername']; ?>'s</u></b> turn!</h1>

<script type="text/javascript">
function LoadGame()
{
	$.ajax({
		url: "includes/ajax/quadropop_load.php",dataType: "html",data: {gameid: '<?php print $_POST['gameid']; ?>'},type: "POST",
		success: function(msg){
			$("#CONTAINER").html(msg);
		},
		error: function(){
			$("#CONTAINER").html("Error");
		}
	});
}
if(!myTurn)
{
	setTimeout(LoadGame, 3000);
}
else
{
	$(".AVAILIBLE").mouseover(function(){$(this).css("background-color", "#eeeeee");}).mouseout(function(){$(this).css("background-color", "#ffffff");});
}
</script>
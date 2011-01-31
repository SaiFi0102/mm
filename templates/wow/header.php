<body>
<?php
if($SHOWVOTEPOPUP)eval($templates->Output("vote_popup", false, false, false, true));
?>
<!-- Logo -->
<div id="logo"></div>
<div id="topline"></div>
<div id="banner">
<div id="banner-top"></div>
<div id="banner-mid">
<div id="banner-right"></div>
<div id="accountbox">

<?php if($USER['loggedin']) { ?>
<h3>Hi <?php print FirstCharUpperThenLower($USER['username']); ?>,</h3><br />
<div id="content">
	You are ....,<br />
	...<br />
	.......<br />
	.....<br />
	.....................
</div>
<?php } else { ?>
<h3>Login</h3><br />
<div id="content">
<form action="login.php?ref=<?php print urlencode(RemoveGetRefFromLogin($_SERVER['REQUEST_URI'])); ?>" method="post">
<div class="greyinput" id="login_username_input"><input type="text" name="username" value="Username" /></div>
<div class="greyinput" id="login_password_input"><input type="password" name="password" value="password" /></div>
<div><input type="checkbox" name="remember" checked="checked" />
Remember Me?
<input type="submit" name="submit" value="Login" /></div>
<div><input type="button" name="register" value="Register" onclick="window.location='register.php';" /></div>
</form>
</div>
<?php } ?>

</div>
<span></span>
</div>
<div id="banner-btm"></div>
</div>
<div id="links">
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="/forums">Forums</a></li>
<li><a href="howtoconnect.php">Connection Guide</a></li>
<li><a href="account.php">Account</a></li>
<li><a href="vote.php">Vote</a></li>
<li><a href="points.php">Points</a></li>
<li><a href="pvp.php">PvP</a></li>
<li><a href="contact.php">Contact Us</a></li>
<li><a href="realms.php">Realms</a></li>
<?php if($USER['loggedin']) { ?>
<li><a href="logout.php" rel="nofollow">Logout</a></li>
<?php } else { ?>
<li><a href="login.php?ref=<?php print urlencode(RemoveGetRefFromLogin($_SERVER['REQUEST_URI'])); ?>">Login</a></li>
<li><a href="register.php">Register</a></li>
<?php } ?>
</ul>
</div>

<div id="maincontainer">
<div id="pagenav"><?php print $PAGETITLE; ?></div>
<?php if($OFFLINE_MAINTENANCE && $USER['access'] >= 4) {?>
<div class="errorbox" style="margin-bottom: 6px;"><h4>The website is currently under maintenace shutdown mode! Only Admins and Executives can view this website!</h4></div>
<?php }?>
<noscript>
<div class="errorbox" style="margin-bottom: 6px;"><h4>JavaScript support have been disabled or is not allowed in your browser. Please enable JavaScript for better experience or use a newer browser.</h4></div>
</noscript>

<?php if(!$OFFLINE_MAINTENANCE || $USER['access'] >= 4) { ?>
<div class="rightside">
<div class="main">
<div class="main_title">Realm Status</div>
<div class="content">

<table width="100%">
<?php foreach($REALM as $rid => $rdata)
{?>
	<tr>
		<th colspan="2"><?php print $rdata['NAME']; ?>'s Status</th>
	</tr>
	<tr>
		<td>State:</td>
		<td><div id="server_status_state_<?php print $rid; ?>"><img src='<?php print $cms->config['websiteurl']; ?>/images/wow/icons/load-small.gif' alt='Loading' height='16' width='16' /></div></td>
	</tr>
	<tr>
		<td>Online Players:</td>
		<td><div id="server_status_online_<?php print $rid; ?>"><img src='<?php print $cms->config['websiteurl']; ?>/images/wow/icons/load-small.gif' alt='Loading' height='16' width='16' /></div></td>
	</tr>
	<tr>
		<td>Uptime:</td>
		<td><div id="server_status_uptime_<?php print $rid; ?>"><img src='<?php print $cms->config['websiteurl']; ?>/images/wow/icons/load-small.gif' alt='Loading' height='16' width='16' /></div></td>
	</tr>
<script type="text/javascript">
function LoadStatus_<?php print $rid; ?>()
{
	$.ajax({
		url: "includes/ajax/server_status.php",dataType: "json",data: {rid: '<?php print $rid; ?>'},type: "POST",
		success: function(msg){
			$("#server_status_state_<?php print $rid; ?>").hide();$("#server_status_online_<?php print $rid; ?>").hide();$("#server_status_uptime_<?php print $rid; ?>").hide();
			if(msg['status']){
				$("#server_status_state_<?php print $rid; ?>").html("<img src='<?php print $cms->config['websiteurl']; ?>/images/icons/uparrow.gif' alt='Online' height='19' width='18' />");
			}
			else{
				$("#server_status_state_<?php print $rid; ?>").html("<img src='<?php print $cms->config['websiteurl']; ?>/images/icons/downarrow.gif' alt='Offline' height='19' width='18' />");
			}
			$("#server_status_state_<?php print $rid; ?>").fadeIn(750);$("#server_status_online_<?php print $rid; ?>").html("<a href='online.php?rid=<?php print $rid; ?>'>" + msg['online']+" (Maximum Online "+msg['maxplayers']+")</a>").fadeIn(750);$("#server_status_uptime_<?php print $rid; ?>").html(msg['uptime']).fadeIn(750);
		},
		error: function(){$("#server_status_state_<?php print $rid; ?>, #server_status_online_<?php print $rid; ?>, #server_status_uptime_<?php print $rid; ?>").html("Error Loading");}
	});
	setTimeout("LoadStatus_<?php print $rid; ?>()", 120000);
}
$(document).ready(function(){
	LoadStatus_<?php print $rid; ?>();
});
</script>
<?php } ?>
	<tr>
		<th colspan="2" align="center">set realmlist <?php print $LOGON_REALMLIST; ?></th>
	</tr>
</table>

</div>
</div>
</div>
<?php } ?>

<div class="leftside">
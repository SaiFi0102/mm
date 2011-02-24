<body>
<?php
if($SHOWVOTEPOPUP)eval($templates->Output("vote_popup", false, false, false, true));
?>
<div id="full">
<!-- Logo -->
<div id="logo"></div>
<div id="topline"></div>
<div id="banner">
<div id="banner-top"></div>
<div id="banner-mid">
<div id="accountbox">

<?php if($USER['loggedin']) { ?>
<h3>Hi <?php print FirstCharUpperThenLower($USER['username']); ?>,</h3><br />
<div id="content">
	<?php if($USER['last_login'] == "0000-00-00 00:00:00") print "You've never logged in before in game.<br />Please check the <a href='howtoconnect.php'>Connection Guide here</a> to help you start!";
	else print "Your account was most recently logged in from game on,<br />" . ConvertMysqlTimestamp($USER['last_login']) . " from the IP, " . $USER['last_ip']; ?>.<br />
	Below are some useful links you might find useful,<br /><br />
	<a href="account.php">Account Manager</a>, <a href="characters.php">Character List</a>,<br />
	<a href="logout.php" rel="nofollow">Logout</a>.
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

<?php
if(!isset($NOFLOATING) && !$NOFLOATING) {
if(!$OFFLINE_MAINTENANCE || $USER['access'] >= 4) { ?>
<div class="rightside">
<div class="main">
<div class="main_title">Realm Status</div>
<div class="content" id="status_table">

<table width="100%" class="serverstatus">
<?php foreach($REALM as $rid => $rdata)
{?>
<tr>
<th style="color:<?php print $rdata['COLOR']; ?>;">
	<?php print $rdata['NAME']; ?>
	<a href="online.php?rid=<?php print $rid; ?>" id="status_state_<?php print $rid; ?>">
		<img src='<?php print $cms->config['websiteurl']; ?>/images/wow/icons/load-small.gif' alt='Loading...' height='16' width='16' id="status_loadingicon_<?php print $rid; ?>" />
		<img src='<?php print $cms->config['websiteurl']; ?>/images/icons/uparrow.gif' alt='Server is up!' height='18' width='19' id="status_uparrow_<?php print $rid; ?>" style="display:none;" />
		<img src='<?php print $cms->config['websiteurl']; ?>/images/icons/downarrow.gif' alt='Server is down but it will be back up soon :)' height='18' width='19' id="status_downarrow_<?php print $rid; ?>" style="display:none;" />
	</a>
</th>
</tr>
<tr>
<td>
	<a href="online.php?rid=<?php print $rid; ?>" id="status_loader_<?php print $rid; ?>">
		<img src='<?php print $cms->config['websiteurl']; ?>/images/wow/icons/load-small.gif' alt='Loading...' height='16' width='16' />
	</a>
	<div id="status_content_<?php print $rid; ?>" style="display:none;">
		<b><span id="status_online_<?php print $rid; ?>"></span></b> Online Players(Maximum Online: <b><span id="status_maxonline_<?php print $rid; ?>"></span></b>).<br />
		<span style="color:#6666FF;"><b><span id="status_alliance_<?php print $rid; ?>"></span></b> Alliance</span> &amp; <span style="color:#FF6666;"><b><span id="status_horde_<?php print $rid; ?>"></span></b> Horde</span>.<br />
		Up for <span id="status_uptime_<?php print $rid; ?>"></span>.
	</div>
</td>
</tr>
<?php
}?>
<tr>
	<th colspan="2" align="center">set realmlist <?php print $LOGON_REALMLIST; ?></th>
</tr>
</table>

</div>
</div>
</div>
<div class="leftside">
<?php } } else { ?>
<div>
<?php } ?>
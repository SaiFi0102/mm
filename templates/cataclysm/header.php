<body>
<?php
if($SHOWVOTEPOPUP && !$uclass->firstvisit && $USER['visits'] >= 3)
{
	eval($templates->Output("vote_popup", false, false, false, true));
}
?>
<div id="full">
<div id="maincontainer">
<!-- Logo -->
<div id="header">
<div class="right">
<?php if($USER['loggedin']) { ?>
<h2>Hi <?php print FirstCharUpperThenLower($USER['username']); ?>,</h3><br />
<?php if($USER['last_login'] == "0000-00-00 00:00:00") print "You've never logged in before in game.<br />Please check the <a href='howtoconnect.php'>Connection Guide here</a> to help you start!";
else print "Your account was most recently logged in from game on,<br />" . ConvertMysqlTimestamp($USER['last_login']) . " from the IP, " . $USER['last_ip']; ?>.<br />
<?php } else { ?>
<form action="login.php?ref=<?php print urlencode(RemoveGetRefFromLogin($_SERVER['REQUEST_URI'])); ?>" method="post">
<div>
<span class="greyinput" id="login_username_input"><input type="text" name="username" value="Username" /></span><input type="submit" name="submit" value="Login" />
</div>
<div>
<span class="greyinput" id="login_password_input"><input type="password" name="password" value="password" /></span><input type="button" name="register" value="Register" onclick="window.location='register.php';" />
</div>
<div>
<input type="checkbox" name="remember" checked="checked" /> Keep me logged in?
</div>
</form>
<?php } ?>
</div>
<div class="left"><a href="index.php" title="Home"><img src="images/cataclysm/logo/logo.png" alt="" width="365" height="60" /></a></div>
<div class="clear"></div>
</div>

<div id="rt-body-surround">
<div class="rt-box-top"><div class="rt-box-top2"></div><div class="rt-box-top3"></div></div>

<div class="rt-box-bg">
<div class="rt-box-bg2">
<div class="rt-box-bg3">
<div id="rt-navigation">
<ul class="menutop">
<li class="root"><a href="index.php" class="orphan item"><span>Home</span></a></li>
<li class="root"><a href="./forums/" class="orphan item"><span>Forums</span></a></li>
<li class="root"><a href="howtoconnect.php" class="orphan item"><span>Connection Guide</span></a></li>
<li class="root"><a href="account.php" class="orphan item"><span>Account</span></a></li>
<li class="root"><a href="vote.php" class="orphan item"><span>Vote</span></a></li>
<li class="root"><a href="points.php" class="orphan item"><span>Points</span></a></li>
<li class="root"><a href="pvp.php" class="orphan item"><span>PvP</span></a></li>
<li class="root"><a href="contact.php" class="orphan item"><span>Contact</span></a></li>
<li class="root"><a href="realms.php" class="orphan item"><span>Realms</span></a></li>
<li class="root"><a href="characters.php" class="orphan item"><span>Characters/Tools</span></a></li>

<?php if($USER['loggedin']) { ?>
<li class="root"><a href="logout.php" class="orphan item"><span>Logout</span></a></li>
<?php } else { ?>
<li class="root"><a href="login.php" class="orphan item"><span>Login</span></a></li>
<li class="root"><a href="register.php" class="orphan item"><span>Register</span></a></li>
<?php } ?>
</ul>
</div>

<div class="rt-shadowbar"></div>

<div id="rt-showcase" class="showcase-overlay-dark">
<div id="rokstories" class="rokstories-layout">
<div id="slider">
<?php 
foreach($slider_data as $slide_data)
{
if($slide_data['link']) print '<a href="' . $slide_data['link'] . '">';
print '<img src="' . $slide_data['image'] . '" width="910" height="219"';
if($slide_data['caption']) print ' title="#caption' . $slide_data['id'] . '"';
if($slide_data['id'] > 1) print ' style="display:none;"'; else print ' alt=""';
print ' />';
if($slide_data['link']) print '</a>';
}?>
</div>
<?php
foreach($slider_data as $slide_data)
{
	if($slide_data['caption']) print '<div id="caption' . $slide_data['id'] . '" style="display:none;" class="nivo-html-caption">' . $slide_data['caption'] . '</div>';
}
?>
</div>
<div class="clear"></div>
<div id="pagenav"><?php print $PAGETITLE; ?></div>
</div>

<div class="rt-shadowbar"></div>

<?php if($OFFLINE_MAINTENANCE && $USER['access'] >= 4) {?>
<div class="errorbox" style="margin-bottom: 6px;"><span><h4>The website is currently under maintenace shutdown mode! Only Admins and Executives can view this website!</h4></span></div>
<?php }?>
<noscript>
<div class="errorbox" style="margin-bottom: 6px;"><span><h4>JavaScript support have been disabled or is not allowed in your browser. Please enable JavaScript for better experience or use a newer browser.</h4></span></div>
</noscript>

<div id="mainbox">
<?php
if(!isset($NOFLOATING) && !$NOFLOATING) {
if(!$OFFLINE_MAINTENANCE || $USER['access'] >= 4) { ?>
<div class="rightside">
<div class="wrapper">
<div class="main">
<div class="main_title">Realm Status</div>
<div class="content" id="status_table">
<table width="100%" class="serverstatus">
<tr><th>Total</th></tr>
<tr>
<td>
<img src='<?php print $cms->config['websiteurl']; ?>/images/cataclysm/spinner.gif' alt='Loading...' height='16' width='16' id="status_loader_total" />
<div id="status_content_total" style="display:none;">
<b><span id="status_online_total"></span></b> Online Players.<br />
<div id="onlinebar">
<div id="horde">
<div class="obar_logo"></div>
<div class="obar" style="width:94px;" id="status_horde_total"></div>
</div>
<div id="ally">
<div class="obar_logo">
<div class="obar" style="width:95px;" id="status_alliance_total"></div>
</div>
</div>
</div>
</div>
</td>
</tr>
<?php foreach($REALM as $rid => $rdata)
{?>
<tr>
<th style="color:<?php print $rdata['COLOR']; ?>;">
<?php print $rdata['NAME']; ?>
<a href="online.php?rid=<?php print $rid; ?>" id="status_state_<?php print $rid; ?>">
<img src='<?php print $cms->config['websiteurl']; ?>/images/cataclysm/spinner.gif' alt='Loading...' height='16' width='16' id="status_loadingicon_<?php print $rid; ?>" />
<img src='<?php print $cms->config['websiteurl']; ?>/images/cataclysm/typography/approved-icon.png' alt='Server is up!' height='14' width='14' id="status_uparrow_<?php print $rid; ?>" style="display:none;" />
<img src='<?php print $cms->config['websiteurl']; ?>/images/cataclysm/typography/alert-icon.png' alt='Server is down but it will be back up soon :)' height='14' width='14' id="status_downarrow_<?php print $rid; ?>" style="display:none;" />
</a>
</th>
</tr>
<tr>
<td>
<a href="online.php?rid=<?php print $rid; ?>" id="status_loader_<?php print $rid; ?>">
<img src='<?php print $cms->config['websiteurl']; ?>/images/cataclysm/spinner.gif' alt='Loading...' height='16' width='16' />
</a>
<div id="status_content_<?php print $rid; ?>" style="display:none;">
<b><span id="status_online_<?php print $rid; ?>"></span></b> Online Players(Maximum Online: <b><span id="status_maxonline_<?php print $rid; ?>"></span></b>).<br />
<div id="onlinebar">
<div id="horde">
<div class="obar_logo"></div>
<div class="obar" style="width:94px;" id="status_horde_<?php print $rid; ?>"></div>
</div>
<div id="ally">
<div class="obar_logo">
<div class="obar" style="width:95px;" id="status_alliance_<?php print $rid; ?>"></div>
</div>
</div>
</div>
<br />Up for <span id="status_uptime_<?php print $rid; ?>"></span>.
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

<div class="main">
<div class="main_title">Find us on Facebook</div>
<div class="content">
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/WoWMortal/194706350573458" width="274" colorscheme="dark" show_faces="true" stream="true" header="false"></fb:like-box>
</div>
</div>

</div>
<div class="left"><div class="bottom-left"></div></div><div class="bottom"></div>
</div>
<div class="leftside">
<?php } } else { ?>
<div>
<?php } ?>
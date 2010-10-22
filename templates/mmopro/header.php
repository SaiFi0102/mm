<body>
<?php
if($SHOWVOTEPOPUP)
{
	eval($templates->Output("vote_popup", false, false, false, true));
}
?>
<!-- ----------- PoPuP END ----------- -->
<div id="con0" align="center">
<div id="con1">

<div id="header">
	<!-- Logo -->
	<div id="logo">
		<a href="index.php">
			<img src="images/mmopro/logo.png" alt="<?php print $TITLE; ?>" class="png" height="88" width="300" />
		</a>
	</div>

	<!-- Header area -->
	<div id="header_area">
		<div id="login_form">
			<?php
			if($USER['loggedin']){?>
		<div id="loggedin_form_container">
			<div class="right">
				<a href="logout.php" title="Click to Logout and Delete All Cookies">[Logout]</a>
			</div>
			Welcome <?php print FirstCharUpperThenLower($USER['username']); ?>.
			<?php if($USER['last_login'] != '0000-00-00 00:00:00') print "You last logged in WoW on " . ConvertMysqlTimestamp($USER['last_login']); else print "Your account has not yet been logged in at WoW"; ?>.
			<?php }else{ ?>
		<div id="form_container">
			<form action="login.php?ref=<?php print urlencode(RemoveGetRefFromLogin($_SERVER['REQUEST_URI'])); ?>" method="post">
			<b>Username:</b> <input type="text" name="username" style="width: 86px;" /> 
			<b>Password:</b> <input type="password" name="password" style="width: 86px;" />
			<input type="checkbox" name="remember" checked="checked" class="autologin" />Remember Me
			<input type="submit" name="submit" value="Login" />
			<input type="button" value="Register" onclick="window.location='register.php';" />
			</form><?php }?>
		</div>
		</div>
	</div>

	<!-- Navigation -->
	<div class="clear"></div>
	<div id="navbar">
		<div id="nav_right"></div>
		<div id="nav_left"></div>
			<div class="link"><a href="index.php">Home</a></div><div class="separator"></div>
			<div class="link"><a href="/forums/">Forums</a></div><div class="separator"></div>
			<div class="link"><a href="account.php">Account Manager</a></div><div class="separator"></div>
			<div class="link"><a href="characters.php">Characters</a></div><div class="separator"></div>
			<div class="link"><a href="vote.php">Vote</a></div><div class="separator"></div>
			<div class="link"><a href="points.php">Points</a></div><div class="separator"></div>
			<div class="link"><a href="pvp.php">PvP</a></div><div class="separator"></div>
			<div class="link"><a href="contact.php">Contact Us</a></div><div class="separator"></div>
			<div class="link"><a href="realms.php">Realms</a></div><div class="separator"></div>
			<div class="link"><a href="howtoconnect.php">Connection Guide</a></div><div class="separator"></div>
			<?php if(!$USER['loggedin']){ ?>
			<div class="link"><a href="login.php">Login</a></div><div class="separator"></div>
			<div class="link"><a href="register.php">Register</a></div>
			<?php }else{ ?>
			<div class="link"><a href="logout.php">Logout</a></div>
			<?php } ?>
	</div>
</div><div class="clear"></div>

<!-- Slider Area -->
<div id="slider_top">
	<img src="images/mmopro/pixel.gif" height="1" width="1" alt="" />
</div>
<div id="slider_in">
	<div id="slider_area">
		<?php foreach($MMOPRO_SLIDER as $sliding_data) { ?>
		<div class="slide">
			<div class="sliding_text_left">
				<?php print $sliding_data['text_left']; ?>
			</div>
			<div class="sliding_text_right">
				<?php print $sliding_data['text_right']; ?>
			</div>
		</div>
		<?php } ?>
	</div>
	<div id="numbers"></div>
</div>
<script type="text/javascript">$('#slider_area').cycle({speed: 2000,fx:'scrollHorz',easing:'easeOutElastic',timeout:11100,pager:'#numbers',pagerAnchorBuilder:function(a){return'<li><a href="#" title="">'+(a+1)+'</a></li>'}});</script>

<!-- A simple separator, do not remove it or the slideshow and news will blend! -->
<div class="box_in"></div>

<?php if($OFFLINE_MAINTENANCE && $USER['access'] >= 4) {?>
<div class="errorbox" style="margin-bottom: 6px;"><h4>The website is currently under maintenace shutdown mode! Only Admins and Executives can view this website!</h4></div>
<?php }?>
<noscript>
<div class="errorbox" style="margin-bottom: 6px;"><h4>JavaScript support have been disabled or is not allowed in your browser. Please enable JavaScript for better output or use a newer browser.</h4></div>
</noscript>

<div class="footer_top"><img src="images/mmopro/pixel.gif" height="1" width="1" alt="" /></div>
<div class="footer_content">
		<div id="footer_cont" align="center" style="padding-top:0; padding-bottom: 0;">
			<h4><?php print $PAGETITLE; ?></h4>
		</div>
</div>
 <div class="footer_bottom"> </div>

<div id="page_left">
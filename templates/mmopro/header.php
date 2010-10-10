<body>
<?php
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
		eval($templates->Output("vote_popup", false, false, false, true));
	}
}
?>
<!-- ----------- PoPuP END ----------- -->
<div id="con0" align="center">
<div id="con1">

<div id="header">
	<!-- Logo -->
	<div id="logo">
		<a href="index.php">
			<img src="images/mmopro/logo.png" alt="<?php print $TITLE; ?>" class="png" />
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
			<div class="link"><a href="donate.php">Donate</a></div><div class="separator"></div>
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
	<img src="images/mmopro/pixel.gif" alt="" />
</div>
<div id="slider_in">
	<div id="slider_area">
		<!-- Slide  1-->
		<div class="slide">
			<!-- Sliding text line -->
			<div class="sliding_text_left">
				<div  class="sliding_text_text">
					<!-- Title -->
					<h1>Title</h1>
					<!-- Title 2-->
					<h2>Slider Sub Text 1</h2>
					<!-- Text-->
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sit amet libero in nunc lobortis accumsan. Sed vehicula ornare quam sit amet tristique. 
					Nunc scelerisque, enim vitae cursus scelerisque, neque purus luctus sapien, <a href=""  title="">sit amet iaculis odio massa ac dui</a>. 
				</div>			
			</div>
			<div class="sliding_text_right">
				<!-- Image -->
				<div class="featured_file">
					<img src="images/mmopro/slide_1.png" alt="" class="png" />
				</div>
			</div>
		</div>

		<!-- Slide  2-->
		<div class="slide">
			<!-- Sliding text line -->
			<div class="sliding_text_left">
				<div  class="sliding_text_text">
					<!-- Title -->
					<h1>Title 2</h1>
					<!-- Title 2-->
					<h2>Slider Sub Text 2</h2>
					<!-- Text-->
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sit amet libero in nunc lobortis accumsan. Sed vehicula ornare quam sit amet tristique. 
					Nunc scelerisque, enim vitae cursus scelerisque, neque purus luctus sapien, <a href=""  title="">sit amet iaculis odio massa ac dui</a>. 
				</div>			
			</div>
			<div class="sliding_text_right">
				<!-- Image -->
				<div class="featured_file">
					<img src="images/mmopro/slide_1.png" alt="" class="png" />
				</div>
			</div>
		</div>

		<!-- Slide  3-->
		<div class="slide">
			<!-- Sliding text line -->
			<div class="sliding_text_left">
				<div  class="sliding_text_text">
					<!-- Title -->
					<h1>Title 3</h1>
					<!-- Title 2-->
					<h2>Slider Sub Text 3</h2>
					<!-- Text-->
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sit amet libero in nunc lobortis accumsan. Sed vehicula ornare quam sit amet tristique. 
					Nunc scelerisque, enim vitae cursus scelerisque, neque purus luctus sapien, <a href=""  title="">sit amet iaculis odio massa ac dui</a>. 
				</div>			
			</div>
			<div class="sliding_text_right">
				<!-- Image -->
				<div class="featured_file">
					<img src="images/mmopro/slide_1.png" alt="" class="png" />
				</div>
			</div>
		</div>
		
		<!-- Slide  4-->
		<div class="slide">
			<!-- Sliding text line -->
			<div class="sliding_text_left">
				<div  class="sliding_text_text">
					<!-- Title -->
					<h1>Title 4</h1>
					<!-- Title 2-->
					<h2>Slider Sub Text 4</h2>
					<!-- Text-->
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sit amet libero in nunc lobortis accumsan. Sed vehicula ornare quam sit amet tristique. 
					Nunc scelerisque, enim vitae cursus scelerisque, neque purus luctus sapien, <a href=""  title="">sit amet iaculis odio massa ac dui</a>. 
				</div>			
			</div>
			<div class="sliding_text_right">
				<!-- Image -->
				<div class="featured_file">
					<img src="images/mmopro/slide_1.png" alt="" class="png" />
				</div>
			</div>
		</div>			
	</div>
	<div id="numbers"></div>
</div>
<script type="text/javascript">$('#slider_area').cycle({speed: 2000,fx:'scrollHorz',easing:'easeOutElastic',timeout:11100,pager:'#numbers',pagerAnchorBuilder:function(a){return'<li><a href="#" title="">'+(a+1)+'</a></li>'}});</script>

<!-- A simple separator, do not remove it or the slideshow and news will blend! -->
<div class="box_in"></div>

<?php if($OFFLINE_MAINTENANCE && $USER['access'] >= 4) {?>
<div class="errorbox" style="margin-bottom: 6px;"><h4>The website is currently under maintenace shutdown mode! Only Admins and Executives can view this website!</h4></div>
<?php }?>

<div class="footer_top"><img src="images/mmopro/pixel.gif" alt="" /></div>
<div class="footer_content">
		<div id="footer_cont" align="center" style="padding-top:0; padding-bottom: 0;">
			<h4><?php print $PAGETITLE; ?></h4>
		</div>
</div>
 <div class="footer_bottom"> </div>

<div id="page_left">
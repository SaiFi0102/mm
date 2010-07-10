<body>
	<a name="top"></a>
	
	<!-- ----------- PoPuP START ----------- -->
	<?php
	if(strpos($_SERVER['PHP_SELF'], "vote.php") === false && strpos($_SERVER['PHP_SELF'], "login.php") === false && strpos($_SERVER['PHP_SELF'], "register.php") === false && strpos($_SERVER['PHP_SELF'], "donate.php") === false && strpos($_SERVER['PHP_SELF'], "logout.php") === false)
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
	
	<!-- ----------- Header START ----------- -->
	<div class="header" align="center"><div class="logo"></div></div>
	<!-- ----------- Header END ----------- -->
	
	<!-- ----------- Top Part START ----------- -->
	<div class="top">
		<div class="main_title" align="center" style="font-size: 16px;"><?php print $PAGETITLE; ?></div>
		<div class="content">
			<ul id='links'>
				
				<li><a href="index.php">Home Page</a></li>
				<li><a href="/forums/">Forums</a></li>
				<li><a href="howtoconnect.php">Connection Guide</a></li>
				<li><a href="realms.php">Realm Info</a></li>
				
				<!-- User control links -->
				<li>
				<a href="javascript:void(0);">Account</a>
					<?php if($USER['loggedin']) { ?><ul>
						<li><a href="account.php">Account Manager</a></li>
						<li><a href="characters.php">Your Characters</a></li>
						<li>
							<a href="javascript:void(0);">Character Management</a>
							<ul>
								<li><a href="characters.php?act=unstuck">Character Unstucker</a></li>
							</ul>
						</li>
					</ul><?php } else { ?>
					<ul>
						<li><a href="login.php?ref=<?php print urlencode(RemoveGetRefFromLogin($_SERVER['REQUEST_URI'])); ?>">Login First</a></li>
						<li><a href="register.php">Or Register</a></li>
					</ul><?php } ?>
				</li>
				
				<li><a href="vote.php">Vote</a>
					<ul>
						<li><a href="vote.php">Voting Overview</a></li>
						<li><a href="vote.php?act=spend">Claim Voting Rewards</a></li>
					</ul>
				</li>
				
				<li><a href="donate.php">Donate</a>
					<ul>
						<li><a href="donate.php">Donation Overview</a></li>
						<li><a href="donate.php?act=spend">Claim Donation Rewards</a></li>
					</ul>
				</li>
				
				<!-- Server Links -->
				<li>
				<a href="javascript:void(0);">Realms</a>
					<ul>
						<?php foreach($REALM as $rid => $r) { ?>
						<li><a href="javascript:void(0);"><?php print $r['NAME']; ?></a>
							<ul>
								<li><a href="online.php?rid=<?php print $rid; ?>">Online Players</a></li>
								<li><a href="pvp.php?rid=<?php print $rid; ?>">PvP Ranking</a></li>
								<li><a href="players.php?rid=<?php print $rid; ?>">Player Status</a></li>
							</ul>
						</li>
						<?php } ?>
					</ul>
				</li>
				
				<!-- Login/Register/Logout -->
				<?php if(!$USER['loggedin']) { ?>
				<li><a href="login.php?ref=<?php print urlencode(RemoveGetRefFromLogin($_SERVER['REQUEST_URI'])); ?>">Login</a></li>
				<li><a href="register.php">Register</a></li>
				<?php } else { ?>
				<li><a href="logout.php">Logout</a></li>
				<?php } ?>
			</ul>
			<script type="text/javascript">$('#links').droppy();</script>
			
			<hr />
			<div>
				<?php $latest_news = $DB->Select("*", "news", "ORDER BY date DESC LIMIT 1", true); ?>
				Latest News: <?php print "<a href='index.php?id={$latest_news['id']}'>{$latest_news['title']}</a> by {$latest_news['by']} at ".ConvertMysqlTimestamp($latest_news['date']); ?>
			</div>
		</div>
	</div>
	<!-- ----------- Top Part END ----------- -->
	
	<!-- ----------- Right Side START ----------- -->
	<div class="rightside">
	
		<div class="main_right">
			<div class="main_title">Welcome</div>
			<div class="content" align="center">
				<?php
				if($USER['loggedin'])
				{?>
					<h3>Welcome back, <?php print FirstCharUpper($USER['username']); ?>!</h3><br />
					
					<?php if($USER['last_login'] != '0000-00-00 00:00:00') print "Your last login from game was at " . ConvertMysqlTimestamp($USER['last_login']) . " with IP " . $USER['last_ip']; else print "Never logged in from game"; ?><br /><br />
					
					<a href="account.php">Account Management</a><br />
					<a href="characters.php">Characters and Tools</a><br />
					<br />
					<a href="logout.php">Logout</a>
				<?php } else
				{?>
					<h3>Welcome!</h3><br />
					<form action="login.php?ref=<?php print urlencode(RemoveGetRefFromLogin($_SERVER['REQUEST_URI'])); ?>" method="post">
					Username<br />
					<input type="text" name="username" maxlength="22" /><br />
					Password<br />
					<input type="password" name="password" /><br />
					<input type="checkbox" name="remember" checked="checked" /> Keep me logged in<br />
					<input type="submit" name="submit" value="Login!" /><br />
					<br />
					</form>
					
					<a href="register.php?act=retrieve">Forgot your password?</a><br />
					<a href="register.php">Register Here!</a>
					
				<?php
				}?>
			</div>
		</div>
		
		<div class="main_right">
			<div class="main_title">Server Status</div>
			<div class="content" align="center">
				<b>Realmlist:</b> <?php print $LOGON_REALMLIST; ?>
				<?php
				foreach($REALM as $rid => $rdata)
				{
					print "<hr />
					<h3>{$rdata['NAME']}</h3><br />
					<div id='server_status_{$rid}'>
						<img src='images/loading-small.gif' alt='Loading' />
					</div>";
					?>
					<script type="text/javascript">
						$(document).ready(function(){
							$.ajax({
								url: "includes/ajax/server_status.php",
								dataType: "json",
								data: {rid: '<?php print $rid; ?>'},
								type: "POST",
								success: function(msg){
									$("#server_status_<?php print $rid; ?>").hide();
									if(msg['status'])
									{
										$("#server_status_<?php print $rid; ?>").html("<img src='images/icons/uparrow.gif' alt='Online' /><br /><b>Online Players:</b> " + msg['online']);
									}
									else
									{
										$("#server_status_<?php print $rid; ?>").html("<img src='images/icons/downarrow.gif' alt='Offline' /><br /><b>Online Players:</b> " + msg['online']);
									}
									$("#server_status_<?php print $rid; ?>").show(500);
								},
								error: function()
								{
									$("#server_status_<?php print $rid; ?>").html("Error Loading");
								}
							});
						});
					</script>
				<?php
				}
				?>
			</div>
		</div>
		
	</div>
	<!-- ----------- Right Side END ----------- -->
<?php 
$_numqueries = 0;
$_numqueries += $DB->numQueries;
$_numqueries += $LOGONDB->numQueries;
foreach($CHARACTERDB as $CHDB)
{
	$_numqueries += $CHDB->numQueries;
}
foreach($WORLDDB as $WDB)
{
	$_numqueries += $WDB->numQueries;
}
?>
<?php if($DEBUG) { ?>
	<div class="left_top"></div><div class="left_content">
		<div class="main_title">SQL Queries</div>
		<div class="content">
			<?php
			print '<h3>$DB</h3>';
			_print_r($DB->ArrQry);
			print '<h3>$LOGONDB</h3>';
			_print_r($LOGONDB->ArrQry);
			print '<h3>$CHARACTERDB array</h3>';
			foreach($CHARACTERDB as $CHDB)
			{
				_print_r($CHDB->ArrQry);
			}
			if(count($WORLDDB))
			{
				print '<h3>$WORLDDB array</h3>';
				foreach($WORLDDB as $WDB)
				{
					_print_r($WDB->ArrQry);
				}
			}
			?>
		</div>
	</div><div class='left_bottom'></div>
<?php } ?>
</div>
		<div id="page_right">
		<?php if(!$OFFLINE_MAINTENANCE || $USER['access'] >= 4) {?>
			<div><a href="vote.php" title="Vote Now!"><img src="images/mmopro/vote.png" alt="" class="png" /></a></div>
			<div><a href="donate.php" title="Donate To Support Us"><img src="images/mmopro/donate.png" alt="" class="png" /></a></div>

					<div class="box2">
						<div class="box_top"></div>
							 <div class="box_content_sub">
							 <div class="main_title">Server Status</div>
							 	<div class="box_content_container">
							 	
							 	<table width="100%">
								 <?php foreach($REALM as $rid => $rdata)
								 {?>
									
										<tr>
											<th colspan="2"><?php print $rdata['NAME']; ?>'s Status</th>
										</tr>
										<tr>
											<td>State:</td>
											<td><div id="server_status_state_<?php print $rid; ?>"><img src='images/loading-small.gif' alt='Loading' /></div></td>
										</tr>
										<tr>
											<td>Online Players:</td>
											<td><div id="server_status_online_<?php print $rid; ?>"><img src='images/loading-small.gif' alt='Loading' /></div></td>
										</tr>
										<tr>
											<td>Uptime:</td>
											<td><div id="server_status_uptime_<?php print $rid; ?>"><img src='images/loading-small.gif' alt='Loading' /></div></td>
										</tr>
<script type="text/javascript">
function LoadStatus_<?php print $rid; ?>()
{
	$(document).ready(function(){
	$.ajax({
		url: "includes/ajax/server_status.php",dataType: "json",data: {rid: '<?php print $rid; ?>'},type: "POST",
		success: function(msg){
			$("#server_status_state_<?php print $rid; ?>").hide();$("#server_status_online_<?php print $rid; ?>").hide();$("#server_status_uptime_<?php print $rid; ?>").hide();
			if(msg['status']){
				$("#server_status_state_<?php print $rid; ?>").html("<img src='images/icons/uparrow.gif' alt='Online' />");
			}
			else{
				$("#server_status_state_<?php print $rid; ?>").html("<img src='images/icons/downarrow.gif' alt='Offline' />");
			}
			$("#server_status_state_<?php print $rid; ?>").fadeIn(750);$("#server_status_online_<?php print $rid; ?>").html("<a href='online.php?rid=<?php print $rid; ?>'>" + msg['online']+" (Maximum Online "+msg['maxplayers']+")</a>").fadeIn(750);$("#server_status_uptime_<?php print $rid; ?>").html(msg['uptime']).fadeIn(750);
		},
		error: function(){$("#server_status_state_<?php print $rid; ?>, #server_status_online_<?php print $rid; ?>, #server_status_uptime_<?php print $rid; ?>").html("Error Loading");}
	});
	});
	setTimeout("LoadStatus_<?php print $rid; ?>()", 120000);
}
LoadStatus_<?php print $rid; ?>();
</script>
								<?php }?>
								<tr>
											<th colspan="2"><center>set realmlist <?php print $LOGON_REALMLIST; ?></center></th>
								</tr>
								</table>
									
								</div>
							 </div>
						 <div class="box_bottom"></div>
					</div>

					<div class="box2">
						<div class="box_top"></div>
							 <div class="box_content_sub">
							 <div class="main_title">Ventrillo</div>
							 	<div class="box_content_container">

									<table>
										<tr>
											<td style="width:70%">Host/IP:</td>
											<td style="width:50%">uranium.typefrag.com</td>
										</tr>
										<tr>
											<td style="width:50%">Port Number:</td>
											<td style="width:50%">5783</td>
										</tr>
									</table>
								</div>
							 </div>
						 <div class="box_bottom"></div>
					</div>
				<?php } ?>				
			</div>
		

		<!-- Bottom ad place -->
		<div class="clear"></div>
		<table class="center">
		<tr>
		<th><img src="https://www.google.com/adsense/static/en_GB/images/leaderboard.gif" /></th>
		</tr>
		</table>
</div>
<div class="box_in"></div>
<!-- Footer -->
<div class="footer_top"><img src="images/mmopro/pixel.gif" alt="" /></div>
<div class="footer_content">
		<div id="footer_cont" align="left">
			<?php foreach($REALM as $rid => $rdata) { ?>
				<h5><?php print $rdata['NAME']; ?> Random 50 Online Players:</h5>
				<?php
				$rand_online = RandomOnlinePlayers($rid);
				if(!count($rand_online))
				{
					print "No online players in this realm.";
				}
				$print_rand_online = null;
				foreach($rand_online as $odata)
				{
					$print_rand_online .= "<a href='character.php?rid={$rid}&cid={$odata['guid']}'>" . $odata['name'] . "</a>, ";
				}
				$print_rand_online = substr($print_rand_online, 0, -2);
				print $print_rand_online;
				?><br />
			<?php } ?>
		</div>
</div>
 <div class="footer_bottom"> </div>


<div class="footer_top"><img src="images/mmopro/pixel.gif" alt="" /></div>
<div class="footer_content">
		<div id="footer_cont">
			<div class="float_left">
				 Copyright &copy; 2010 MMOPro WoW. All Rights Reserved.
			</div>

			<div class="float_right">
				<a href="#" title="">Terms &amp; Condition</a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="#" title="">Privacy Policy</a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="#" title="">Sitemap</a>
				<br />
			</div>
			<div style="clear:both;"></div>
			<br /><b>Queries:</b> <?php print $_numqueries; ?>, <b>Execution Time:</b> <?php print $executiontime; ?>
		</div>
</div>
 <div class="footer_bottom"> </div>
</div>
<br />
<div id="footer_loading">
		<div id="footer_loading_text"></div>
</div>
</body>
</html>
</div>
<div class="clear"></div>
</div>

<div class="rt-shadowbar"></div>
<div align="center"><?php print $cms->config['ad_footer']; ?></div>

</div>
</div>
</div>	
<div class="rt-box-bottom"><div class="rt-box-bottom2"></div><div class="rt-box-bottom3"></div></div>
</div>
<div class="rt-shadowbar"></div>

<?php if($DEBUG && $USER['access'] >= 4) { ?>
<div class="clear"><div class="main">
<div class="main_title">SQL Queries</div>
<div class="content">
	<h3>$DB</h3>
	<?php _print_r($DB->ArrQuery); ?>
</div>
</div></div>
<div class="rt-shadowbar"></div>
<?php } ?>

<div id="footer_container">
<div class="right">
<h5>Visitors</h5>
<?php
$print_rand_online = null;
$unregisteredonline = 0;
foreach($website_onlines as $odata)
{
	if(empty($odata['username']) || $odata['uid'] == 0)
	{
		$unregisteredonline++;
		continue;
	}
	$print_rand_online .= $odata['username']. ", ";
}

$print_rand_online = substr($print_rand_online, 0, -2);
print $print_rand_online;
if($print_rand_online == null)
{
	print "No Visitors Online.";
}
?>

<?php foreach($REALM as $rid => $rdata) { ?>
<h5><?php print $rdata['NAME']; ?> Random 50 Online Players:</h5>
<?php
if(!count($rand_online[$rid]))
{
	print "No online players in this realm.";
}
$print_rand_online = null;
foreach($rand_online[$rid] as $odata)
{
	$print_rand_online .= "<a href='character.php?rid={$rid}&cid={$odata['guid']}'>" . $odata['name'] . "</a>, ";
}
$print_rand_online = substr($print_rand_online, 0, -2);
print $print_rand_online;
}?>
</div>
<div class="left">
<h5>Visitors Online</h5>
<ul>
<?php print "<li><b>Total:</b> ".count($website_onlines). "</li><li><b>Players:</b> ".(count($website_onlines) - $unregisteredonline). "</li><li><b>Guests:</b> ". $unregisteredonline. "</li>"; ?>
</ul>
</div><div class="clear"></div>
</div>

</div>
<div id="footer_bar"><div class="text">
<div class="right">
<b><a href="tos.php">Terms &amp; Condition</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="points.php?act=refundpolicy">Refund Policy</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="about.php">About Us</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="privacy.php">Privacy Policy</a></b>
<br /><b>Queries:</b> <?php print $DB->NumQueries; ?>, <b>Execution Time:</b> <?php print $executiontime; ?>, <b>Memory Usage:</b> <?php print round((memory_get_usage() - START_MEMORY)/1024); ?>K
</div>
<?php print $COPYRIGHT; ?>
<div class="clear"></div>
</div></div>

<div id="footer_loading">
<div id="footer_loading_text"></div>
</div>

</div>

</body>
</html>
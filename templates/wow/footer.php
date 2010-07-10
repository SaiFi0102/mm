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
	<div class="main">
		<div class="main_title">SQL Queries</div>
		<div class="content" id="jstest"></div>
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
	</div>
<?php } ?>
	<!-- ----------- Footer ----------- -->
	<div class="footer">
		<div class="main_title">Status and Information</div>
		<?php foreach($REALM as $rid => $rdata) { ?>
		<div class="content">
			<b><?php print $rdata['NAME']; ?> Random 50 Online Players:</b><br />
			<?php
			$rand_online = RandomOnlinePlayers($rid);
			$print_rand_online = null;
			foreach($rand_online as $odata)
			{
				$print_rand_online .= "<a href='character.php?rid={$rid}&cid={$odata['guid']}'>" . $odata['name'] . "</a>, ";
			}
			$print_rand_online = substr($print_rand_online, 0, -2);
			print $print_rand_online;
			?>
		</div><?php } ?>
		
		<div class="footer_right">
			<?php print $COPYRIGHT; ?><br />
			<b>Queries:</b> <?php print $_numqueries; ?>, <b>Execution Time:</b> <?php print $executiontime; ?><br /><br />
			Valid <a href="http://validator.w3.org/check?uri=referer" title="Validate">XHTML</a> and <a href="http://jigsaw.w3.org/css-validator/check/referer" title="Validate">CSS</a>
			<br /><br /><b>Made by <a href='mailto:saif@fistrive.com'>Saif</a></b>
		</div>
	</div>
	
	<div class="lichking"></div>
	<div class="bottom"></div>
	<!-- ----------- Footer END ----------- -->
	<div id="footer_loading">
		<div id="footer_loading_text"></div>
	</div>
</body>
</html>
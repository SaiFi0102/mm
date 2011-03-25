<?php foreach($REALM as $rid => $rdata)
{?>
<div class="main"><div class="main_title_top"></div>
	<div class="main_title"><?php print $rdata['NAME']; ?></div>
	<div class="content">
			<h4>Description/Role:</h4>
			<?php print $rdata['DESC_LONG']; ?><br />
	</div>
</div>
<?php }?>
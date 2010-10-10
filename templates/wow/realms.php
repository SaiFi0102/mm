<div class="left_top"></div><div class="left_content">
	<div class="main_title">Realms Information</div>
	<div class="content">
		<?php foreach($REALM as $rid => $rdata)
		{
			print "<h3>{$rdata['NAME']}</h3>
			<b>Description/Role:</b><br />
			{$rdata['DESC_LONG']}<br />";
		} ?>
	</div>
</div>
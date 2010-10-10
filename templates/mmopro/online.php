<div class="left_top"></div><div class="left_content">
	<div class="main_title">Online Players</div>
	<div class="content">
		<div class="noticebox">
			<b>Total Online Players:</b> <?php print count($onlines); ?>
		</div><br />
		
		<table cellspacing="0" cellpadding="5px" width="100%" border="1px">
			<tr>
				<th>Name</th>
				<th>Level</th>
				<th>Race</th>
				<th>Class</th>
				<th>Location</th>
			</tr>
			<?php
			if(!count($onlines))
			{
				eval($templates->Output("online_no_onlines", false, false, false, true));
			}
			$i = 1;
			foreach($onlines as $_cdata)
			{
				if($i > 100)
				{
					break;
				}
				eval($templates->Output("online_player_bit", false, false, false, true));
				$i++;
			}
			?>
		</table>
	</div>
</div><div class='left_bottom'></div>
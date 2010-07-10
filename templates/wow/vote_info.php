<div class="main">
	<div class="main_title">Vote!</div>
	<div class="content">
		<div align="center">
			<h3><a href="vote.php">[Vote Now and Info]</a> - <a href="vote.php?act=spend">[Buy Vote Items]</a></h3>
		</div><br />
		
		<div class="noticebox">
		Once you vote you'll receive voting points after the vote have been successfuly counted. You only receive vote points after every 12hours, If you vote once and vote again before 12 hours on the same gateway you will not receive points for it.
		</div><br />
		
		<div align="center">
		<?php
		if($USER['loggedin'])
		{ ?>
			<b>Your Statistics</b><br />
			Voting Points: <?php print $USER['votepoints']; ?><br />
			Times Voted: <?php print $USER['voted']; ?>
		<?php
		}
		else
		{
		?>
		<b>You are not logged in. Please log in to receive vote points!</b>
		<?php
		}
		?>
		</div><br />
		
		<h3>Vote now!</h3>
		<?php
		foreach($gateways as $vg_data)
		{
			eval($templates->Output("votegateway_bit", false, false, false, true));
		}
		if(!count($gateways))
		{
			eval($templates->Output("votegateways_notexists", false, false, false, true));
		}
		?>
	</div>
</div>
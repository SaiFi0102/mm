<div class="main">
	<div class="main_title">Vote!</div>
	<div class="content">
		<div align="center">
			<h3><a href="vote.php">[Vote Now and Info]</a> - <a href="vote.php?act=spend">[Buy Vote Items]</a></h3>
		</div><br />
		
		<div class="noticebox">
		Once you vote you'll receive voting points after the vote have been successfuly counted. You can only vote on a gateway every 12 hours.
		</div><br />
		
		<div align="center">
		<?php
		if($USER['loggedin'])
		{ ?>
			<h4>Your Statistics</h4>
			<b>Voting Points:</b> <?php print $USER['votepoints']; ?><br />
			<b>Times Voted:</b> <?php print $USER['voted']; ?>
		<?php
		}
		else
		{
		?>
		<h5>You are not logged in. Please log in to receive vote points!</h5>
		<?php
		}
		?>
		</div><br />
		
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
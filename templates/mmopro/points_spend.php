<div class="left_top"></div><div class="left_content">
	<div class="main_title">Buy <?php print $cms->config['websitename']; ?> Points Items</div>
	<div class="content">
		<div align="center">
			<h4><a href="points.php">[Form and Overview]</a> - <a href="points.php?act=spend">[<?php print $cms->config['websitename']; ?> Points Items]</a> - <a href="points.php?act=faq">[<?php print $cms->config['websitename']; ?> Points Faq]</a></h4>
		</div><br />
		<?php 
		print $cms->ErrorOutput();
		if(isset($successmessage))
		{
			print "<div class='successbox'>$successmessage</div>";
		}
		?>
		<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
		<fieldset>
			<legend><b>Step #1</b></legend>
			<h4>Select a Character</h4>
			<?php
			if(!count($characters))
			{
				eval($templates->Output("characters_notexists", false, false, false, true));
			}
			foreach($characters as $_cdata)
			{
				eval($templates->Output("character_bit", false, false, false, true));
			}
			?>
		</fieldset>
		<fieldset>
			<legend><b>Step #2</b></legend>
			<h4>Select a Reward</h4>
			<?php
			if(!count($rewards))
			{
				eval($templates->Output("rewards_notexists", false, false, false, true));
			}
			foreach($rewards as $_rdata)
			{
				eval($templates->Output("rewards_bit", false, false, false, true));
			}
			?>
		</fieldset>
		<fieldset>
			<legend>Step #3</legend>
			<h4>Review and Buy!</h4>
			<h5>Your <?php print $cms->config['websitename']; ?> Points: <b><?php print $USER['donationpoints']; ?></b></h5>
			<div align="center">
				<input type="submit" name="submit" value="Claim Reward!" />
			</div>
		</fieldset>
		</form>
	</div>
</div><div class='left_bottom'></div>
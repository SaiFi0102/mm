<div class="main">
	<div class="main_title">Buy Domination Items</div>
	<div class="content">
		<div align="center">
			<h3><a href="dominate.php">[Form and Overview]</a> - <a href="dominate.php?act=spend">[Domination Items]</a> - <a href="dominate.php?act=faq">[Domination Faq]</a></h3>
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
			<h3>Select a Character</h3>
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
			<h3>Select a Reward</h3>
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
			<h3>Review and Buy!</h3>
			<b>Your Domination Points:</b> <?php print $USER['donationpoints']; ?><br />
			<div align="center">
				<input type="submit" name="submit" value="Claim Reward!" />
			</div>
		</fieldset>
		</form>
	</div>
</div>
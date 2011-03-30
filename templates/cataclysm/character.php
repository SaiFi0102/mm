<div class="main">
	<div class="main_title">Character Details and Tools</div>
	<div class="content">
		<?php
		if(!count($_cdata))
		{
			eval($templates->Output("character_notexists", false, false, false, true));
		}
		else
		{
			eval($templates->Output("character_bit", false, false, false, true));
		}
		?>
	</div>
</div>
<?php
if(isset($_GET['act']) && $_GET['act'] == "unstuck" && $_cdata['account'] == $USER['id'])
{?>
<div class="main">
	<div class="main_title">Character Unstuck</div>
	<div class="content">
		<?php print $cms->ErrorOutput(); ?>
		<div class="noticebox"><span>
			Are you sure you want to use unstuck tool on this character?<br />
			You will be revived and teleported.
		</span></div><br />
		<div align="center">
			<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
			<input type="submit" name="submit" value="Unstuck!" />
			</form>
		</div>
	</div>
</div>
<?php
}
?>
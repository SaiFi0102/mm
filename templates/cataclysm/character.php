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
if(isset($_GET['act']) && $_cdata['account'] == $USER['id'])
{
	if($_GET['act'] == "unstuck")
	{?>
	<div class="main">
		<div class="main_title">Character Unstuck/Revive</div>
		<div class="content">
			<?php print $cms->ErrorOutput(); ?>
			<div class="noticebox"><span>
				Are you sure you want to use the Unstuck/Revive tool on this character?<br />
				You will be revived and teleported to a safe location.
			</span></div>
			<div align="center">
				<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
				<input type="submit" name="submit" value="Unstuck!" />
				</form>
			</div>
		</div>
	</div>
	<?php
	}
	if($_GET['act'] == "customize")
	{?>
	<div class="main">
		<div class="main_title">Character Rename/Customize</div>
		<div class="content">
			<?php print $cms->ErrorOutput(); ?>
			<div class="noticebox"><span>
				Are you sure you want to use Rename/Customize on this character for <b><i><?php print $cms->config['cost_customizetool']; ?> Vote Points</i></b>?<br />
				You will be prompted to customize your character in the character selection screen in game.
			</span></div>
			<div align="center">
				<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
				<input type="submit" name="submit" value="Customize!" />
				</form>
			</div>
		</div>
	</div>
	<?php
	}
	if($_GET['act'] == "factionchange")
	{?>
	<div class="main">
		<div class="main_title">Character Faction Change</div>
		<div class="content">
			<?php print $cms->ErrorOutput(); ?>
			<div class="noticebox"><span>
				Are you sure you want to change the faction of this character for <b><i><?php print $cms->config['cost_factionchange']; ?> Vote Points</i></b>?<br />
				You will be prompted to customize your character in the character selection screen in game.
			</span></div>
			<div align="center">
				<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
				<input type="submit" name="submit" value="Customize!" />
				</form>
			</div>
		</div>
	</div>
	<?php
	}
	if($_GET['act'] == "racechange")
	{?>
	<div class="main">
		<div class="main_title">Character Race Change</div>
		<div class="content">
			<?php print $cms->ErrorOutput(); ?>
			<div class="noticebox"><span>
				Are you sure you want to change the race of this character for <b><i><?php print $cms->config['cost_racechange']; ?> Vote Points</i></b>?<br />
				You will be prompted to customize your character in the character selection screen in game.
			</span></div>
			<div align="center">
				<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
				<input type="submit" name="submit" value="Customize!" />
				</form>
			</div>
		</div>
	</div>
	<?php
	}
}
?>
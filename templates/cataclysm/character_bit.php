<?php

//More trusted variables ... easy to change :P
$CDATA_GUID = $_cdata['guid'];
$CDATA_ACCOUNTID = $_cdata['account'];
$CDATA_NAME = $_cdata['name'];
$CDATA_RACE = $_cdata['race'];
$CDATA_CLASS = $_cdata['class'];
$CDATA_GENDER = $_cdata['gender'];
$CDATA_LEVEL = $_cdata['level'];
$CDATA_MONEY = $_cdata['money'];
$CDATA_MAPID = $_cdata['map'];
$CDATA_ONLINE = $_cdata['online'];
$CDATA_ZONEID = $_cdata['zone'];
?>

<div class="characters" id="CHAR_<?php print $CHARACTERLIST_RID; ?>_<?php print $CDATA_GUID ?>"
<?php if(isset($CHARACTERLIST_SELECTION) && $CHARACTERLIST_SELECTION) print 'style="cursor:pointer;"'; ?> >
	<!-- Character Avatar and images -->
	<div class="cleft">
		<div class="avatar">
			<div class="shell">
				
				<div style="background: transparent url('images/avatars/wow/<?php print $CDATA_GENDER."-".$CDATA_RACE."-".$CDATA_CLASS; ?>.gif') repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; width: 64px; height: 64px;"></div>
				
				<div class="frame">
					<img src="<?php print $cms->config['websiteurl']; ?>/images/pixel.gif" alt="" border="0" width="82" height="83">
				</div>
				<div class="iconPosition">
					<?php print $CDATA_LEVEL; ?>
				</div>
			</div>
		</div>
		
		<div style="text-align: center;">
			<img alt="" height="18" width="18" src="<?php print $cms->config['websiteurl']; ?>/<?php print WoW::$raceIcons[$CDATA_GENDER][$CDATA_RACE]; ?>" onmouseover="toolTip(this, '<?php print EscapeHtml(WoW::$arrRace[$CDATA_RACE]); ?>');" />
			<?php if($CDATA_ONLINE) print '<img src="'.$cms->config['websiteurl'].'/images/icons/uparrow.gif" onmouseover="toolTip(this, \'Online\');" height="19" width="18" />' ?>
			<img alt="" height="18" width="18" src="<?php print $cms->config['websiteurl']; ?>/<?php print WoW::$classIcons[$CDATA_CLASS]; ?>" onmouseover="toolTip(this, '<?php print EscapeHtml(WoW::$arrClass[$CDATA_CLASS] . " - " . WoW::$arrGender[$CDATA_GENDER]); ?>');" />
		</div>
		
	</div>
	
	<?php
	if(isset($CHARACTERLIST_SHOW_TOOLS) && $CHARACTERLIST_SHOW_TOOLS)
	{ ?>
		<div class="cright">
			<h5 style="color:#ffffff;text-align:center;text-shadow:#000000 1px 1px 0;">Player Tools</h5>
			<a href="character.php?act=unstuck&rid=<?php print $CHARACTERLIST_RID ?>&cid=<?php print $CDATA_GUID; ?>" style="float:right;">
				<div class="tool_bit">
					<div class="tool_bit_border">
						<img src="<?php print $cms->config['websiteurl']; ?>/images/icons/unstuck.png" height="40" width="40" alt="" />
						<br />Unstuck/Revive
						<br /><b>FREE!</b>
					</div>
				</div>
			</a>
			<a href="character.php?act=customize&rid=<?php print $CHARACTERLIST_RID ?>&cid=<?php print $CDATA_GUID; ?>" style="float:right;">
				<div class="tool_bit">
					<div class="tool_bit_border">
						<img src="<?php print $cms->config['websiteurl']; ?>/images/icons/rename.png" height="40" width="40" alt="" />
						<br />Rename/Customize
						<br /><b>Cost:</b> <i><?php print $cms->config['cost_customizetool']; ?></i> Vote Points
					</div>
				</div>
			</a>
			<a href="character.php?act=racechange&rid=<?php print $CHARACTERLIST_RID ?>&cid=<?php print $CDATA_GUID; ?>" style="float:right;">
				<div class="tool_bit">
					<div class="tool_bit_border">
						<img src="<?php print $cms->config['websiteurl']; ?>/images/icons/racechange.png" height="40" width="40" alt="" />
						<br />Race Change
						<br /><b>Cost:</b> <i><?php print $cms->config['cost_racechange']; ?></i> Vote Points
					</div>
				</div>
			</a>
			<a href="character.php?act=factionchange&rid=<?php print $CHARACTERLIST_RID ?>&cid=<?php print $CDATA_GUID; ?>" style="float:right;">
				<div class="tool_bit">
					<div class="tool_bit_border">
						<img src="<?php print $cms->config['websiteurl']; ?>/images/icons/factionchange.png" height="40" width="40" alt="" />
						<br />Faction/Race Change
						<br /><b>Cost:</b> <i><?php print $cms->config['cost_factionchange']; ?></i> Vote Points
					</div>
				</div>
			</a>
		</div><?php
	}?>
	
	<!-- Character Name and Selection Radios -->
	<h3>
		<a href="character.php?rid=<?php print $CHARACTERLIST_RID; ?>&cid=<?php print $CDATA_GUID; ?>"><?php print FirstCharUpper($CDATA_NAME); ?></a>
		
		<?php if(isset($CHARACTERLIST_SELECTION) && $CHARACTERLIST_SELECTION)
		{ ?>
		<input type="radio" name="character_selected" value="<?php print $CDATA_GUID ?>"
		<?php if(	(isset($CHARACTERLIST_MUSTBEONLINE)		&& $CHARACTERLIST_MUSTBEONLINE	&& !$CDATA_ONLINE)
				||	(isset($CHARACTERLIST_MUSTBEOFFLINE)	&& $CHARACTERLIST_MUSTBEOFFLINE	&& $CDATA_ONLINE))
				{
					print "disabled='disabled' />";
					print " (You must be ";
					if($CHARACTERLIST_MUSTBEONLINE) print "<u>online</u>";
					if($CHARACTERLIST_MUSTBEOFFLINE) print "<u>offline</u>";
					print " to select this character!)";
				} else { ?> /> <?php } ?>
		<?php
		} ?>
	</h3>
	
	<!-- Character Details -->
	Level <?php print $CDATA_LEVEL; ?> <?php print WoW::$arrFaction[$CDATA_RACE] ?>: <?php print WoW::$arrRace[$CDATA_RACE]; ?>
	<br />
	
	<b>Class:</b> <?php print WoW::$arrClass[$CDATA_CLASS]; ?>
	<br />
	
	<b>Location:</b> <?php print WoW::$arrZones[$CDATA_ZONEID]; ?>, <?php print WoW::$arrMaps[$CDATA_MAPID]; ?>
	<br />
	
	<b>Money:</b> <?php $goldstr = "[gold]{$CDATA_MONEY}[/gold]"; ParseGold($goldstr); print $goldstr; ?>
	<div class="clear"></div>
</div>
<!-- Character Selection JAVASCRIPT -->
<?php
if(isset($CHARACTERLIST_SELECTION) && $CHARACTERLIST_SELECTION)
{ ?>
	<script type="text/javascript">
		$("#CHAR_<?php print $CHARACTERLIST_RID; ?>_<?php print $CDATA_GUID ?>").click(function(){
			$("#CHAR_<?php print $CHARACTERLIST_RID; ?>_<?php print $CDATA_GUID ?> input[type=radio]").attr({checked: true});
		});
	</script>
<?php
}?>

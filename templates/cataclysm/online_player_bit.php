<tr class="online_player_bit" id="CHAR_ONLINE_<?php print $_cdata['guid']; ?>">
	<td>
		<?php print FirstCharUpper($_cdata['name']); ?>
	</td>
	
	<td>
		<?php print $_cdata['level']; ?>
	</td>
	
	<td>
		<img height="18" width="18" src="<?php print $cms->config['websiteurl']; ?>/<?php print WoW::$raceIcons[$_cdata['gender']][$_cdata['race']]; ?>" onmouseover="toolTip(this, '<?php print EscapeHtml(WoW::$arrGender[$_cdata['gender']]." ".WoW::$arrRace[$_cdata['race']]); ?>');" alt="" />
	</td>
	
	<td>
		<img height="18" width="18" src="<?php print $cms->config['websiteurl']; ?>/<?php print WoW::$classIcons[$_cdata['class']]; ?>" onmouseover="toolTip(this, '<?php print EscapeHtml(WoW::$arrClass[$_cdata['class']]); ?>');" alt="" />
	</td>
	
	<td>
		<?php print WoW::$arrZones[$_cdata['zone']]; ?>, <?php print WoW::$arrMaps[$_cdata['map']]; ?>
	</td>
</tr>

<tr id="CHAR_DETAILS_<?php print $_cdata['guid']; ?>" style="display:none;">
	<td colspan="5"></td>
</tr>
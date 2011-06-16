<?php
$itemsarray = RewardsItemsColumnToArray($_rdata['items']);

print "<div class='reward_bit' id='REWARD_{$_rdata['id']}'>
<div class='reward_bit_border'>
<input type='radio' name='reward_selected' value='{$_rdata['id']}' />
{$_rdata['description']}<br />

<b>You will receive the following items from this reward:</b>
<ul>";
	foreach($itemsarray as $itemdata)
	{
		print "<li><span class='".ItemQualityToColorClass($itemnames[$itemdata['itemid']][1])."'>[".ItemIdToName($itemnames, $itemdata['itemid'])."] X {$itemdata['itemcount']}</span></li>";
	}
	if($_rdata['gold'] > 0)
	{
		$gold = "[gold]{$_rdata['gold']}[/gold]";
		$gold = ParseGold($gold);
		print "<li>{$gold}</li>";
	}
	else
	{
		if(!count($itemsarray))
		{
			print "<li>There are no items in this reward</li>";
		}
	}
print "</ul>
<b>Cost:</b> <span id='REWARD_{$_rdata['id']}_POINTS'>{$_rdata['points']}</span>
</div></div><br />";
?>
<script type="text/javascript">
	$("#REWARD_<?php print $_rdata['id']; ?>").click(function(){
		$("#REWARD_<?php print $_rdata['id']; ?> input[type=radio]").attr({checked: true});
	});
</script>
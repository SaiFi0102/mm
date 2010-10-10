<div class="vg_bit">
<form action="vote.php" method="post" target="_blank">
<?php
	print "
	<div class='right'>
		<img src='{$vg_data['image']}' alt='{$vg_data['name']}' />
	</div>
	<h5>{$vg_data['name']}</h5>";
	if(isset($previousvotes[$vg_data['id']]))
	{
		print VoteTimeLeft($previousvotes[$vg_data['id']]['time']);
		print " remaining to vote.<br />";
	}
	print "
	<input type='hidden' name='gateway' value='{$vg_data['id']}' />
	<input type='submit' name='submit' value='Vote Now!' "; if(isset($previousvotes[$vg_data['id']])) print "disabled='disabled'";
	print "/>
	<div class='clear'></div>
	";
?>
</form>
</div><br />
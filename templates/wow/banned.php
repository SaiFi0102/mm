<div class="left_top"></div><div class="left_content">
	<div class="main_title">You are banned!</div>
	<div class="content">
		<div style='color: #ff0000;'>
			<b>You've been banned because of the following reason:</b><br />
			<div style='text-indent: 15px;'><?php print $uclass->ban['reason']; ?></div>
			<br />
			<b>You were banned on:</b> <?php print BanTimeOut($uclass->ban['start']); ?><br />
			<b>Your ban will be lifted (on):</b> <?php print BanTimeOut($uclass->ban['end']); ?>
			<br /><br />
			Please contact us if you think this is an error.
		</div>
	</div>
</div>
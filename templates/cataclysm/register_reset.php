<div class="main">
	<div class="main_title">Reset lost password</div>
	<div class="content">
		<?php print $cms->ErrorOutput(); ?>
		<div class="noticebox"><span>Enter the reset confirmation code provided in the email message.</span></div><br />
		<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="get">
		<div align="center">
			Reset Confirmation Code<br />
			<input type="hidden" name="act" value="reset" />
			<input type="text" name="resetcode" size="20" value="<?php print $_GET['resetcode']; ?>" /><br /><br />
			<input type="hidden" name="uid" value="<?php print $_GET['uid']; ?>" />
			<input type="submit" value="Reset Password!" />
		</div>
		</form>
	</div>
</div>
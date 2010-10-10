<div class="left_top"></div><div class="left_content">
	<div class="main_title">Retrieve lost password</div>
	<div class="content">
		<?php print $cms->ErrorOutput(); ?>
		<div class="noticebox">
			Please enter your email address. We will email you the information to reset your password.
		</div><br />
		
		<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
		<table cellspacing="0" cellpadding="5px" border="0" align="center">
			<tr>
				<td>Email Address</td>
				<td>
					<input type="text" name="email" size="32"
					<?php if(isset($_POST['email'])) print 'value="' . EscapeHtml($_POST['email']) . '"' ?> />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="Reset my Password" /></td>
			</tr>
		</table>
		</form>
	</div>
	
</div>
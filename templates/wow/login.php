<div class="main">
	<div class="main_title">Login</div>
	<div class="content">
		<?php print $cms->ErrorOutput(); ?>
		<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
			<table cellpadding="5px" cellspacing="0" border="0">
				<tr>
					<td>Username</td>
					<td>
						<input type="text" name="username" maxlength="<?php print $cms->config['usermaxlen']; ?>"
						<?php if(isset($_POST['username'])) { print 'value="' . EscapeHtml($_POST['username']) . '"'; } ?> />
					</td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="password" /></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="checkbox" name="remember" checked="checked" /> Keep me logged in<br /><a href="register.php?act=retrieve">Click here to retrive your password</a></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="Login" /></td>
				</tr>
			</table>
		</form>
	</div>
</div><div class='left_bottom'></div>
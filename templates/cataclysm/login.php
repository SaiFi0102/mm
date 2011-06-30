<div class="main">
	<div class="main_title">Login</div>
	<div class="content">
		<?php print $cms->ErrorOutput(); ?>
		<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
			<table>
				<tr>
					<th>Email Address</th>
					<td>
						<input type="text" name="email" maxlength="<?php print $cms->config['usermaxlen']; ?>"
						<?php if(isset($_POST['email'])) { print 'value="' . EscapeHtml($_POST['email']) . '"'; } ?> />
					</td>
				</tr>
				<tr>
					<th>Password</th>
					<td><input type="password" name="password" /></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="checkbox" name="remember" checked="checked" /> Keep me logged in<br /><a href="register.php?act=retrieve">Click here to retrive your password</a></td>
				</tr>
				<tr>
					<th colspan="2" align="center"><input type="submit" name="submit" value="Login" /></th>
				</tr>
			</table>
		</form>
	</div>
</div>
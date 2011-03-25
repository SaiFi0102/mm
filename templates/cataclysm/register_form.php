<div class="main"><div class="main_title_top"></div>
	<div class="main_title">Registration Form</div>
	<div class="content">
		<h3>All fields are required.</h3>
		<?php print($cms->ErrorOutput()); ?>
		<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
		
		<fieldset>
		<legend>Login Details</legend>
			<table>
				<tr>
					<td>Username</td>
					<td>
						<input type="text" name="username" size="32"
						maxlength="<?php print $cms->config['usermaxlen']; ?>"
						<?php if(isset($_POST['username'])) print 'value="' . EscapeHtml($_POST['username']) . '"' ?> />
					 </td>
				</tr>
				
				<tr>
					<td>Password</td>
					<td>
						<input type="password" name="password" size="32" />
					 </td>
				</tr>
				
				<tr>
					<td>Confirm Password</td>
					<td>
						<input type="password" name="confirmpassword" size="32" />
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
		<legend>Personal Details</legend>	
			<table>
				<tr>
					<td>Email Address</td>
					<td>
						<input type="text" name="email" size="32"
						<?php if(isset($_POST['email'])) print 'value="' . EscapeHtml($_POST['email']) . '"' ?> />
					 </td>
				</tr>
				
				<tr>
					<td>Confirm Email Address</td>
					<td>
						<input type="text" name="confirmemail" size="32" />
					 </td>
				</tr>
				
				<tr>
					<td>Choose your client</td>
					<td>
						<select name="flags">
							<option value="2" selected="selected">Wrath of the Lich King</option>
							<option value="1">Burning Crusade</option>
							<option value="0">Classic WoW</option>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
		<legend>Captcha Verification</legend>
			<?php print recaptcha_get_html($cms->config['captchapubkey'], $captchaerror); ?>
		</fieldset>
		<center><input type="submit" name="submit" value="Register!" /></center>
		</form>
	</div>
</div>
<div class="main">
	<div class="main_title">Registration Form</div>
	<div class="content">
		<h3>All fields are required.</h3>
		<?php print($cms->ErrorOutput()); ?>
		<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
		
			<table border="0" width="100%">
				<tr>
					<th colspan="2">Login Details</th>
				</tr>
				
				<tr>
					<th>Username</th>
					<td>
						<input type="text" name="username" size="32" maxlength="<?php print $cms->config['usermaxlen']; ?>"
						<?php if(isset($_POST['username'])) print 'value="' . EscapeHtml($_POST['username']) . '"' ?> />
					 </td>
				</tr>
				<tr>
					<th>Password</th>
					<td>
						<input type="password" name="password" size="32" />
					 </td>
				</tr>
				<tr>
					<th>Confirm Password</th>
					<td>
						<input type="password" name="confirmpassword" size="32" />
					</td>
				</tr>
				
				<tr>
					<th colspan="2">Personal Details</th>
				</tr>
				
				<tr>
					<th>Country</th>
					<td><?php print $countrylisthtml; ?></td>
				</tr>
				<tr>
					<th>Email Address</th>
					<td>
						<input type="text" name="email" size="32" maxlength="256"
						<?php if(isset($_POST['email'])) print 'value="' . EscapeHtml($_POST['email']) . '"' ?> />
					 </td>
				</tr>
				<tr>
					<th>Secret Question 1</th>
					<td><?php print BuildSecretQuestions(1); ?></td>
				</tr>
				<tr>
					<th>Answer</th>
					<td>
						<input type="text" name="sa1" size="32"
						<?php if(isset($_POST['sa1'])) print 'value="' . EscapeHtml($_POST['sa1']) . '"' ?> />
					</td>
				</tr>
				<tr>
					<th>Secret Question 2</th>
					<td><?php print BuildSecretQuestions(2); ?></td>
				</tr>
				<tr>
					<th>Answer</th>
					<td>
						<input type="text" name="sa2" size="32"
						<?php if(isset($_POST['sa2'])) print 'value="' . EscapeHtml($_POST['sa2']) . '"' ?> />
					</td>
				</tr>
				
				<tr>
					<th colspan="2">Image Verification</th>
				</tr>
				<tr>
					<td colspan="2" align="center"><?php print recaptcha_get_html($cms->config['captchapubkey'], $captchaerror); ?></td>
				</tr>
				
				<tr>
					<th colspan="2" align="center"><input type="submit" name="submit" value=" Register! " /></th>
				</tr>
			</table>
		</form>
	</div>
</div>
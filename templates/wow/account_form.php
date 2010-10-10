<div class="left_top"></div><div class="left_content">
	<div class="main_title">Account Management</div>
	<div class="content">
		<fieldset>
		<legend>Account Overview</legend>
			<table cellpadding="5px" cellspacing="0" border="0" style="border: 1px solid darkgrey;">
				<tr>
					<td><b>Username</b></td>
					<td><?php print $USER['username']; ?></td>
				</tr>
				<tr style="background: grey;">
					<td><b>Email Address</b></td>
					<td><?php print $USER['email']; ?></td>
				</tr>
				<tr>
					<td><b>Account Group</b></td>
					<td><?php print AccessLevelToGroup($USER['gmlevel'], true); ?></td>
				</tr>
				<tr style="background: grey;">
					<td><b>Banned?</b></td>
					<td><?php if($uclass->banned) print "<font color='red'><b>Yes!</b></font>"; else print "<font color='darkgreen'><b>No</b></font>"; ?></td>
				</tr>
				<tr>
					<td><b>Last logged in game</b></td>
					<td><?php if($USER['last_login'] != '0000-00-00 00:00:00') print ConvertMysqlTimestamp($USER['last_login']); else print "Never logged in"; ?></td>
				</tr>
				<tr style="background: grey;">
					<td><b>Last logged in IP</b></td>
					<td><?php if($USER['last_ip'] != '0.0.0.0') print $USER['last_ip']; else print "Never logged in"; ?></td>
				</tr>
				<tr>
					<td><b>Registered on</b></td>
					<td><?php print ConvertMysqlTimestamp($USER['joindate']); ?></td>
				</tr>
				
			</table>
		</fieldset>
	
		<fieldset>
		<legend>Change Account Info</legend>
			<h3>Your current password is compulsory.<br />
			Please leave the fields blank if you don't want to change them and click submit when you are done.</h3>
			
			<?php print $cms->ErrorOutput(); ?>
			
			<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
			<table cellpadding="5px" cellspacing="0" border="0">
				<tr>
					<td>Current Password*</td>
					<td><input type="password" maxlength="32" name="currentpassword" /></td>
				</tr>
				<tr>
					<td>New Password</td>
					<td><input type="password" name="newpassword" maxlength="32" /></td>
				</tr>
				<tr>
					<td>Game Client</td>
					<td>
						<select name="newflags">
							<option value="2"<?php if($USER['expansion'] == 2) print " selected='selected'"; ?>>Wrath of the Lich King</option>
							<option value="1"<?php if($USER['expansion'] == 1) print " selected='selected'"; ?>>Burning Crusade</option>
							<option value="0"<?php if($USER['expansion'] == 0) print " selected='selected'"; ?>>Classic WoW</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="Submit" /></td>
				</tr>
			</table>
			</form>
		</fieldset>
	</div>
</div>
<div class="main">
	<div class="main_title">Account Management</div>
	<div class="content">
		<?php
		if(isset($update_result))
		{
			if($update_result)
			{?>
	<div class="successbox"><span>
		Account details successfully changed!			
	</span></div>
			<?php
			}
			else
			{?>
	<div class="errorbox"><span>
		Account details could not be changed.
	</span></div>
			<?php
			}
		}
		?>
	
		<fieldset>
		<legend>Account Overview</legend>
			<table width="100%">
				<tr>
					<th>Email Address</th>
					<td><?php print $USER['username']; ?></td>
					
					<th>Banned?</th>
					<td><?php if($uclass->banned) print "<font color='red'><b>Yes!</b></font>"; else print "<font color='darkgreen'><b>No</b></font>"; ?></td>
				</tr>
				<tr>
					<th>Registered on</th>
					<td><?php print ConvertMysqlTimestamp($USER['joindate']); ?></td>
					
					<th>Last logged in game</th>
					<td><?php if($USER['last_login'] != '0000-00-00 00:00:00') print ConvertMysqlTimestamp($USER['last_login']); else print "Never logged in"; ?></td>
				</tr>
				<tr>
					<th>Account Group</th>
					<td><?php print AccessLevelToGroup($USER['access'], true); ?></td>
					
					<th>Last logged in IP</th>
					<td><?php if($USER['last_ip'] != '0.0.0.0') print $USER['last_ip']; else print "Never logged in"; ?></td>
				</tr>
				
			</table>
		</fieldset>
	
		<fieldset>
		<legend>Change Account Info</legend>
			<div class="noticebox"><span>Your current password is compulsory.</span></div>
			
			<?php print $cms->ErrorOutput(); ?>
			
			<form action="<?php print $_SERVER['REQUEST_URI']; ?>" method="post">
			<table width="100%">
				<tr>
					<th width="20%">Current Password*</th>
					<td><input type="password" maxlength="32" name="currentpassword" size="32" /></td>
				</tr>
				<tr>
					<th width="20%">New Password</th>
					<td><input type="password" name="newpassword" maxlength="32" size="32" /></td>
				</tr>
				<tr>
					<th colspan="2" align="center"><input type="submit" name="submit" value="Submit" /></th>
				</tr>
			</table>
			</form>
		</fieldset>
	</div>
</div>
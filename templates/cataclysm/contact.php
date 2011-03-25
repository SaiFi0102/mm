<div class="main"><div class="main_title_top"></div>
	<div class="main_title">Contact Us Form</div>
	<div class="content">
		<?php
		if(isset($success))
		{
			print "<div class='successbox'><span>Your message has been sent to our administrators. Please wait upto 24hours for a reply on you email address. Usually you will get a reply in less than 12 hours.</span></div>";
		}
		if(isset($error))
		{
			print "<div class='errorbox'><span>There was a technical error. Please try again later.</span></div>";
		}
		?>
	
		<form action="contact.php" method="post">
		
		<div class="noticebox"><span>
			You can contact us by this form or you can make a ticket in game.<br />
			Please fill in the form below and click on "Send" to send us an email.
		</span></div><br />
		
		<h4>Your Email Adress:</h4>
		<input type="text" name="from" value="<?php print $USER['email'] ?>" /><br /><br />
		
		<h4>Reason:</h4>
		<select name="reason">
			<option value="Other" selected="selected">Other</option>
			<option value="Reporting Website Bugs">Reporting Website Bugs</option>
			<option value="Reporting In-Game Bugs">Reporting In-Game Bugs</option>
			<option value="Game/Client Help">Game/Client Help</option>
			<option value="Reporting Hackers">Reporting Hackers</option>
		</select><br /><br />
		
		<h4>Body:</h4>
		<textarea name="body" rows="15" cols="60"></textarea><br /><br />
		
		<input type="submit" name="submit" value="Send" />
		
		</form>
		
		<br /><br />
		<h4>Contact Details</h4>
		Address: <?php print $CONTACTDETAILS['ADDRESS']; ?><br /><br />
		Phone: <?php print $CONTACTDETAILS['PHONE']; ?>
	</div>
</div>
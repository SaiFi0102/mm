<div class="main">
	<div class="main_title">Donation Form</div>
	<div class="content">
		<div align="center">
			<h3><a href="donate.php">[Donation Form and Info]</a> - <a href="donate.php?act=spend">[Buy Donation Items]</a></h3>
		</div>
		
		<div class="noticebox">
			When you donate you will recieve donation points per the amount you donate, these donation points can be used to purchase donation items from <a href="donate.php?act=rewards">here</a>. 
			Donations are used for keeping the servers running and lag free by paying for the servers and host, none of the donation are used for personal means.<br /><br />
			You will receive donation points instantly after your payment is successful, sometimes it requires upto 12hours to process.
		</div><br />
		
		<div align="center">
			<b>Your Statistics</b><br />
			Donation Points: <?php print $USER['donationpoints']; ?><br />
			Times Donated: <?php print $USER['donated']; ?>
		</div><br />
		
		<div align="center">
			<form action="https://<?php print PAYPAL_GATEWAY_URL; ?>/cgi-bin/webscr" method="post" target="paypal">
			<b>Amount to donate:</b><br />
			<b>$</b> <input type="text" id="amount" name="amount" value="10" />
			<br /><br />
			<!--
			Don't bother trying to change anything here from JAVASCRIPT,
			you won't get your reward if you try! Totally secure
			-->
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="notify_url" value="<?php print $cms->config['websiteurl']; ?>/donate.php?act=validate" />
			<input type="hidden" name="item_name" value="Donation from <?php print $USER['username']; ?> to <?php print $cms->config['websitename']; ?>" />
			<input type="hidden" name="custom" value="<?php print $USER['id']; ?>" />
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="business" value="<?php print $cms->config['donationemail']; ?>" />
			<input type="hidden" name="currency_code" value="USD" />
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			</form>
		</div>
	</div>
</div>

<div class="main">
	<div class="main_title">Transactions</div>
	<div class="content">
		<table cellspacing="0" cellpadding="3px" width="100%" border="1px">
			<tr align="center">
				<th colspan="6">Your Transactions and their details</th>
			</tr>
			
			<tr>
				<th>Transaction ID</th>
				<th>Amount</th>
				<th>Payer Email</th>
				<th>Description</th>
				<th>Status</th>
				<th>Date</th>
			</tr>
			
			<?php
			if(!count($transactions))
			{
				print "<tr align='center'><td colspan='6'>You haven't made any transactions yet!</td></tr>";
			}
			foreach($transactions as $transaction)
			{
				//Variables
				$amount = "$".$transaction['amount'];
				$txnid = $transaction['transaction_id'];
				$payment_status = str_replace("_", " ", $transaction['payment_status']);
				$timestamp = ConvertMysqlTimestamp($transaction['timestamp']);
				
				//If reversed transaction or subtransaction
				if($transaction['status'] == "3")
				{
					$amount = "-".$amount;
				}
				if(!empty($transaction['real_transaction_id']))
				{
					$txnid = "&uarr;";
				}
				
				//Print Em
				print "
				<tr>
					<td align='center'>{$txnid}</td>
					<td>{$amount}</td>
					<td>{$transaction['sender_email']}</td>
					<td>{$transaction['details']}</td>
					<td>{$payment_status}</td>
					<td>{$timestamp}</td>
				</tr>";
			}
			?>
			
		</table>
	</div>
</div>
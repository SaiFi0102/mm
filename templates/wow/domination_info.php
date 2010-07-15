<div class="main">
	<div class="main_title">Domination Form</div>
	<div class="content">
		<div align="center">
			<h3><a href="dominate.php">[Form and Overview]</a> - <a href="dominate.php?act=spend">[Domination Items]</a> - <a href="dominate.php?act=faq">[Domination Faq]</a></h3>
		</div><br />
		
		<div class="noticebox">
			When you make a payment you will recieve Domination Points per the amount you paid, these Domination Points can be used to purchase Domination Items from <a href="dominate.php?act=rewards">here</a>. 
			<br /><br />
			You will receive Domination Points instantly after your payment is successful, sometimes it requires upto 12hours to process.
		</div><br />
		
		<div align="center">
			<h3>Your Statistics</h3><br />
			<b>Domination Points:</b> <?php print $USER['donationpoints']; ?><br />
			<b>Times Dominated:</b> <?php print $USER['donated']; ?>
		</div><br />
		
		<div align="center">
			<form action="https://<?php print PAYPAL_GATEWAY_URL; ?>/cgi-bin/webscr" method="post" target="paypal">
			<b>Domination Points:</b><br />
			<b>$</b> <input type="text" id="amount" name="amount" value="10" />
			<br /><br />
			<!--
			Don't bother trying to change anything here from JAVASCRIPT,
			you won't get your reward if you try! Totally secure
			-->
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="notify_url" value="<?php print $cms->config['websiteurl']; ?>/dominate.php?act=validate" />
			<input type="hidden" name="item_name" value="Payment from <?php print $USER['username']; ?> to <?php print $cms->config['websitename']; ?>" />
			<input type="hidden" name="custom" value="<?php print $USER['id']; ?>" />
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="business" value="<?php print $cms->config['donationemail']; ?>" />
			<input type="hidden" name="currency_code" value="USD" />
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
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
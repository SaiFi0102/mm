<div class="main">
	<div class="main_title">Payment Form</div>
	<div class="content">
		<div align="center">
			<h4><a href="points.php">[Form and Overview]</a> - <a href="points.php?act=spend">[<?php print $cms->config['websitename']; ?> Points Items]</a> - <a href="points.php?act=faq">[<?php print $cms->config['websitename']; ?> Points Faq]</a></h4>
		</div><br />
		
		<div class="noticebox">
			When you make a payment you will recieve <?php print $cms->config['websitename']; ?> Points per the amount you paid, you'll receive <?php print $cms->config['pointsperdollar_paypal']; ?> point(s) for payment by PayPal, <?php print $cms->config['pointsperdollar_alertpay']; ?> point(s) for AlertPay and <?php print $cms->config['pointsperdollar_moneybookers']; ?> point(s) for Moneybookers. These <?php print $cms->config['websitename']; ?> Points can be used to purchase <?php print $cms->config['websitename']; ?> Points Items from <a href="points.php?act=spend">here</a>. 
			<br /><br />
			You will receive <?php print $cms->config['websitename']; ?> Points instantly after your payment is successful, sometimes it requires upto 12hours to process.
		</div><br />
		
		<div align="center">
			<h4>Your Statistics</h4>
			<b><?php print $cms->config['websitename']; ?> Points:</b> <?php print $USER['donationpoints']; ?><br />
			<b>Times Paid:</b> <?php print $USER['donated']; ?>
		</div><br />
		
		<div align="center">
		<?php if((!$cms->config['show_paypal'] && !$cms->config['show_moneybookers'] && !$cms->config['show_alertpay']) || (!$cms->config['enable_paypal'] && !$cms->config['enable_moneybookers'] && !$cms->config['enable_alertpay'])) {?>
			<h5>Buying <?php print $cms->config['websitename']; ?> Points is currently not allowed. Please check again later.</h5>
		<?php }?>
		<?php if($cms->config['show_moneybookers']) { ?>
			<h5><b>Money</b>Bookers</h5>
			<form action="https://www.moneybookers.com/app/payment.pl" target="_blank">
			
			<b>$</b><input type="text" name="amount" value="10" id="amount_moneybookers" size="3" maxlength="6" /><br />
			You will receive <span id="points_moneybookers"><?php print $cms->config['pointsperdollar_moneybookers'] * 10; ?></span> points for $<span id="dollars_moneybookers">10</span><br />
			(<?php print $cms->config['pointsperdollar_moneybookers']; ?> Points for each $)
<script type="text/javascript">
$("#amount_moneybookers").change(function()
{
	$("#points_moneybookers").html(parseFloat($(this).val()) * <?php print $cms->config['pointsperdollar_moneybookers']; ?>);
	$("#dollars_moneybookers").html(parseFloat($(this).val()));
});
</script>
			<br />
			<?php if($cms->config['enable_moneybookers']) { ?>
			<input type="hidden" name="pay_to_email" value="<?php print $cms->config['email_moneybookers']; ?>" />
			<input type="hidden" name="return_url" value="<?php print $cms->config['websiteurl']; ?>/points.php" />
			<input type="hidden" name="return_url_target" value="4" />
			<input type="hidden" name="cancel_url" value="<?php print $cms->config['websiteurl']; ?>/points.php" />
			<input type="hidden" name="cancel_url_target" value="1" />
			<input type="hidden" name="status_url" value="<?php print $cms->config['websiteurl']; ?>/payments.php?gateway=MoneyBookers" />
			<input type="hidden" name="language" value="EN" />
			<input type="hidden" name="confirmation_note" value="Points will be added to your account as soon as the payment is received" />
			<input type="hidden" name="merchant_fields" value="accountid" />
			<input type="hidden" name="accountid" value="<?php print $USER['id']; ?>" />
			<input type="hidden" name="currency" value="USD" />
			<input type="hidden" name="detail1_description" value="Payment for:" />
			<input type="hidden" name="detail1_text" value="<?php print $USER['username']; ?>" />
			<input type="hidden" name="detail2_description" value="Account ID:" />
			<input type="hidden" name="detail2_text" value="<?php print $USER['id']; ?>" />
			<input type="hidden" name="submit_id" value="Submit" />
			<input type="image" src="http://www.moneybookers.com/images/logos/checkout_logos/checkout_120x40px.gif" width="120" height="40" border="0" name="Pay" alt="Recommended by us!" />
			<?php } else { ?>
			<img src="http://www.moneybookers.com/images/logos/checkout_logos/checkout_120x40px.gif" width="120" height="40" border="0" onmouseover="toolTip(this, 'Currently not not allowed!');" />
			<?php } ?>
			</form><br /><br />
		<?php } ?>
		
		<?php if($cms->config['show_alertpay']) { ?>
			<h5><b>Alert</b>Pay</h5>
			<form method="post" action="https://www.alertpay.com/checkout" target="_blank">
			<b>$</b><input type="text" name="ap_amount" value="10" id="amount_alertpay" size="3" maxlength="6" /><br />
			You will receive <span id="points_alertpay"><?php print $cms->config['pointsperdollar_alertpay'] * 10; ?></span> points for $<span id="dollars_alertpay">10</span><br />
			(<?php print $cms->config['pointsperdollar_alertpay']; ?> Points for each $)
<script type="text/javascript">
$("#amount_alertpay").change(function()
{
	$("#points_alertpay").html(parseFloat($(this).val()) * <?php print $cms->config['pointsperdollar_alertpay']; ?>);
	$("#dollars_alertpay").html(parseFloat($(this).val()));
});
</script>
			<br />
			<?php if($cms->config['enable_alertpay']) { ?>
			<input type="hidden" name="ap_purchasetype" value="item" />
			<input type="hidden" name="ap_merchant" value="<?php print $cms->config['email_alertpay']; ?>" />
			<input type="hidden" name="ap_itemname" value="Payment for: <?php print $USER['username']; ?>(ID:<?php print $USER['id']; ?>)" />
			<input type="hidden" name="ap_currency" value="USD" />
			<input type="hidden" name="ap_returnurl" value="<?php print $cms->config['websiteurl']; ?>/points.php" />
			<input type="hidden" name="ap_quantity" value="1" />
			<input type="hidden" name="ap_cancelurl" value="<?php print $cms->config['websiteurl']; ?>/points.php" />
			<input type="hidden" name="apc_1" value="<?php print $USER['id']; ?>" />
			<input type="hidden" name="apc_2" value="_MM_PAYMENT" />
			<input type="image" name="ap_image" src="https://www.alertpay.com//PayNow/7459EDD2962843B681B09B0080EFCA97b0en.gif" width="171" height="45" />
			<?php } else { ?>
			<img src="https://www.alertpay.com//PayNow/7459EDD2962843B681B09B0080EFCA97b0en.gif" width="171" height="45" border="0" onmouseover="toolTip(this, 'Currently not not allowed!');" />
			<?php } ?>
			</form>
			<br /><br />
		<?php }?>
		
		<?php if($cms->config['show_alertpay']) { ?>
			<h5><b>Pay</b>Pal</h5>
			<form action="https://<?php print PAYPAL_GATEWAY_URL; ?>/cgi-bin/webscr" method="post" target="paypal">
			<b>$</b><input type="text" id="amount_paypal" name="amount" value="10" size="3" maxlength="6" /><br />
			You will receive <span id="points_paypal"><?php print $cms->config['pointsperdollar_paypal'] * 10; ?></span> points for $<span id="dollars_paypal">10</span><br />
			(<?php print $cms->config['pointsperdollar_paypal']; ?> Points for each $)
<script type="text/javascript">
$("#amount_paypal").change(function()
{
	$("#points_paypal").html(parseFloat($(this).val()) * <?php print $cms->config['pointsperdollar_paypal']; ?>);
	$("#dollars_paypal").html(parseFloat($(this).val()));
});
</script>
			<br />
			<?php if($cms->config['enable_paypal']) { ?>
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="notify_url" value="<?php print $cms->config['websiteurl']; ?>/payments.php?gateway=PayPal" />
			<input type="hidden" name="item_name" value="Payment for: <?php print $USER['username']; ?>(ID:<?php print $USER['id']; ?>)" />
			<input type="hidden" name="custom" value="<?php print $USER['id']; ?>" />
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="business" value="<?php print $cms->config['email_paypal']; ?>" />
			<input type="hidden" name="currency_code" value="USD" />
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" width="122" height="47" />
			<?php }else{ ?>
			<img src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" onmouseover="toolTip(this, 'Currently not not allowed!');" width="122" height="47" />
			<?php } ?>
			</form>
		<?php } ?>
		</div>
	</div>
</div>

<div class="main">
	<div class="main_title">Transactions</div>
	<div class="content">
		<table cellspacing="0" cellpadding="3px" width="100%" border="1px">

			<tr>
				<th>Transaction ID</th>
				<th>Amount</th>
				<th>Payer Email</th>
				<th>Description</th>
				<th>Status</th>
				<th>Date</th>
			</tr>
			
			<tr align="center">
				<th colspan="6">PayPal</th>
			</tr>
			
			<?php
			if(!count($transactions_paypal))
			{
				print "<tr align='center'><td colspan='6'>You haven't made any transactions yet!</td></tr>";
			}
			foreach($transactions_paypal as $transaction)
			{
				//Variables
				$amount = $transaction['amount_gross'];
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
			}?>
			
			<tr align="center">
				<th colspan="6">MoneyBookers</th>
			</tr>
			
			<?php
			if(!count($transactions_moneybookers))
			{
				print "<tr align='center'><td colspan='6'>You haven't made any transactions yet!</td></tr>";
			}
			foreach($transactions_moneybookers as $transaction)
			{
				//Variables
				$amount = $transaction['amount'];
				$txnid = $transaction['transaction_id'];
				$payment_status = $transaction['payment_status'];
				$timestamp = ConvertMysqlTimestamp($transaction['timestamp']);
				
				//If reversed transaction or subtransaction
				if($transaction['status'] == "3")
				{
					$amount = "-".$amount;
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
			
			<tr align="center">
				<th colspan="6">AlertPay</th>
			</tr>
			
			<?php
			if(!count($transactions_alertpay))
			{
				print "<tr align='center'><td colspan='6'>You haven't made any transactions yet!</td></tr>";
			}
			foreach($transactions_alertpay as $transaction)
			{
				//Variables
				$amount = $transaction['amount_gross'];
				$txnid = $transaction['transaction_id'];
				$payment_status = $transaction['payment_status'];
				$timestamp = ConvertMysqlTimestamp($transaction['timestamp']);
				
				//If reversed transaction or subtransaction
				if($transaction['status'] == "3")
				{
					$amount = "-".$amount;
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
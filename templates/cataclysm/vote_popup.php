<?php
$ppgateways = array();
$ppgateways = FetchVoteGateways();
if(count($ppgateways)){
?>
<div id="votepopup_popup" style="color: #000000; width: 100%; height: 100%; position: absolute; left: 0px; top: 100px; z-index: 99999; display: none;">
	<div style="padding: 5px; margin-top: 100px; width: 470px; margin:0 auto; positon: absolute; background: url(images/wow/gradients/dark.jpg) repeat; border: 1px #000000 solid;">
		<div align="center" style="border-bottom: 1px solid;"><a href="javascript:void(0);" class="votepopup_close" style="color: #FF0000;"><b>Close Window.</b></a></div><br />
		<div class="noticebox" align="center"><span>Vote for us!<br />This popup dialog will not appear for 12 hours if you vote on atleast one gateway.<br />Thank you for your support!</span></div>
		<br />
		<?php
		$previousvotes = array(); //If they voted on atleast one gateway. Popup wont show so no need to waste time
		foreach($ppgateways as $vg_data)
		{
			eval($templates->Output("votegateway_bit", false, false, false, true));
		}
		if(!count($ppgateways))
		{
			eval($templates->Output("votegateways_notexists", false, false, false, true));
		}
		?>
		<br /><div align="center" style="border-top: 1px solid;"><a href="javascript:void(0);" class="votepopup_close" style="color: #FF0000;"><b>Close Window.</b></a></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){setTimeout(function(){$("#full").mask();$("#votepopup_popup").fadeIn(500);},5000);});$(".votepopup_close").click(function(){$("#full").unmask();$("#votepopup_popup").fadeOut(250, function(){$(this).hide();});});
</script>
<?php }?>
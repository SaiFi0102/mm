<?php
$ppgateways = array();
$ppgateways = FetchVoteGateways();
if(count($ppgateways)){
?>
<div id="votepopup_modal" style="width: 100%; height: 100%; position: fixed; left: 0px; right: 0px; top: 0px; bottom: 0px; background: url(images/wow/gradients/trans.png) repeat transparent; z-index: 99998; display: none;"></div>
<div id="votepopup_popup" style="color: #000000; width: 100%; height: 100%; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px; background: repeat transparent; z-index: 99999; display: none;">
	<div style="padding: 5px; margin-top: 100px; width: 470px; margin-left: auto; margin-right: auto; positon: absolute; background: url(images/mmopro/light.jpg) repeat; border: 1px #000000 solid;">
		<div align="center" style="border-bottom: 1px solid;"><a href="javascript:void(0);" class="votepopup_close" style="color: #FF0000;"><b>Close Window.</b></a></div><br />
		<div class="noticebox" align="center">Vote for us!<br />This popup dialog will not appear for 12 hours if you vote on atleast one gateway.<br />Thank you for your support!</div>
		<br />
		<?php
		$previousvotes = array(); //If there voted on atleast one gateway. Popup wont show so no need to waste time
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
$(document).ready(function(){$("#votepopup_modal").show();$("#votepopup_popup").fadeIn(500);});
$(".votepopup_close").click(function(){$("#votepopup_modal").hide();$("#votepopup_popup").hide(500);});
</script>
<?php }?>
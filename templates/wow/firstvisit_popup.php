<div id="firstvisit_popup" style="z-index:999999;width:100%;height:100%;position:absolute;left:0px;top:100px;display:none;">
	<div style="margin:0 auto;width:400px;">
		<div style="padding:5px;background:#000000;color:#ffffff;border:1px solid #bbbbbb;border-top:3px solid #bbbbbb;border-bottom:0px;"><b>Click anywhere to close this window</b></div>
		<a href="howtoconnect.php">
		<img src="<?php print $cms->config['websiteurl']; ?>/images/wow/backgrounds/firstvisit_popup.jpg" alt="" width="400" height="600" onmouseover="toolTip(this, 'Click here to check out the step by step guide on how to play on WoWMortal')" />
		</a>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){$("#full").mask();$("#firstvisit_popup").fadeIn(1000);$("html,body").one("click",function(){$("#full").unmask();$("#firstvisit_popup").fadeOut(500);});});
</script>
<?php
switch($REDIRECT_TYPE)
{
	case "success":
		$REDIRECT_TYPE = "successbox";
	break;
	case "error":
		$REDIRECT_TYPE = "errorbox";
	break;
	case "notice":
		$REDIRECT_TYPE = "noticebox";
	break;
	default:
		$REDIRECT_TYPE = "noticebox";
	break;
}
?>
<div id="full">
<div id="redirectcontainer">
<div id="rt-body-surround">
<div class="rt-box-top"><div class="rt-box-top2"></div><div class="rt-box-top3"></div></div>
<div class="rt-box-bg">
<div class="rt-box-bg2">
<div class="rt-box-bg3">
<div id="mainbox">
<div class="main" align="center">
	<div class="main_title">Redirecting!</div>
		<div class="content">
			<h3>You are being redirected... Please wait <?php print round($REDIRECT_INTERVAL/1000, 1); ?> seconds</h3>
			<br />
			<div class="<?php print $REDIRECT_TYPE; ?>"><span><?php print $REDIRECT_MESSAGE; ?></span></div>
		</div>
</div>
</div>
<div class="rt-shadowbar"></div>
</div>
</div>
</div>	
<div class="rt-box-bottom"><div class="rt-box-bottom2"></div><div class="rt-box-bottom3"></div></div>
</div>
<div class="rt-shadowbar"></div>
</div></div>
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
<div class="redirect_container" align="center">
	<div class="left_top"></div><div class="left_content" align="center">
		<div class="main_title">Redirecting!</div>
		<div class="content">
			<h3>You are being redirected... Please wait <?php print round($REDIRECT_INTERVAL/1000, 1); ?> seconds</h3>
			<br />
			<div class="<?php print $REDIRECT_TYPE; ?>"><?php print $REDIRECT_MESSAGE; ?></div>
		</div>
	</div><div class='left_bottom'></div>
</div>
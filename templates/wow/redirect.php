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
<div class="main_redirect" align="center">
	<div class="main_title">Redirecting!</div>
	<div class="content">
		<div align="center">
			<h3>You are being redirected... Please wait <?php print ($REDIRECT_INTERVAL/1000); ?> seconds</h3>
		</div>
		<br />
		<div class="<?php print $REDIRECT_TYPE; ?>"><?php print $REDIRECT_MESSAGE; ?></div>
	</div>
</div>
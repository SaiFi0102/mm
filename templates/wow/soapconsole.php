<div class="main">
	<div class="main_title">How to Connect and Play on Domination WoW</div>
	<div class="content">
		<textarea rows="20" cols="72.5" readonly="readonly" name="textarea" id="textarea"></textarea>
		<input type="text" value="Soap Command(For example .help)" size="95" name="soapcommand" id="soapcommand" style="color: grey; font-style: italic; font-size: 11px;" />
		<input type="button" value="Send" name="soapsend" id="soapsend" />
	</div>
</div>
<div class='left_bottom'></div>
<script type="text/javascript">
$("#soapcommand").one("focus", function()
{
	$(this).attr({value: ""}).css({color: "black", fontStyle: "normal", fontSize: "12px"});
});
</script>
<div class="main"><div class="main_title_top"></div>
	<div class="main_title">BAKAI KAMEHAMEHAAAAAAAAAAAAA QUADROPOOOP .. Smelly!!!</div>
	<div class="content">
		<div id="CONTAINER"><img src="images/loading-small.gif" alt="Loading......" /></div>
<script type="text/javascript">
$(document).ready(function(){
	$.ajax({
		url: "includes/ajax/quadropop_load.php",dataType: "html",data: {gameid: '<?php print $_GET['gameid']; ?>'},type: "POST",
		success: function(msg){
			$("#CONTAINER").html(msg);
		},
		error: function(){
			$("#CONTAINER").html("Error");
		}
	});
});
</script>
		
		<div class="clear"></div>
	</div>
</div>
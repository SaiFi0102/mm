<?php
//Prepare the link without realm id
$url = preg_replace(array("#\?rid=(.+)&#i", "#&rid=(.+)&#i", "#\?rid=(.+)#i", "#&rid=(.+)#i", ), array("&", "&", "", ""), $_SERVER['REQUEST_URI']);
$get = preg_match("#\?(.*?)#i", $url) ?  "&" :  "?";
$url = $url.$get."rid=";
?>
<div class="left_top"></div><div class="left_content">
	<div class="main_title">Realm's List</div>
	<div class="content">
		<h3>Please select a realm from the list below</h3>
		
		<ul>
		<?php
		foreach($REALM as $rid => $rdata)
		{
			print "<li><a href='{$url}{$rid}'>{$rdata['NAME']}</a></li>";
		}
		?>
		</ul>
	</div>
</div>
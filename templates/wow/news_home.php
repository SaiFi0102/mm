<div id="newsloader" style="display:none;"><div class="main">
	<div class="main_title">News</div>
	<div class="content" align="center" style="padding:20px;">
		<br />
		<span style="font-size:20px; font-weight:bold;">Loading!</span><br /><br />
		<img src="images/loading-horizontal.gif" alt="Loading!" />
		<br />...<noscript><br />News will not be loaded if JavaScript is disabled. Please enable javascript or use a newer browser!</noscript>
	</div>
</div><div class='left_bottom'></div></div>

<div id="newsloaderror" style="display:none;"><div class="main">
	<div class="main_title">Error Loading News</div>
	<div class="content"><div class="errorbox" align="center"><h3>There was an error while loading news. Please reload page.</h3></div></div>
</div><div class='left_bottom'></div></div>

<div id="newsnotexists" <?php if(count($news)) print "style='display:none;'"; ?>><div class="main">
	<div class="main_title">News</div>
	<div class="content" style="margin-top:20px;"><h3 style="text-align:center;">There are no new updates.</h3></div>
</div><div class='left_bottom'></div></div>

<div id="newscontainer">
<?php
if(count($news))
{
	foreach($news as $newz)
	{
		$newz['body'] = str_replace("\r\n", "<br />", $newz['body']);
		$newz['body'] = str_replace("\n", "<br />", $newz['body']);
		print '<div class="main"><div class="main_title"><a href="index.php?id=' . $newz['id'] . '">' . $newz['title'] . '</a></div>';
		print '<div class="content">' . $newz['body'] . '<div class="timestamp">Posted on ' . ConvertMysqlTimestamp($newz['date']) . ' by ' . $newz['by'] . '</div></div></div><div class="left_bottom"></div>';
	}
}
?>
</div>
<div class="pagestablecontainer"><div style="display:none;" class="pagestablebox" id="newspagestable"></div></div>

<script type="text/javascript">
function LoadNews()
{
$("#newsloader").PageSort({
JSONFile:"includes/json/news.json.php",ElementsPerPage:5,TotalElements:<?php print $numnews; ?>,OrderColumn:"date",OrderMethod:"DESC",PagesTableContainer:"#newspagestable",
CallBeforeLoad:function()
{
	$("#newscontainer").stop(true,true).hide();$("#newsloader").stop(true,true).fadeIn(500, "easeOutQuad");
},
CallAfterLoad:function(JSONData, totalelements, totalpages)
{
	if(totalelements < 1){$("#newscontainer").hide();$("#newsloader").hide();$("#newsnotexists").fadeIn(1000, "easeOutQuad");}
	else{
	nc_html = '';
	for(x in JSONData.MDElements)
	{
		nc_html += '<div class="main"><div class="main_title"><a href="index.php?id=' + JSONData.MDElements[x]['id'] + '">' + JSONData.MDElements[x]['title'] + '</a></div>';
		nc_html += '<div class="content">' + JSONData.MDElements[x]['body'] + '<div class="timestamp">Posted on ' + JSONData.MDElements[x]['date'] + ' by ' + JSONData.MDElements[x]['by'] + '</div></div></div><div class="left_bottom"></div>';
	}
	$("#newscontainer").html(nc_html);delete nc_html;$("#newsloader").stop(true,true).hide();$("#newscontainer").stop(true,true).fadeIn(1000, "easeOutQuad");}
},
CallOnError:function(XMLHttpRequest, textStatus, errorThrown)
{
	$("#newscontainer").hide();$("#newsloader").hide();$("#newsloaderror").fadeIn(1000, "easeOutQuad");
}
});
}
$(document).ready(function(){LoadNews();});
	
</script>
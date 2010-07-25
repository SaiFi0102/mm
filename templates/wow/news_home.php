<div class="main" id="newsloader">
	<div class="main_title">News</div>
	<div class="content" align="center" style="padding: 20px;">
	
		<br />
		<span style="font-size: 20px; font-weight: bold;">Loading!</span><br /><br />
		<img src="images/loading-horizontal.gif" alt="Loading!" />
		<br />...
		
	</div>
</div>
<div style="display: none;" id="newscontainer"></div>
<div class="pagestablecontainer"><div style="display: none;" class="pagestablebox" id="newspagestable"></div></div>
<div id="newsloaderror" style="display: none;">
	<div class="main">
		<div class="main_title">Error Loading News</div>
		<div class="content"><div class="errorbox" align="center"><h3>There was an error while loading news. Please reload page.</h3></div></div>
	</div>
</div>
<div id="newsnotexists" style="display: none;">
	<div class="main">
		<div class="main_title">News</div>
		<div class="content"><div class="noticebox" align="center"><h3>There are no new updates.</h3></div></div>
	</div>
</div>

<script type="text/javascript">
function LoadNews()
{
	$("#newsloader").PageSort({
		JSONFile: "includes/json/news.json.php",
		ElementsPerPage: 5,
		OrderColumn: "date",
		OrderMethod: "DESC",
		PagesTableContainer: "#newspagestable",
		CallBeforeLoadTotal: function()
		{
			$("#newscontainer").stop(true,true).hide();$("#newsloader").stop(true,true).fadeIn(500);
		},
		CallAfterLoadTotal: function(totalelements, totalpages)
		{
			if(totalelements < 1){$("#newscontainer").hide();$("#newsloader").hide();$("#newsnotexists").fadeIn(1000);}
		},
		CallAfterLoad: function(JSONData)
		{
			nc_html = '';
			for(x in JSONData)
			{
				nc_html += '<div class="main"><div class="main_title"><a href="index.php?id=' + JSONData[x]['id'] + '">' + JSONData[x]['title'] + '</a></div>';
				nc_html += '<div class="content">' + JSONData[x]['body'] + '<div class="timestamp">Posted on ' + JSONData[x]['date'] + ' by ' + JSONData[x]['by'] + '</div></div></div>';
			}
			$("#newscontainer").html(nc_html);delete nc_html;$("#newsloader").stop(true,true).hide();$("#newscontainer").stop(true,true).fadeIn(1000);
		},
		CallOnError: function(XMLHttpRequest, textStatus, errorThrown)
		{
			$("#newscontainer").hide();$("#newsloader").hide();$("#newsloaderror").fadeIn(1000);
		}
	});
}
$(document).ready(function()
{
	LoadNews();
});
	
</script>
<?php
if(!count($newsarr))
{
	print "<div class='left_top'></div><div class='left_content'>
	<div class='main_title'>News and Updates</div>
	<div class='content' align='center'><h3>No new updates</h3></div></div><div class='left_bottom'></div>";
}
else
{
	foreach($newsarr as $news)
	{
		$news['body'] = str_replace("\r\n", "<br />", $news['body']);
		$news['body'] = str_replace("\n", "<br />", $news['body']);
		print "
		<div class='left_top'></div><div class='left_content'>
		<div class='main_title'>
				<div class='right'>
					<a href='javascript:void(0);' id='newsc_{$news['id']}' style='font-size: 20px;'>-</a>
				</div>
				<a href='index.php?id={$news['id']}'>{$news['title']}</a>
		</div>
		
		<div class='content'>
			<div id='newsb_{$news['id']}'>
					{$news['body']}
					<div style='text-align: right;'>Posted on ". ConvertMysqlTimestamp($news['date']) ." by {$news['by']} <a href='index.php?id={$news['id']}'>({$news['commentcount']} Comments)</a></div>
			</div>
		</div></div><div class='left_bottom'></div>";
		?>
		
		<script type="text/javascript">
		$('#newsc_<?php print $news['id']; ?>').click(function(){
			if($(this).text() == "-")
			{
				$(this).hide();
				$('#newsb_<?php print $news['id']; ?>').stop(true, true).slideToggle(function()
				{
					$('#newsc_<?php print $news['id']; ?>').text('+').show();
				});
			}
			else
			{
				$(this).hide().text('-');
				$('#newsb_<?php print $news['id']; ?>').stop(true, true).slideToggle(function(){
					$('#newsc_<?php print $news['id']; ?>').show();
				});
			}
		});
		</script>
		
		<?php 
	}
}	
?>
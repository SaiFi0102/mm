<div class="left_top"></div><div class="left_content">
	<?php
	$news['body'] = str_replace("\r\n", "<br />", $news['body']);
	$news['body'] = str_replace("\n", "<br />", $news['body']);
	print "<div class='main_title'>
			<a href='index.php?id={$news['id']}'>{$news['title']}</a>
	</div>
	
	<div class='content' id='newsb_{$news['id']}'>
		<span>
			{$news['body']}
			<div class='timestamp'>Posted on ". ConvertMysqlTimestamp($news['date']) ." by {$news['by']}</div>
		</span>
	</div>";
	?>
</div>

<div class="left_top"></div><div class="left_content">
	<div class="main_title">Comments</div>
	<div class="content">
			<?php
			if(!count($comments))
			{
				print "<h3>No Comments</h3>";
			}
			else
			{
				foreach($comments as $comment)
				{
					$comment['body'] = str_replace("\r\n", "<br />", $comment['body']);
					$comment['body'] = str_replace("\n", "<br />", $comment['body']);
					print "<h3>{$comment['title']}</h3>
					{$comment['body']}
					<div class='timestamp'>Posted on ". ConvertMysqlTimestamp($comment['date']) ." by {$comment['by']}</div>
					<hr />
					";
				}
			}
			?>
	</div>
</div>
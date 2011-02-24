<div class="main">
	<div class="main_title">PvP Player Statistics</div>
	<div class="content">
		<div class="noticebox">This page shows the PvP statistics of players of the realm Warground only.</div>
		
		<table>
			<tr>
				<th>Rank</th>
				<th>Name</th>
				<th>Total Kills</th>
				<th>Consecutive Kills</th>
				<th>Total Deaths</th>
				<th>Kill Streak</th>
				<th>Kill/Death Ratio</th>
			</tr>
			<?php
			if(!count($top_pvp))
			{
				print "<tr align='center'><td colspan='7'>No records availible at this time.</td></tr>";
			}
			$i = 0;
			foreach($top_pvp as $pvp_data)
			{
				if(empty($pvp_data['charactername']))
				{
					continue;
				}
				$i++;
				if($pvp_data['totaldeaths'] == 0)
				{
					$kdr = 100;
				}
				else
				{
					$kdr = round(($pvp_data['totalkills'] / $pvp_data['totaldeaths']), 2);
				}
				if($pvp_data['killstreak'] == 0)
				{
					$ks = "-";
				}
				else
				{
					$ks = $pvp_data['killstreak']*10 ." kills, Kill Streak";
				}
				print "<tr>
				<td>{$i}</td>
				<td>{$pvp_data['charactername']}</td>
				<td>{$pvp_data['totalkills']}</td>
				<td>{$pvp_data['currentkills']}</td>
				<td>{$pvp_data['totaldeaths']}</td>
				<td>{$ks}</td>
				<td>{$kdr}</td>
				</tr>";
			}
			?>
		</table>
	</div>
</div>
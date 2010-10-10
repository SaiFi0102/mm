<div class="left_top"></div><div class="left_content">
	<div class="main_title">Your Characters</div>
	<div class="content">
		<h3>Below are a list of realms with your characters</h3>
		<?php
		foreach($cdata as $rid => $cdataz)
		{
			print "<fieldset>";
			print "<legend>". $REALM[$rid]['NAME'] ."</legend>";
			
			$CHARACTERLIST_RID = $rid;
			if(!count($cdataz))
			{
				eval($templates->Output("characters_notexists", false, false, false, true));
			}
			foreach($cdataz as $_cdata)
			{
				eval($templates->Output("character_bit", false, false, false, true));
			}
			
			print "</fieldset>";
		}
		?>
	</div>
</div>
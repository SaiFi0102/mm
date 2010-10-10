<div class="left_top"></div><div class="left_content">
	<div class="main_title">Account Management</div>
	<div class="content">
		<div class="successbox">
			<?php
			if($cop) print "Your password";
			if($cof && $cop) print " and ";
			if($cof) print "Your Game Client Flags";
			if($cop && $cof) print " were";
			if(($cop && !$cof) || (!$cop && $cof)) print " was";
			if($cop || $cof) print " successfully changed!";
			
			if(!$cop && !$cof)
			{
				print "Your password and game client flags were not changed!";
			}
			?>
		</div>
	</div>
</div><div class='left_bottom'></div>
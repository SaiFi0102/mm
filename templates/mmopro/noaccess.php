<div class="left_top"></div><div class="left_content">
	<div class="main_title">You do not have access to this page!</div>
	<div class="content">
		<div class="errorbox">
			<?php
			switch($cms->page_access)
			{
				case ACCESS_UNREGISTERED: ?>
			This page cannot be access when logged in. Please logout if you wish to access this page.
			<?php break;
			
				case ACCESS_REGISTERED: ?>
			This page can only be accessed when logged in. Please log in if you wish to access this page.
			<?php break;
			
				default: ?>
			You're not allowed to access this page!
			<?php break;
			} ?>
		</div>
	</div>
</div><div class='left_bottom'></div>
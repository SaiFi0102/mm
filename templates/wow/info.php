		<!-- MAIN Start -->
		<div class="main">
			<div class="main_title">Login or Logout</div>
			<div class="content">
				<?php if(isset($loggedin)) print_r($loggedin); ?>
				<?php if(isset($loggedout)) print_r($loggedout); ?>
			</div>
			
		
			<div class="main_title">Cookies</div>
			<div class="content">
				<?php _var_dump($_COOKIE) ?>
			</div>
			
			
			<div class="main_title">User Class</div>
			<div class="content">
				<?php _print_r($USER); ?>
				<?php _var_dump($uclass->ban); ?>
				<?php _var_dump($uclass->banned); ?>
			</div>
			
			
			<div class="main_title">Online Users</div>
			<div class="content">
				<?php _print_r($onlines); ?>
			</div>
			
			
			<div class="main_title">hehe</div>
			<div class="content">
				<b>LAST QUERY</b> - <?php print $DB->LastQry; ?><hr />
				<b>USER ACCESS</b> - <?php print $USER['access']; ?><br />
				<b>PAGE ACCESS</b> - <?php print $cms->page_access; ?><hr />
				<b>EXECUTION TIME</b> - <?php print $executiontime; ?><hr />
				<b>NUM QUERIES</b> - <?php print $DB->numQueries; ?><hr />
				<b>TIME NOW</b> - <?php print time(); ?><hr />
				<b>TIME 24H+</b> - <?php print (time()+(60*60*12)); ?>
			</div>
		</div><div class='left_bottom'></div>
		<!-- MAIN End -->
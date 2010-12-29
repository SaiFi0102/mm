<?php
exit();
	//Resource File
	$fd = fopen('CSV/AreaTable.dbc.CSV','r');
	if(!$fd) trigger_error('CSV data file "AreaTable.dbc.CSV" is not available', E_USER_ERROR);
	print "<h3>Converting...</h3>";
	while($s = fgets($fd))
	{
		$s = trim($s);
		$a = explode(",",$s);
		$as = $a[0];
		$rs[$as] = $a[11];
	}
	fclose($fd);
	
	//Results File
	$af = fopen('php/AreaTableArray.php', 'w+');
	fwrite($af, "<?php\n\n//Zone ID to Name array\n\$arrZones = array(\n");
	foreach($rs as $key => $val)
	{
		fwrite($af, "\t$key => $val,\n");
	}
	fwrite($af, ");\n\n?>");
	print "<h3>Conversion complete. check 'AreaTableArray.php' for results</h3>";
?>
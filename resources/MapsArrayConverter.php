<?php
exit();
	//Resource File
	$fd = fopen('CSV/Map.dbc.CSV','r');
	if(!$fd) trigger_error('CSV data file "Map.dbc.CSV" is not available', E_USER_ERROR);
	print "<h3>Converting...</h3>";
	while($s = fgets($fd))
	{
		$s = trim($s);
		$a = explode(",",$s);
		$as = $a[0];
		$rs[$as] = $a[6];
	}
	fclose($fd);
	
	//Results File
	$af = fopen('php/MapsArray.php', 'w+');
	fwrite($af, "<?php\n\n//Maps ID to Name array\n\$arrMaps = array(\n");
	foreach($rs as $key => $val)
	{
		fwrite($af, "\t$key => $val,\n");
	}
	fwrite($af, ");\n\n?>");
	print "<h3>Conversion complete. check 'MapsArray.php' for results</h3>";
?>
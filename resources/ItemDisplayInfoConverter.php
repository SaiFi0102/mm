<?php

	//Resource File
	$fd = fopen('CSV/ItemDisplayInfo.dbc.CSV','r');
	if(!$fd) trigger_error('TSV data file "ItemDisplayInfo.dbc.CSV" is not available', E_USER_ERROR);
	print "<h3>Converting...</h3>";
	while($s = fgets($fd))
	{
		$s = trim($s);
		$a = explode(",",$s);
		$as = $a[0];
		$rs[$as] = $a[5];
	}
	fclose($fd);
	
	//Results File
	$af = fopen('PHPArray/ItemDisplayInfoArray.php', 'w+');
	fwrite($af, "<?php\n\n//Item Display ID to IMG array\n\$arrItemImages = array(\n");
	foreach($rs as $key => $val)
	{
		$val = strtolower($val);
		fwrite($af, "\t$key => $val,\n");
	}
	fwrite($af, ");\n\n?>");
	print "<h3>Conversion complete. check 'PHPArray/ItemDisplayInfoArray.php' for results</h3>";
?>
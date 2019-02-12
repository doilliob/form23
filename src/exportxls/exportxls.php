<?php
	// ˆˆ–ˆ€‹ˆ‡€–ˆŸ
	require_once 'Classes/PHPExcel.php';
	include("../connection.php");
	include("lib.php");

	//®«ãç¥­¨¥ á¯¨áª  ¯à¥¯®¤ ¢ â¥«¥©
	$array;
	$query = "SELECT * FROM teachers";
	$result = mysql_query($query) or die("Žè¨¡ª  ¢ë¯®«­¥­¨ï § ¯à®á  1!!!! " + mysql_error());
	if( $result )
		while( $row = mysql_fetch_assoc($result) )
				$array[$row['fio']] = 1;
	
	


	$i=1;
	if($array)
		if( count($array) > 0)
			foreach( array_keys($array) as $teacher )
				{
					echo $i++." - ".$teacher."...[";
					ExportXLSX($teacher);
					echo "--ok--]<br>\n";
					
				};
				
	
	echo "================================================<br>";
	echo "‚‘… „Ž‘’“›… ‚ €‡… …Ž„€‚€’…‹ˆ ‚›ƒ“†…›!!!!";
?>


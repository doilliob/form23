 		
 <?php
	include("connection.php");
	
	
	$query = "select * from students";
	
							
	$result = mysql_query($query);
	
	if($result) {
		
	
		while( $row = mysql_fetch_assoc($result) )
		{
			$arr[$row['spec']][$row['number']]=1;
		};
		
	};
		
	foreach( $arr as $key => $value)
	{
		echo '<div class="cSpec">'.$key;
		foreach($value as $group => $e){
			echo '<div class="cGroup">'.$group.'</div>';	
		};
		echo '</div>';
	};
	mysql_close($connection);
?>
		

  <?php
	include("connection.php");
	
	$query = "select * from teachers";
							
	$result = mysql_query($query);
	
	if($result) {
		while( $row = mysql_fetch_assoc($result) )
		{
			echo "<li>".$row['fio']."</li>";
		};
	};

	mysql_close($connection);
?>

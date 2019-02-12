 <?php
		//include('connection.php');
		
		$query = "select number from students order by number";
		$result = mysql_query($query);
		
		if($result){
			while($row = mysql_fetch_assoc($result)){
				echo '<li>'.$row['number'].'</li>';
			};
		};
		
		mysql_close($connection);
 ?>

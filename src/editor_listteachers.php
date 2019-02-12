 <?php
		include('connection.php');
		
		$query = "select fio from teachers order by fio";
		$result = mysql_query($query);
		
		if($result){
			while($row = mysql_fetch_assoc($result)){
				echo '<option value="'.$row['fio'].'">'.$row['fio'].'</option>';
			};
		};
		
		mysql_close($connection);
 ?>

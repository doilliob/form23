 <?php
	if( isset($_POST['group']) && isset($_POST['mounfth']))
 {
		$group = $_POST['group'];
		list($mounth,$year) = split("-",$_POST['mounth']);
		
		$polugodie = ( ($mounth < 13) and ($mounth > 8) )?1:2;
	
		include('connection.php');

		$query = "select name from lessons 
					where groupnum='".$group."' and polugodie=".$polugodie;
		
		$result = mysql_query($query);
		
		if($result){
			while($row = mysql_fetch_assoc($result)){
				echo '<li>'.$row['name'].'</li>';
			};
		};
		
		mysql_close($connection);
};
 ?>

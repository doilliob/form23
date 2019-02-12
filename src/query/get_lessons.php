  <?php
	include("connection.php");
	
	$group = $_GET['group'];
	$mounth = $_GET['mounth'];
	$year = $_GET['year'];
	
	$semestr = ( ($mounth > 8)&&($mounth<13) )? 1:2;
	
	$query = "select * from lessons where semestr='".$semestr."'
									and  spec=(select spec from students where number='".$group."') 
									and  course=(select course from students where number='".$group."')
									";
	
							
	$result = mysql_query($query);
	
	if($result) {
		
		while( $row = mysql_fetch_assoc($result) )
		{
			echo "<li>".$row['name']."</li>";
		};
	};

	
	mysql_close($connection);

?>


<?php
 //print_r($_POST);
 if( isset($_POST['groupnum']) &&
	 isset($_POST['course']) &&
	 isset($_POST['polugodie']) &&
	 isset($_POST['name'])
	 ){
		include('connection.php'); 
		
		$query = "insert into lessons(name,groupnum,course,polugodie) values (
				  '".$_POST['name']."',
				  '".$_POST['groupnum']."',
				  '".$_POST['course']."',
				  '".$_POST['polugodie']."')";
		
		//echo $query;
		$result = mysql_query($query);
		
		if($result){
			echo "OK";
		}else{
			echo "FAILED";
		};
		 
		 
	    mysql_close($connection);
     }else{
		echo "Не отосланы данные!"; 
	 };
?>

<?php
 include('connection.php');
 
 $from = '251';
 $to = '252';
 $query = "select * from lessons where groupnum='".$from."' ";
 $result = mysql_query($query) or die(mysql_error());
 echo 'Запрос выполнен!';
 if($result){
	echo '!!!';
	$arr;
	while( $row = mysql_fetch_assoc($result) ){
		$arr[ count($arr) ] = "insert into lessons (name,groupnum,course,polugodie) 
		                       values('".$row['name']."','".$to."','".$row['course']."',".$row['polugodie'].")";
	};
	
	if(!empty($arr))
	  foreach($arr as $q){
		  echo $q.'\n<br>';
		  mysql_query($q) or die(mysql_error());	
	  };
 };



?>

<?php
	//header('Content-Type: application/json; charset=utf-8');
	$tbl_name = $_GET['tbl'];
	$connection = mysql_connect('localhost','ucheb','123');
	mysql_select_db('ucheb');
	mysql_query('set names utf8');
	
	$query = "select * from ".$tbl_name;
	$result = mysql_query($query);
	//$r = ();
	$len = 0;
	echo '<fieldset id="book_'.$tbl_name.'">';
	if( $result )
		while( $row = mysql_fetch_assoc($result) ){
			echo '<p>';
			foreach( $row as $key => $value){
				 echo '<div class="
			 };
			$len++;
		};
	mysql_close($connection);


?>

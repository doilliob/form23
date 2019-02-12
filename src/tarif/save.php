 <?php
		 include("../connection.php");
	     $fio = $_POST['fio'];
		 $ekz_budg = $_POST['ekz_budg'];
		 $ekz_nbudg = $_POST['ekz_nbudg'];
		 $tarif_budg = $_POST['tarif_budg'];
		 $tarif_nbudg = $_POST['tarif_nbudg'];
		 $pred_budg = $_POST['pred_budg'];
		 $pred_nbudg = $_POST['pred_nbudg'];
		 $perc_budg = $_POST['perc_budg'];
		 $perc_nbudg = $_POST['perc_nbudg'];
		 $not_budg = $_POST['not_budg'];
		 $not_nbudg = $_POST['not_nbudg'];
	
		 //Проверка есть ли уже такой преподаватель
		 
		 $ifhave = 0;
		 $query = "SELECT count(*) FROM tarif WHERE fio LIKE '%".$fio."%'";
		 //echo $query;
		 $result = mysql_query($query) or die("Ошибка выполнения запроса ".$query." !!! " + mysql_error());
		 //echo "hello=>";
		 $row = mysql_fetch_array($result);
	     //echo $row;
		 if( $row[0] == 1 ){ $ifhave = 1;};
		 
		 
		
		 // Формируем запрос
		 $query = ($ifhave == 0) ? "INSERT INTO tarif VALUES( '".$fio."',
																 ".$ekz_budg.",
																 ".$ekz_nbudg.",
																 ".$tarif_budg.",
																 ".$tarif_nbudg.",
																 ".$pred_budg.",
																 ".$pred_nbudg.",
																 ".$perc_budg.",
																 ".$perc_nbudg.",
																 ".$not_budg.",
																 ".$not_nbudg.")" :
								   "UPDATE tarif SET  ekz_budg=".$ekz_budg.",
														ekz_nbudg=".$ekz_nbudg.",
														tarif_budg= ".$tarif_budg.",
														tarif_nbudg=".$tarif_nbudg.",
														pred_budg=".$pred_budg.",
														pred_nbudg=".$pred_nbudg.",
														perc_budg=".$perc_budg.",
														perc_nbudg=".$perc_nbudg.",
														not_budg=".$not_budg.",
														not_nbudg=".$not_nbudg."
											WHERE fio='".$fio."'";
		//print $query;
		$result = mysql_query($query) or die("Ошибка выполнения запроса ".$query." !!! " + mysql_error());
		echo "ok";
 ?>
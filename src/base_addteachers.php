 <html>
 <head>
	<title>Добавьте преподавателя</title>
	<style>
		table { width:900px; }
		table, table th, table td { border:2px solid #000000;border-collapse:collapse; }
		.row { padding:5px 10px;text-align:left;width:30px;font-size:18px; }
		.firstrow { text-align:center; }
		.row:hover { border:2px solid #A020F0;background-color:#C2E3EC; }
		.secondrow { padding-left:30px; }
		.thirdrow { width:220px; }
		.sendbutton { cursor:pointer;margin:0;font-size:15px;padding:1px;width:220px; }
		.sendbutton:hover { background-color:#FFD700; }
		.addteacher { width:900px;margin:20px 0 0 0;padding:10px;border:2px solid #000000;background-color:#E69559; }
		.addteacher .newfio { width:800px;font-size:18px;border:2px solid #000000; }
	</style>
 </head>
 <body>
	 
  <div align=left>
	 <a href="mainmenu.php">Вернуться в главное меню</a>
  </div>
  <hr>
	 
 <?php
	//Подключаем базу
	include('connection.php');
	
	//Добавить преподавателя
	if( isset($_POST['add']) && isset($_POST['teacher']) ){
		$query = "insert into teachers(fio) values('".$_POST['teacher']."')";
		mysql_query($query) or die(mysql_error());
	};
	
	//Удалить преподавателя
	if( isset($_POST['delete']) && isset($_POST['id']) ){
		$query = "delete from teachers where teachers.id='".$_POST['id']."'";
		mysql_query($query) or die(mysql_error());
	};

	
	//Запрос получить список преподавателей
	$query = "select * from teachers order by fio";
	$result = mysql_query($query);
	
	if( $result ){
		
		//Выводим список преподавателей
		echo '<table align=center>';
		$i=1;
		while( $row = mysql_fetch_assoc($result) ){
			echo '<tr class="row">
					<td class="firstrow">'.($i++).'</td>
					<td class="secondrow">'.$row['fio'].'</td>
					<td class="thirdrow">
						<form action="base_addteachers.php" method=POST >
							<input type=hidden name="id" value="'.$row['id'].'">
							<input class="sendbutton" type=submit name="delete" value="Удалить преподавателя"> 
						</form>
					</td>
				  </tr>';
		};
		echo '</table>
		';
		
		echo '
		<div align=center>
		<div class="addteacher">
			<label>Введите ФИО нового преподавателя</label>
			<form action="base_addteachers.php" method=POST>
				<input class="newfio" type=text name="teacher">
				<input type=submit name="add" value="Добавить преподавателя">
			</form>
		</div>
		</div><br><br><br>';
		
		mysql_close($connection);

	
	};
 
 
 ?>
</body>
</html>

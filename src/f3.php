<html>
<head>
	<meta lang="ru">
	<meta charset="utf-8">
	<!-- Подключаю JQuery и Jquery UI -->
	<script type="text/javascript" src="ui/js/jquery-1.8.0.js"></script>
	<link type="text/css" href="ui/css/sunny/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="ui/js/jquery-ui-1.8.23.custom.min.js"></script>
	
<?php 
//$_POST['teacher']='Айдарова Юлия Викторовна'; 
?>
	
<?php if (isset($_POST['teacher']) ){ ?>
	<link type="text/CSS" rel="stylesheet"  href="styles/f3.css" />
<?php  }else{  ?>
	<script type="text/javascript" src="js/f3.js"></script>
<?php  };  ?>
</head>
<body>
	<div align=left><a href="mainmenu.php">Главное меню</a></div>
	<hr>
	
<?php    
if (isset($_POST['teacher']) ){ 
		$teacher = $_POST['teacher'];
		
		//Подключаемся к базе
		include('connection.php');
		
		//Распечатка данных в Excel
		echo "<div align=left><a href='exportxls/f3-export.php?teacher=".$_POST['teacher']."&' target='_blank' >Распечатать в Excel</a></div><hr>";
	
		echo '<p class="mainTitle" align=center>
				УЧРЕЖДЕНИЕ<br>
				ГОДОВОЙ УЧЕТ ЧАСОВ,<br>
				Данных преподавателями в '.$my_year_now.'/'.$my_year_next.' учебном году
			  </p>';
		echo '<div align=center>Учет часов работы за '.$my_year_now.'/'.$my_year_next.' учебный год</div>';
		echo '<div align=center>Фамилия преподавателя <div class="fio">'.$_POST['teacher'].'</div></div>';
	
		
		//======================================================================
		// ЗАПРОСЫ
		//======================================================================
		$query = "select lesson,groupnum,date_format(hours.day,'%m') as mounth,sum(hours) as summa from hours 
						where   teacher='".$teacher."'
						group by lesson,groupnum,mounth
						order by lesson,groupnum,mounth
						";
						
		$query_nobudg = "select number from students where budg=0";
		
		$query_teacher = "select date_format(substitutions.day,'%m') as mounth,sum(substitutions.hours) as summa from substitutions 
								where   teacher='".$teacher."'
								group by mounth
								order by mounth
						";
									
		$query_subster = "select date_format(substitutions.day,'%m') as mounth,sum(substitutions.hours) as summa from substitutions 
								where   subster='".$teacher."'
								group by mounth
								order by mounth
						";
		//=======================================================================
	
		
		//Выполнение запросов
		$result = mysql_query($query) or die('Ошибка выполнения запроса! '+mysql_error());
		$result_nobudg = mysql_query($query_nobudg) or die('Ошибка выполнения запроса #2! '+mysql_error());
		$result_teacher = mysql_query($query_teacher) or die('Ошибка выполнения запроса #3! '+mysql_error());
		$result_subster = mysql_query($query_subster) or die('Ошибка выполнения запроса #4! '+mysql_error());
              
		//Проверка
		if($result && $result_nobudg && $result_teacher && $result_subster){
			    
			    //Заполняем массивы
			    
			    //Небюджетные группы
			    $nobudg;
			    while($row = mysql_fetch_assoc($result_nobudg)){
					$nobudg[$row['number']] = 1;
				};
			    
			    //Заполняем часы дополнительно
			    $hours_teacher;
				while($row = mysql_fetch_assoc($result_teacher)){
					$arr_teacher[$row['mounth']] = $row['summa'];
				};
				
				//Заполняем часы замена
				$hours_subster;
				while($row = mysql_fetch_assoc($result_subster)){
					$arr_subster[$row['mounth']] = $row['summa'];
				};
				
			    //Заполняем основные часы
			    $arr;
				while($row = mysql_fetch_assoc($result)){
					$arr [$row['lesson']]
						 [$row['groupnum']]
						 [$row['mounth']] = $row['summa'];
				};
				
				//=============================================================
				// ОСНОВНЫЕ МАССИВЫ-МЕСЯЦЫ
				//=============================================================
				$year = 		  array( '09' => 'Сентябрь',
										 '10' => 'Октябрь',
										 '11' => 'Ноябрь',
										 '12' => 'Декабрь',
										 '01' => 'Январь',
										 '02' => 'Февраль',
										 '03' => 'Март',
										 '04' => 'Апрель',
										 '05' => 'Май',
										 '06' => 'Июнь',
										 '07' => 'Июль');
								 
				$semestr = array( '1 семестр' => array(  '09' => 'Сентябрь',
														 '10' => 'Октябрь',
														 '11' => 'Ноябрь',
														 '12' => 'Декабрь'),
								  '2 семестр' => array(  '01' => 'Январь',
														 '02' => 'Февраль',
														 '03' => 'Март',
														 '04' => 'Апрель',
														 '05' => 'Май',
														 '06' => 'Июнь',
														 '07' => 'Июль')
								 );
								 
								 
				//Приведение к окончательной матрице
				$matrix;
				if(!empty($arr))
				foreach ($arr as $lesson => $data1){ 
				//Для каждого предмета
					foreach( $data1 as $group => $data2){
					//Для каждой группы
						foreach($year as $mnum => $mname){
						    //Для каждого месяца в году выставляем часы
							$b = false;
							foreach($data2 as $m => $hours){
							//Для каждой записи часов
								if( $m == $mnum) {
								//Если есть часы в месяце - проставляем
									$matrix[$lesson][$group][$mnum] = $arr[$lesson][$group][$m];
									$b = true;
								};
							};
							//Если нет - выставляем 0
							if(!$b) $matrix[$lesson][$group][$mnum] = 0;	
						};
					};
				};
				
				//==============================================
				// ВЫВОД ТАБЛИЦЫ
				//==============================================
				echo '<table class="mainTable">';				 
				//Вывод предметов
				echo '<tr bgcolor=gray><td>Предмет:</td>';
				if(!empty($matrix))
				foreach ($matrix as $lesson => $data){
					echo '<td colspan='.(count($data)).'>'.$lesson.'</td>';
				};
				//Всего-всего
				echo '<td colspan=5 bgcolor=red>';
				$sum=0;
				if(!empty($matrix))
				foreach ($matrix as $lesson => $data1)
				 foreach ($data1 as $group => $data3)
					foreach($data3 as $mday => $hour) 
						$sum+=$hour;
				echo 'Всего часов:'.$sum;
				echo '</td>';
				echo '</tr>';
				
				//Вывод групп
				echo '<tr bgcolor=gray><td>Месяц/Группа</td>';
				if(!empty($matrix))
				foreach ($matrix as $lesson => $data){
						foreach ($data as $group => $data2)
							echo '<td class="groupNameCell">'.$group.'</td>';
				};
				echo '<td>Бюджет:</td>';
				echo '<td>Внебюджет:</td>';
				echo '<td>Замена:</td>';
				echo '<td>Дополнтельно:</td>';
				echo '<td>Итого:</td>';
				echo '</tr>';
				
				//ВЫВОД ПО СЕМЕСТРАМ
				if(!empty($semestr))
				foreach ($semestr as $semestr_name => $semestr_arr){
					
					//Вывод за семестр
					
					foreach($semestr_arr as $mnum => $mname){
				      echo '<tr>';	
					  echo ' <td>'.$mname.'</td>';
					  
					  //Подсчет строк
					  if(!empty($matrix))
					  foreach($matrix as $lesson => $data1)
						foreach($data1 as $group => $data2){
							echo '<td>';
							echo $matrix[$lesson][$group][$mnum];
							echo '</td>';	
						};
					  
					  //Подсчет бюджет------------------
					  $sum=0;
					  if(!empty($matrix))
					  foreach ($matrix as $lesson => $data1)
						foreach($data1 as $group => $data2)
							if( !isset($nobudg[$group]) )
								$sum+=$matrix[$lesson][$group][$mnum];
					  echo '<td>'.$sum.'</td>';
					  
					  //Подсчет внебюджет--------------
					  $sum=0;
					  if(!empty($matrix))
					  foreach ($matrix as $lesson => $data1)
						foreach($data1 as $group => $data2)
							if( isset($nobudg[$group]) )
								$sum+=$matrix[$lesson][$group][$mnum];
					  echo '<td>'.$sum.'</td>';
					  
					  //Подсчет замена-----------------
					  echo '<td>';
					  echo ( isset($arr_teacher[$mnum]) ) ? $arr_teacher[$mnum] : "0";
					  echo '</td>';
					  
					  //Подсчет дополнительно----------
					  echo '<td>';
					  echo ( isset($arr_subster[$mnum]) ) ? $arr_subster[$mnum] : "0";
					  echo '</td>';
					  
					  //Подсчет итого за месяц по всем группам-----------
					  echo '<td>';
					  $sum=0;
					  if(!empty($matrix))
					  foreach ($matrix as $lesson => $data1)
						foreach($data1 as $group => $data2)
							$sum+=$matrix[$lesson][$group][$mnum];
					  echo $sum;
					  echo '</td>';
					  //-----------
					  echo '</tr>';
					};
					
					
					//Подсчет итогов за семестр
				     echo '<tr bgcolor=yellow>';	
					 echo ' <td>'.$semestr_name.'</td>';
					  
					 //Подсчет строк
					 if(!empty($matrix))
					 foreach($matrix as $lesson => $data1)
						foreach($data1 as $group => $data2){
							//Подсчет за семестр для каждой группы
							echo '<td>';
							$sum = 0;
							foreach ($semestr_arr as $mnum => $mname)
								$sum+=$matrix[$lesson][$group][$mnum];
							echo $sum;
							echo '</td>';	
						};
					  
					  //Подсчет бюджет за семестр------------------
					  $sum=0;
					  if(!empty($semestr) && !empty($matrix))
					  foreach ($semestr_arr as $mnum => $mname)
						foreach ($matrix as $lesson => $data1)
							foreach($data1 as $group => $data2)
								if( !isset($nobudg[$group]) )
									$sum+=$matrix[$lesson][$group][$mnum];
					  echo '<td>'.$sum.'</td>';
					  
					  //Подсчет внебюджет за семестр--------------
					  $sum=0;
					  if(!empty($semestr) && !empty($matrix))
					  foreach ($semestr_arr as $mnum => $mname)
						foreach ($matrix as $lesson => $data1)
							foreach($data1 as $group => $data2)
								if( isset($nobudg[$group]) )
									$sum+=$matrix[$lesson][$group][$mnum];
					  echo '<td>'.$sum.'</td>';
					  
					  //Подсчет замена за семестр-----------------
					  $sum=0;
					  if(!empty($semestr) && !empty($arr_teacher))
					  foreach ($semestr_arr as $mnum => $mname)
						if( isset($arr_teacher[$mnum]) )
						 $sum += $arr_teacher[$mnum];
					  echo '<td>'.$sum.'</td>';
					  
					  //Подсчет дополнительно за семестр----------
					  $sum=0;
					  if(!empty($semestr) && !empty($arr_subster))
					  foreach ($semestr_arr as $mnum => $mname)
						if( isset($arr_subster[$mnum]) )
						 $sum += $arr_subster[$mnum];
					  echo '<td>'.$sum.'</td>';
					  
					  //Подсчет итого за месяц по всем группам за семестр-----------
					  echo '<td>';
					  $sum=0;
					  if(!empty($semestr) && !empty($matrix))
					  foreach ($semestr_arr as $mnum => $mname)
						foreach ($matrix as $lesson => $data1)
							foreach($data1 as $group => $data2)
								$sum+=$matrix[$lesson][$group][$mnum];
					  echo $sum;
					  echo '</td>';
					  //-----------
					  echo '</tr>';
					//*****************
					
				};
			
				//Итого за год по группе
				echo '<tr><td>Итого:</td>';
				if(!empty($matrix))
				foreach ($matrix as $lesson => $data1)
					foreach($data1 as $group => $data2){
						echo '<td>';
						$sum=0;
						foreach ($year as $mnum => $mname)
							$sum += $matrix[$lesson][$group][$mnum];
						echo $sum;
						echo '</td>';
					};
				
				//Итого за год бюджет
				$sum=0;
				if(!empty($matrix))
				foreach ($year as $mnum => $mname)
					foreach ($matrix as $lesson => $data1)
						foreach($data1 as $group => $data2)
							if( !isset($nobudg[$group]) )
								$sum += $matrix[$lesson][$group][$mnum];
			    echo '<td>'.$sum.'</td>';
			    
			    //Итого за год внебюджет
				$sum=0;
				if(!empty($matrix))
				foreach ($year as $mnum => $mname)
					foreach ($matrix as $lesson => $data1)
						foreach($data1 as $group => $data2)
							if( isset($nobudg[$group]) )
								$sum += $matrix[$lesson][$group][$mnum];
			    echo '<td>'.$sum.'</td>';
			    
			    //Итого за год замена
			    $sum=0;
				if(!empty($arr_teacher))
				foreach ($year as $mnum => $mname)
					if( isset($arr_teacher[$mnum]) )
						 $sum += $arr_teacher[$mnum];
				echo '<td>'.$sum.'</td>';
			    
			    //Итого за год дополнительно 
			    $sum=0;
				if(!empty($arr_subster))
				foreach ($year as $mnum => $mname)
					if( isset($arr_subster[$mnum]) )
						 $sum += $arr_subster[$mnum];
				echo '<td>'.$sum.'</td>';
				
			    //Всего часов за год
			    $sum=0;
				if(!empty($matrix))
				foreach ($matrix as $lesson => $data1)
				 foreach ($data1 as $group => $data3)
					foreach($data3 as $mday => $hour) 
						$sum+=$hour;
				echo '<td>'.$sum.'</td>';
			    
				echo '</tr>';
				echo '</table>';
				echo '<hr><div align=left><a href="f3.php">Прсмотреть форму для другого преподавателя</a></div>';
		};
		mysql_close($connection);
   	 }else{ ?>	

		<div align=center>
			<form action="f3.php" method=POST>
				<p>Выберите преподавателя:</p>
				<select name="teacher">
					<?php include('editor_listteachers.php'); ?> 
				</select>
				<input type=submit value="Выбрать">
			</form>
		</div>
<?php	}; ?>
</body>
</html>

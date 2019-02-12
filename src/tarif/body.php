 <?php
	
	include("../connection.php.");
	
	// Строка для поиска
	$search_term = ( isset($_POST['search']) ) ?   $_POST['search'] : '';
	
	
	
	//===================================
	// Получаем список преподавателей
	//===================================
	$all_teachers;
	//-----------------------------------
	$query = "SELECT fio FROM teachers WHERE fio LIKE '%".$search_term."%'";
	$result = mysql_query($query) or die("Ошибка получения списка преподавателей! " + mysql_error());
	if ($result)
	while( $row = mysql_fetch_assoc($result) )
	{
		$all_teachers[ $row['fio'] ] = null;
	};//---------------------------------
	//Проверка
	if(count($all_teachers) == 0) die;
	
	//=============================================
	// Добавляем в хэш данные о тарификации
	//=============================================
	$query = "SELECT * FROM tarif WHERE fio LIKE '%".$search_term."%'";
	$result = mysql_query($query) or die("Ошибка получения данных о тарификации!" + mysql_error());
	if ($result)
	while($row = mysql_fetch_assoc($result))
	{
		$arr;
		foreach( $row as $key => $value )
		{
			$arr[$key] = $value;
		};
		$all_teachers[ $row['fio'] ] = $arr;
	};
	
	//=============================================
	// Выводим результаты
	//=============================================
	$names = array(
		'ekz_budg' => 'Экзаменационные часы:',
		'ekz_nbudg' => 'Экзаменационные часы(внебюджет):',
		'tarif_budg' => 'Тарификационные часы:',
		'tarif_nbudg' => 'Тарификационные часы(внебюджет):',
		'pred_budg' => 'Изменение преднагрузки:',
		'pred_nbudg' => 'Изменение преднагрузки(внебюджет):',
		'perc_budg' => '5% праздничных:',
		'perc_nbudg' => '5% праздничных(внебюджет):',
		'not_budg' => 'Не выполнено:' ,
		'not_nbudg' => 'Не выполнено(внебюджет):');
		
	foreach ( array_keys($all_teachers) as $fio ){
		$arr = $all_teachers[$fio];
		
		echo '<div class="Teacher"> 
				<div class="TeacherName"><h2>'.$fio.'</h2></div> 
				<input type=hidden id="fio" value="'.$fio.'"></input>
				<table border=1 class="TProperties">';
			foreach( array_keys($names) as $k ){
				echo '<tr>
						<td>
							<label> '.$names[$k].' </label>
						</td>
						<td>
							 <input type=text 
									class="TextField" 
									id="'.$k.'"
									value="'.( ($arr == null) ? 0 : $arr[$k] ).'">
							 </input>
						</td>
					   </tr>';
			};
		echo '<tr><td></td><td><div align="center"><input type=button class="Percent" value="Подсчитать 5%"></div></td></tr>
			  </table>
			  <input type=button class="Save" value="Сохранить">
		</div>';
	};
	
 
 
 ?>
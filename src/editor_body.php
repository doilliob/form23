 <?php 
#$_POST['group'] = '0111';
#$_POST['mounth']= '09-2015';
 //Если не указаны в запросе группа и месяц-год
 if( isset($_POST['group']) && isset($_POST['mounth']))
 {
	// Подключаемся к базе данных
	include("connection.php");
	
	// Получаем переменные из запроса
	$group = $_POST['group'];
	list($mounth,$year) = split("-",$_POST['mounth']);
	
	
	// Создаем запросы к базе данных
	// На часы преподавателей
	$query_teacher_hours = "select * from hours 
							where date_format(hours.day,'%m-%Y')='".$mounth."-".$year."' 
								   and hours.groupnum='".$group."';";
	// На часы заместителей					
	$query_substers_hours = "select * from substitutions 
							 where date_format(substitutions.day,'%m-%Y')='".$mounth."-".$year."' 
								   and substitutions.groupnum='".$group."';";
	// Выполняем запросы к базе данных									
	$result_teacher_hours  = mysql_query($query_teacher_hours);
	$result_substers_hours = mysql_query($query_substers_hours);
	
	// Массив для хранения структуры часов
	$arr =  array();
	
	// Если какой-либо запрос вернул результат
	if(	$result_teacher_hours || $result_substers_hours ) {
		
		// Если есть часы преподавателей
		if($result_teacher_hours)
		while( $row = mysql_fetch_assoc($result_teacher_hours) )
		{
		    // Заполняем структуру массива
			$arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['brignum']  ]
				[$row['view']	  ]
				[$row['day']      ] = $row['hours'];
				
		};
		
		// Если есть часы заместителей
		if($result_substers_hours)
		while( $row = mysql_fetch_assoc($result_substers_hours) )
		{
		   // Заполняем структуру массива
		   
		   $arr [$row['lesson']   ]
				[$row['teacher']  ]
				[$row['brignum']  ]
				[$row['view']     ]
				[$row['day']      ]
				['hours'] = $row['hours'];
				
				
			$arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['brignum']  ]
				[$row['view']     ]
				[$row['day']      ]
				['subster'] = $row['subster'];
				
		};
		
		
		// Если массив заполнен
		if( isset($arr) && !empty($arr))
		foreach( $arr as $lesson => $data1 )
		{
			echo '<div class="lesson">';
				echo '<div class="lessonName">'.$lesson.'</div>
					  <div class="cAddTeacher">Добавить преподавателя</div>
					  <div class="cDelLesson">Удалить предмет</div>
				';
				foreach( $data1 as $teacher => $data2)
				{
					echo '<div class="teacher">
					';
						echo '<div class="Selector">+</div>
							  <div class="teacherName">'.$teacher.'</div>
							  <div class="cAddCalendar">Добавить Теорию/Практику</div>
							  <div class="cDelTeacher">Удалить преподавателя</div>
						';
						foreach( $data2 as $brignum => $data3){
							foreach( $data3 as $view => $data4){
								
								echo '<div class="Calendar" brignum="'.$brignum.'" view="'.$view.'">
								';
								echo '<div class="View">'.$view.'</div>
								';
								echo '<div class="brigNum">'.(($brignum=='Вся группа')?$brignum:'Бригада '.$brignum).'</div>
									  <p> 
								';
									
								// Вычисляем количество дней в месяце
								$num = cal_days_in_month(CAL_GREGORIAN, $mounth, $year);
								// Обнуляем сумму часов
								$sum=0;
								// Пробегаем по всем дням
								for($j=1; $j<=$num; $j++)
								{
									$trues=0;
									$theday="";
									
									// Пробегаем по всем датам у данного преподавателя и предмета
									foreach( $data4 as $day => $cur_hours ){
										list($y,$m,$d) = split("-",$day);
										$d = date('j',mktime(0,0,0,$m,$d,$y));
										if($d==$j){
											$trues=1;
											$theday=$day;
											$hours = (is_array($cur_hours)) ? 
														intval($arr[$lesson][$teacher][$brignum][$view][$day]['hours']) : 
														intval($cur_hours) ;
										};
									};
									
									// Если за этот день есть часы
									if($trues==1){
									    // Определяем вид часов - заместителя или преподавателя
										echo (is_array($arr[$lesson][$teacher][$brignum][$view][$theday])) ?
											    '<input class="calSubDay" 
															  id="'.$j.'"
															  value="'.$arr[$lesson][$teacher][$brignum][$view][$theday]['hours'].'"
															  subs="'.$arr[$lesson][$teacher][$brignum][$view][$theday]['subster'].'"
												readonly>
												' :
											    '<input class="calFullDay" id="'.$j.'" value="'.$hours.'" readonly>
												';
										// Вычисляем сумму
										$sum+=$hours;
									}else{
											echo '<input class="calInputDay" value="'.$j.'" readonly>
											';
									};
								};	
								echo '<input class="calAwerall" value="Итого: '.$sum.' ч" readonly>
								      <input class="calDelete" value="X" readonly>
								    </p>
							   </div>';
							}; 
						};
					echo '</div>';
				};
			echo '</div>';
		};
			
	};
	
 
 };
 ?>

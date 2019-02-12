		
 <?php
	include("connection.php");
	
	$group = $_GET['group'];
	$mounth = $_GET['mounth'];
	$year = $_GET['year'];
	
	$query = "select * from hours where date_format(hours.day,'%m-%Y')='".$mounth."-".$year."' 
									and hours.groupnum='".$group."';";
	
							
	$result = mysql_query($query);
	
	if($result) {
		
		while( $row = mysql_fetch_assoc($result) )
		{
			$arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['brignum']  ]
				[$row['ispractic']]
				[$row['day']      ] = $row['hours'];
		};
		
		#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		foreach( $arr as $lesson => $data1 )
		{
			echo '<div class="lesson">';
				echo '<div class="lessonName">'.$lesson.'</div>
					  <a href=# class="cAddTeacher">Добавить преподавателя</a>
					  <a href=# class="cDelLesson">Удалить предмет</a>
				';
				foreach( $data1 as $teacher => $data2)
				{
					echo '<div class="teacher">
					';
						echo '<div class="teacherName">
							  <img class="ShowHideImage" src="http://localhost/images/plus.png">'.$teacher.'</div>
							  
						';
						foreach( $data2 as $brignum => $data3){
							foreach( $data3 as $ispractic => $data4){
								echo '<div class="Calendar" brignum="'.$brignum.'" ispractic="'.$ispractic.'">
								';
								echo '<input class="isTheory" 
											 value="'.(($ispractic==1)?'Практика':'Теория').'" readonly>
								';
								echo '<input class="brigNum" value="Бригада '.$brignum.'" readonly>
									    <input class="calDay" value="Пн" readonly>
										<input class="calDay" value="Вт" readonly>
										<input class="calDay" value="Ср" readonly>
										<input class="calDay" value="Чт" readonly>
										<input class="calDay" value="Пн" readonly>
										<input class="calDay" value="Сб" readonly>
										<input class="calDay" value="Вс" readonly>
										';
									
								$d = date("w", mktime(0, 0, 0, $mounth, 1, $year));
								$d = ($d==0)?7:$d;
								for($i=0; $i<$d; $i++)
								{
									echo '<input class="nullDay" readonly>
									';
								};
								$num = cal_days_in_month(CAL_GREGORIAN, $mounth, $year);
								
								$sum=0;
								for($j=1; $j<=$num; $j++)
								{
									$trues=0;
									foreach( $data4 as $day => $hours ){
										list($y,$m,$d) = split("-",$day);
										$d = date('j',mktime(0,0,0,$m,$d,$y));
										if($d==$j)
											$trues=1;
									};
									if($trues==1){
										echo '<input class="calFullDay" value="'.$hours.'" readonly>
											';
										$sum+=$hours;
									}else{
										echo '<input class="calInputDay" value="'.$j.'" readonly>
											';
									};
								};	
								echo '<input class="calAwerall" value="Итого: '.$sum.' ч">';
								echo '</div>';
							}; 
						};
					echo '<hr>';
					echo '<a href=# class="cAddCalendar">Добавить Теорию/Практику</a>';
					echo '<a href=# class="cDelTeacher">Удалить преподавателя</a>';
					echo '</div>';
				};
			echo '</div>';
		};
			
		#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	};
	
 ?>

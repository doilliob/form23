 <html>
	
<head>
	<meta lang="ru">
	<meta charset="utf-8">
	<!-- Подключаю JQuery и Jquery UI -->
	<script type="text/javascript" src="ui/js/jquery-1.8.0.js"></script>
	<link type="text/css" href="ui/css/sunny/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="ui/js/jquery-ui-1.8.23.custom.min.js"></script>
	<!-- Мои скрипты -->

<?php if(  isset($_POST['group']) && isset($_POST['mounth']) ){ ?>
	   <link type="text/CSS" rel="stylesheet"  href="styles/f2.css" />
	
</head>
	
<body>
	<a href="f2.php">Назад</a>
	<hr>
	<?php
	   //Расставляем переменные
	  list($mounth,$year) = split("-",$_POST['mounth']);
	  $group = $_POST['group'];
	   
	   // Работаем с БД
	   include("connection.php");
		
	   $query = "select spec,spec_id,course from students where number='".$group."'";
	   $result = mysql_query($query);
	   $row = mysql_fetch_assoc($result);
	   $spec = $row['spec'];
	   $spec_id = $row['spec_id'];
	   $course = $group[1];
	
	?>
	
	<table width=100%>
	<tr>
	  <td>Ведомость учета теоретических часов учебной работы преподавателей</td>
	  <td width=100px>Форма2</td>
	</tr>
	<tr>
		<td>
			
		<?php 
				echo 'Специальность '.$spec_id.',"'.$spec.'"  '.$course.' курс '.$group.' группа ';
				$my_learn_year = $my_year_now . "-" . $my_year_next;
				switch ($mounth){
					case '09':echo "сентябрь ".$my_learn_year." учебный год.";break;
					case '10':echo "октябрь ".$my_learn_year." учебный год.";break;
					case '11':echo "ноябрь ".$my_learn_year." учебный год.";break;
					case '12':echo "декабрь ".$my_learn_year." учебный год.";break;
					case '01':echo "январь ".$my_learn_year." учебный год.";break;
					case '02':echo "февраль ".$my_learn_year." учебный год.";break;
					case '03':echo "март ".$my_learn_year." учебный год.";break;
					case '04':echo "апрель ".$my_learn_year." учебный год.";break;
					case '05':echo "май ".$my_learn_year." учебный год.";break;
					case '06':echo "июнь ".$my_learn_year." учебный год.";break;
					case '07':echo "июль ".$my_learn_year." учебный год.";break;
				};
		?>
			
		</td>
	</tr>
</table>

<br><br><br>

<table class="mainTable" border=0 padding=0 margin=0>
	<tr bgcolor=#D0D051>
		<td width=20px class="numer">№</td>
		<td class="lesson" align=center height=60px>Дисциплина</td>
		<td class="teacher">Ф.И.О. Преподавателя</td>

 <?php
 
 
	//Расставляем чиселки
	$num = cal_days_in_month(CAL_GREGORIAN, $mounth, $year+1);				
	for($j=1; $j<=$num; $j++)
	{
		if( date("w", mktime(0, 0, 0, $mounth, $j, $year)) == 0){
			echo '<td class="numdayGray" bgcolor=gray width=20px align=center>'.$j.'</td>';
		}else{
			echo '<td class="numday" width=20px align=center>'.$j.'</td>';
		};
	};
	echo '<td  width=40px></td></tr>';
	
	
	
	$query = "select * from hours where date_format(hours.day,'%m-%Y')='".$mounth."-".$year."' 
									and hours.groupnum='".$group."';";
	$result = mysql_query($query);
	if($result){
	  //Проходим по всему и составляем массив	
	  while( $row = mysql_fetch_assoc($result) )
		{
			list($y,$m,$d) = split("-",$row['day']);
			$d = (substr($d,0,1) == 0) ? substr($d,1):$d;
			$arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['brignum']  ]
				[$row['view']]
				[$d] = $row['hours'];
		};
	
	  //******************************************************
		//Набираем заместителей
	    $query = "select * from substitutions where date_format(substitutions.day,'%m-%Y')='".$mounth."-".$year."' 
									and substitutions.groupnum='".$group."';";
		$result = mysql_query($query);
		if($result){
		  //Проходим по всему и составляем массив	
		  while( $row = mysql_fetch_assoc($result) )
			{
				list($y,$m,$d) = split("-",$row['day']);
				$d = (substr($d,0,1) == 0) ? substr($d,1):$d;
			    $subs[$row['lesson']   ]
				  	 [$row['teacher']  ]
					 [$row['brignum']  ]
					 [$row['view']]
					 [$d] = $row['hours'];
				
				//echo '<p>'.$row['lesson'].' '.$row['teacher'].' '.$row['brignum'].' '.$row['view'].' '.$d.' '.$row['hours'];	
				
			   //Если по какому-либо предмету есть заместители но нет основных часов 
			   if( !isset($arr[$row['lesson']][$row['teacher']][$row['brignum']][$row['view']][$d]) ){
				   $arr[$row['lesson']][$row['teacher']][$row['brignum']][$row['view']][$d] = 0;
			   };
	
			};
		 };
		 //print_r($subs);
	  //******************************************************
	  
	  //Рисуем таблицу
	  $nummer=1;
	  if( isset($arr) && !empty($arr))
	  foreach ($arr as $lesson => $data1){
		$lesson_for_subs = $lesson;
		foreach ($data1 as $teacher => $data2){
			foreach ($data2 as $brignum => $data3){
				foreach ($data3 as $view => $data4){
					//Рисуем ряд
					
					//ФИО к сокращенному виду
					mb_internal_encoding("UTF-8"); 
					list($fam,$im,$ot) = split(' ',$teacher);
					$teacher_short = $fam.' '.mb_substr($im,0,1,"UTF-8").'. '.mb_substr($ot,0,1,"UTF-8").'.';
					
					echo '<tr class="TRow">
								<td class="numer">'.$nummer++.'</td>
								<td class="lesson">'.$lesson.'</td>
							    <td class="teacher">'.$teacher_short.'</td>';
					$lesson=' ';
					$sum=0;
					
					//Расставляем чиселки
					$num = cal_days_in_month(CAL_GREGORIAN, $mounth, $year);				
					for($j=1; $j<=$num; $j++)
					{			
						$cont="";
						$issubs = false;
						
						foreach($data4 as $day => $hours){
							if( $j == $day ){ $cont=$hours;$sum+=$hours;}
						};
						
						//Если есть замещенные часы на этого преподавателя
						//echo '<p>"'.$lesson_for_subs.'" "'.$teacher.'" "'.$brignum.'" "'.$view.'" "'.$j.'"';
						//print_r($subs);
						//echo '<p>'.$subs[$lesson_for_subs][$teacher][$brignum][$view][$j];
						
						
						if( isset($subs[$lesson_for_subs][$teacher][$brignum][$view][$j]) ){
								$h = $subs[$lesson_for_subs][$teacher][$brignum][$view][$j];
								$cont=$h;$sum+=$h;
								$issubs=true;
						};
						
						if( date("w", mktime(0, 0, 0, $mounth, $j, $year)) == 0){
								echo '<td class="numdayGray" '.(($issubs)?' bgcolor=yellow ':'bgcolor=gray ').' width=20px align=center>'.$cont.'</td>';
						}else{
								echo '<td class="numday" '.(($issubs)?' bgcolor=yellow ':'').' width=20px align=center>'.$cont.'</td>';
						};
							
					};
					//Завершаем табличку
					echo '<td class="numres" align=center width=40px>'.$sum.'</td>
							</tr>';
				};
			};
		};  
	  };	
		
		
	
	};
	mysql_close($connection);
	echo "</table>";
 ?>
 
 <br><br>
 <p>Заведующая учебной частью: ________________________/Фамилия И.О./</p>
 </body>
 </html>

<?php }else{ ?>
 <link type="text/CSS" rel="stylesheet"  href="styles/pager.css" />
 <script type="text/javascript" src="js/f2.js"></script>	
</head>
	
<body>
	 <!-- Верхнее меню --------------------------------->
		<ul class="Pager">
			<li class="chooseGroup">Группа:</li>
			<li class="chooseMounth">Месяц:</li>
			<li class="chooseExit">Выход</li>
		</ul>
		<?php include('connection.php'); ?>
		<!-- Список групп (получаем из скрипта) ----------->	
		<ul class="listGroups">
			<?php include('editor_listgroups.php'); ?>
		</ul>
		<!-- Список месяцев ------------------------------->
		<ul class="listMounth">
				<li id="09-<?php echo $my_year_now; ?>">Сентябрь, <?php echo $my_year_now; ?>г.</li>
				<li id="10-<?php echo $my_year_now; ?>">Октябрь, <?php echo $my_year_now; ?>г.</li>
				<li id="11-<?php echo $my_year_now; ?>">Ноябрь, <?php echo $my_year_now; ?>г.</li> 
				<li id="12-<?php echo $my_year_now; ?>">Декабрь, <?php echo $my_year_now; ?>г.</li> 
				<li id="01-<?php echo $my_year_next; ?>">Январь, <?php echo $my_year_next; ?>г.</li> 
				<li id="02-<?php echo $my_year_next; ?>">Февраль, <?php echo $my_year_next; ?>г.</li> 
				<li id="03-<?php echo $my_year_next; ?>">Март, <?php echo $my_year_next; ?>г.</li> 
				<li id="04-<?php echo $my_year_next; ?>">Апрель, <?php echo $my_year_next; ?>г.</li> 
				<li id="05-<?php echo $my_year_next; ?>">Май, <?php echo $my_year_next; ?>г.</li> 
				<li id="06-<?php echo $my_year_next; ?>">Июнь, <?php echo $my_year_next; ?>г.</li> 
				<li id="07-<?php echo $my_year_next; ?>">Июль, <?php echo $my_year_next; ?>г.</li> 
		</ul>
		
		<!-- Форма для отправки настроек ------------------>
			<form class="Settings" action="f2.php" method="POST">
				<input type="input" id="group"  name="group" value="">
				<input type="input" id="mounth" name="mounth" value="">
			</form>
			
		<br><br>
		<div align=center>
			<h1>Вберите пожалуйста группу и месяц <br> для создания отчета по Форме №2</h1>
		</div>
		
</body>
</html>

<?php }; ?>


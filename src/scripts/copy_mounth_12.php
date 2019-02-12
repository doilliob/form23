  <?php 
        //=======================================================  
	// Копирует расписание с одного месяца на другой (только учителей и уроки) 
	//=======================================================
 
	//include("connection.php");
	$connection = mysql_connect("localhost","ucheb","123") or die(mysql_error());
	mysql_select_db("ucheb2");
	mysql_query("SET NAMES UTF8");


function makeCopy($mounth_from,$mounth_to,$year,$group) 
{	
	$arr;
	$query = "select * from hours where date_format(hours.day,'%m-%Y')='".$mounth_from."-".$year."' and groupnum='".$group."'";
	$query_sub = "select * from substitutions where date_format(substitutions.day,'%m-%Y')='".$mounth_from."-".$year."' and groupnum='".$group."'";
	//echo $query."<br>";									
	$result = mysql_query($query);
	//print "**".mysql_num_rows($result)."**<br>";
	$result_sub = mysql_query($query_sub);
	//print "**".mysql_num_rows($result_sub)."**<br>";
	
	if($result || $result_sub) {
		
		$arr;
		//$i=0;
		//Отбираем все часы по расписанию
		if($result)
		while( $row = mysql_fetch_assoc($result) )
		{
			$arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['groupnum'] ]
				[$row['brignum']  ]
				[$row['view']]  =  1; //$i++;
		};
		
		//Отбираем все часы по заместителям
		if($result_sub)
		while( $row = mysql_fetch_assoc($result_sub) )
		{
		    $arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['groupnum'] ]
				[$row['brignum']  ]
				[$row['view']]  =  1; //$i++;
		};

		//Массив запросов
		$query =  array();
		
		
		//Генерация запросов
		if( isset($arr) && !empty($arr)) 
		foreach( $arr as $lesson => $data1 )
		  foreach( $data1 as $teacher => $data2)
		   foreach( $data2 as $groupnum => $data3)
			foreach( $data3 as $brignum => $data4)
			  foreach( $data4 as $view => $num) 
				$query[ count($query) ] = 
					"insert into hours(lesson,teacher,groupnum,brignum,view,day,hours) values('".$lesson."','".$teacher."','".$groupnum."','".$brignum."','".$view."','".$year."-".$mounth_to."-01',1)";
			
		
		if( count($query) > 0 ){
			// Выполняем транзакцию
			mysql_query("SET autocommit=0");
			mysql_query("start transaction");
			try {  
					//Удаляем все из следующего месяца
					mysql_query("delete from hours where date_format(hours.day,'%m-%Y')='".$mounth_to."-".$year."'");
				   //Выполняем по одной запросы
				   foreach($query as $q){
					   mysql_query($q) or die(mysql_error());
				   };
				   
				   //Сохраняем изменения
				   mysql_query("commit")  or die(mysql_error());
				   echo (count($query)-1)."] запросов выполнено</p>";
			
			}catch( Exception $e ){
				//Отменяем транзакцию, если ошибка
				echo "OH NO!!!!!";
				mysql_query("rollback");
			};
			
			
		}else{  echo "0] запросов выполнено</p>"; };
								
    }else{  echo "0] запросов выполнено</p>"; };
	
	unset ($arr);
	unset ($query);
}; // КОНЕЦ ФУНКЦИИ
	
	
//==========================================================
//
//				ГЛАВНАЯ ПРОГРАММА
//
//==========================================================	
	
	// Месяц с которого копируем расписание
	$mounth_from='03';
	// Месяцы в которые копируем расписание
	$mounth_to = array('04','05','06');
	// Год в пределах которого копируем расписание
	$year = '2013';
	
	// Получаем список всех существующих групп
	$grps = array();
	$res = mysql_query("select * from students");
	
	//Отбираем группы по шаблону
	if( $res )
		while($r = mysql_fetch_assoc($res))
			if( (substr($r['number'],0,1) == '1') or
			//  Шаблоны имени:
			//	(substr($r['number'],0,1) == '1') or
			//  (substr($r['number'],0,1) == '1') or
				(substr($r['number'],0,1) == '2') ) 
					array_push($grps,$r['number']) ;
					
	//Для каждой отобранной группы
	foreach ($grps as $tmp)
	// Для каждого месяца
		foreach ( $mounth_to as $m )
		{		
			// Копируем месяц
			echo "<p> Расписание для группы ".$tmp." за месяц ".$m.": [";
			makeCopy($mounth_from,$m,$year,$tmp);
		};
		
	
	
	mysql_close($connection);
	
	
 ?>


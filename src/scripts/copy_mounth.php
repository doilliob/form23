  <?php 
        //=======================================================  
	// Копирует расписание с одного месяца на другой (только учителей и уроки) 
	//=======================================================

	include("connection.php");
	
	$mounth_from='02';
	$mounth_to='04';
	$year = '2013';
	//$group = '103';
	
	
	$query = "select * from hours where date_format(hours.day,'%m-%Y')='".$mounth_from."-".$year."'"; // and groupnum='".$group."'";
	$query_sub = "select * from substitutions where date_format(substitutions.day,'%m-%Y')='".$mounth_from."-".$year."' "; //and groupnum='".$group."'";
										
	$result = mysql_query($query);
	$result_sub = mysql_query($query_sub);
	
	if($result && $result_sub) {
		
		//Отбираем все часы по расписанию
		while( $row = mysql_fetch_assoc($result) )
		{
			$arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['groupnum'] ]
				[$row['brignum']  ]
				[$row['view']]  =  1;
		};
		//Отбираем все часы по заместителям
		while( $row = mysql_fetch_assoc($result_sub) )
		{
		    $arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['groupnum'] ]
				[$row['brignum']  ]
				[$row['view']]  =  1;
		};

		
		//Массив запросов
		$query =  array();
		//Удаляем все из следующего месяца
		//$query[0] = "delete from hours where date_format(hours.day,'%m-%Y')='".$mounth_to."-".$year."'";
		
		//Проверка----------------------------------
		if( isset($arr) && !empty($arr)) //---------
		foreach( $arr as $lesson => $data1 )
		  foreach( $data1 as $teacher => $data2)
		   foreach( $data2 as $groupnum => $data3)
			foreach( $data3 as $brignum => $data4)
			  foreach( $data4 as $view => $num)
				$query[ count($query) ] = 
					"insert into hours(lesson,teacher,groupnum,brignum,view,day,hours) values('".$lesson."','".$teacher."','".$groupnum."','".$brignum."','".$view."','".$year."-".$mounth_to."-01',1)";
		
		if( count($query) > 1 ){
			
			mysql_query("SET autocommit=0");
			mysql_query("start transaction");
			try {  
				   //Выполняем по одной запросы
				   foreach($query as $q){
					   echo $q."\n";
					   mysql_query($q) or die(mysql_error());
				   };
				   
				   //Сохраняем изменения
				   mysql_query("commit")  or die(mysql_error());
			
			}catch( Exception $e ){
				//Отменяем изменения
				mysql_query("rollback");
			};
			
			
		};

		mysql_close($connection);
								
    };
 ?>


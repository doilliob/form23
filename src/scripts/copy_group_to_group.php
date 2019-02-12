  <?php 
     //=======================================================  
	// Копирует расписание с одного месяца на другой (только учителей и уроки) 
	//=======================================================

	include("connection.php");
	
	$group = '251';
	$togroup = '252';
	
	
	$query = "select * from hours where groupnum='".$group."'";
	$query_sub = "select * from substitutions where groupnum='".$group."'";
										
	$result = mysql_query($query);
	#$result_sub = mysql_query($query_sub);
	
	#if($result && $result_sub) {
	if($result) {
	
		//Отбираем все часы по расписанию
		while( $row = mysql_fetch_assoc($result) )
		{
			$arr[$row['lesson']   ]
				[$row['teacher']  ]
				[$row['groupnum'] ]
				[$row['brignum']  ]
				[$row['view']     ]
				[$row['day']      ]  =  $row['hours'];
		};
		
		//Отбираем все часы по заместителям
	#	while( $row = mysql_fetch_assoc($result_sub) )
	#	{
	#	    $arr[$row['lesson']   ]
	#			[$row['teacher']  ]
	#			[$row['groupnum'] ]
	#			[$row['brignum']  ]
	#			[$row['view']     ]
	#			[$row['day']      ]  =  $row['hours'];
	#	};

		
		//Массив запросов
		$query =  array();
		
		//Проверка----------------------------------
		if( isset($arr) && !empty($arr)) //---------
		foreach( $arr as $lesson => $data1 )
		  foreach( $data1 as $teacher => $data2)
		   foreach( $data2 as $groupnum => $data3)
			foreach( $data3 as $brignum => $data4)
			  foreach( $data4 as $view => $data5 )
			    foreach( $data5 as $day => $num )
				$query[ count($query) ] = 
					"insert into hours(lesson,teacher,groupnum,brignum,view,day,hours) values('".$lesson."','".$teacher."','".$togroup."','".$brignum."','".$view."','".$day."',".$num.")";
		
		if( count($query) > 1 ){
			
			mysql_query("SET autocommit=0");
			mysql_query("start transaction");
			try {  
				   //Выполняем по одной запросы
				   #$i = 1;
				   foreach($query as $q){
					   #echo $i++." ".$q."<BR>";
					   mysql_query($q) or die(mysql_error());
				   };
				   
				   //Сохраняем изменения
				   mysql_query("commit")  or die(mysql_error());
			
			}catch( Exception $e ){
				//Отменяем изменения
				mysql_query("rollback");
			};
			
			echo "ok";
			
		};

		mysql_close($connection);
								
    };
 ?>


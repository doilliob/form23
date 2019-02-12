  <?php 
    //=======================================================  
	// Копируем расписание с исходной базы
	//=======================================================
 
	$connection = mysql_connect("localhost","ucheb","123") or die(mysql_error());
	mysql_select_db("ucheb2");
	mysql_query("SET NAMES UTF8");
	
	$arr1;
	$result  = mysql_query("SELECT * FROM hours WHERE date_format(hours.day,'%Y')='2012'")  or die(mysql_error());
	$arr2;
	$result2 = mysql_query("SELECT * FROM hours WHERE date_format(hours.day,'%Y')='2013'") or die(mysql_error());
	if($result && $result2)
	{
		// За 2012 год
		while($row = mysql_fetch_assoc($result))
		{
			$mounth = split('-',$row['day']);
			$mounth = $mounth[1];
			$arr1[$row['lesson']   ]
				 [$row['teacher']  ]
				 [$row['groupnum'] ]
				 [$row['brignum']  ]
				 [$row['view']     ]
				 [$mounth]  =  1; 
		}
		// За 2013 год
		while($row = mysql_fetch_assoc($result2))
		{
			$mounth = split('-',$row['day']);
			$mounth = $mounth[1];
			$arr2[$row['lesson']   ]
				 [$row['teacher']  ]
				 [$row['groupnum'] ]
				 [$row['brignum']  ]
				 [$row['view']	   ]
				 [$mounth]  =  1; 
		}

	}
	mysql_close($connection);


	//=======================================================  
	// Копируем расписание в новую базу
	//=======================================================
	$connection = mysql_connect("localhost","ucheb","123") or die(mysql_error());
	mysql_select_db("ucheb14-15");
	mysql_query("SET NAMES UTF8");

	foreach ($arr1 as $lesson => $value1)
		foreach ($value1 as $teacher => $value2)
			foreach ($value2 as $groupnum => $value3)
				foreach ($value3 as $brignum => $value4)
					foreach ($value4 as $view => $value5)
						foreach ($value5 as $mounth => $num)
						{
							$query = "INSERT INTO hours(lesson,teacher,groupnum,brignum,view,day,hours) values('".$lesson."','".$teacher."','".$groupnum."','".$brignum."','".$view."','2014-".$mounth."-01',1)";
							mysql_query($query) or die(mysql_error() + "  - 2014 -" + $query);
						}


	foreach ($arr2 as $lesson => $value1)
		foreach ($value1 as $teacher => $value2)
			foreach ($value2 as $groupnum => $value3)
				foreach ($value3 as $brignum => $value4)
					foreach ($value4 as $view => $value5)
						foreach ($value5 as $mounth => $num)
						{
							$query = "INSERT INTO hours(lesson,teacher,groupnum,brignum,view,day,hours) values('".$lesson."','".$teacher."','".$groupnum."','".$brignum."','".$view."','2015-".$mounth."-01',1)";
							mysql_query($query) or die(mysql_error() + "  - 2014 -" + $query);
						}		

	mysql_close($connection);
		
	echo "Export to new database succefull!";
	
 ?>


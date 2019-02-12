 <?php
if( isset($_POST['subs']) && 
	isset($_POST['full']) && 
	isset($_POST['mounth']) &&
	isset($_POST['group']))
{
	include('connection.php');
	
	$groupnum = "'".$_POST['group']."'";
	list($mounth,$year) = split("-",$_POST['mounth']);
	
	$full = ($_POST['full'] == "") ? "" : split('\|',$_POST['full']);
	$subs = ($_POST['subs'] == "") ? "" : split('\|',$_POST['subs']);
    
	
	
	
	
	
	//$query; //Массив запросов
	//Отбираем запросы на добавление часов
	if($full != "")
	foreach ($full as $node){
			list ($lesson, $teacher, $brignum, $view, $day, $hours)  = split('\,',$node);
			$str = "insert into hours(view,groupnum,brignum,teacher,lesson,day,hours) values
										( ".$view.",
										  ".$groupnum.",
										  ".$brignum.",
										  ".$teacher.",
										  ".$lesson.",
										  ".$day.",
										  ".$hours.")";
			$str = str_replace('\\', '', $str);
			//echo '<p>'.$str;
			$query[ count($query) ] = $str;
	};
	//Отбираем запросы на добавление заместителей
	if($subs != "")
	foreach ($subs as $node){
			list ($lesson, $teacher,$subster,$brignum, $view, $day, $hours)  = split('\,',$node);
			$str = "insert into substitutions(view,groupnum,brignum,teacher,subster,lesson,day,hours) values
										( ".$view.",
										  ".$groupnum.",
										  ".$brignum.",
										  ".$teacher.",
										  ".$subster.",
										  ".$lesson.",
										  ".$day.",
										  ".$hours.")";
			$str = str_replace('\\', '', $str);
			//echo '<p>'.$str;
			$query[ count($query) ] = $str;
	};
	
	
	
	//САМОЕ ВАЖНОЕ - ДОБАВЛЕНИЕ В БД!!!!!!!!!!!!!!!!!!
	$h_del="delete	from hours where date_format(hours.day,'%m-%Y')='".$mounth."-".$year."' and hours.groupnum=".$groupnum;
    $s_del="delete	from substitutions where date_format(substitutions.day,'%m-%Y')='".$mounth."-".$year."' and substitutions.groupnum=".$groupnum; 									

	
	mysql_query("SET autocommit=0");
	mysql_query("start transaction");
	try {
		   //ВСЕ СТИРАЕМ ЗА ЭТОТ МЕСЯЦ!!!!!!!!!!!!!!!!!!!!!!!!
		   mysql_query($h_del);
		   mysql_query($s_del);						
		   
		   //print_r($query);
		   //Выполняем по одной запросы
		   $myFile = "LOGFile.log";
		   $fh = fopen($myFile, 'a');
		   fwrite($fh,$h_del.";\n");
		   fwrite($fh,$s_del.";\n");
		   
		   if(isset($query) && !empty($query)) 
		   foreach($query as $q){
			   //echo '<hr><p>'.$q;
			   fwrite($fh, $q.";\n");
			   mysql_query($q) or die(mysql_error());
		   };
		   
		   fclose($fh);
		   
		   //Сохраняем изменения
		   mysql_query("commit")  or die(mysql_error());
		   //echo "COMMIT";
	
	}catch( Exception $e ){
		//Отменяем изменения
		mysql_query("rollback");
		mysql_close($connection);
		echo "FAIL";
		return;
	};
		
	mysql_close($connection);
	echo "OK";
	
}else{ echo "FAIL";};
 ?>

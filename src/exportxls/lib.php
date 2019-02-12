 <?php
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
	// Конвертация адреса (х,у) -> (C3)
	function xy($x,$y)
	{
		$a = '';
		$pattern = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$lem = $x;
		while( $lem > 26  ){
			$l = ($lem - ($lem % 26))/26;
			$a .= $pattern[$l-1];
			$lem = $lem % 26;
		};
		$a .= $pattern[$lem-1];
		$a .= $y;
		
		return $a;
	};
	
	function xColumn($x)
	{
		$a = '';
		$pattern = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$lem = $x;
		while( $lem > 26  ){
			$l = ($lem - ($lem % 26))/26;
			$a .= $pattern[$l-1];
			$lem = $lem % 26;
		};
		$a .= $pattern[$lem-1];
		
		return $a;
	};
	
	function Encode ( $str, $type )
	{ // $type: 'w' - encodes from UTF to win 'u' - encodes from win to UTF
		 static $conv='';
		 if (!is_array ( $conv )){
		 $conv=array ();
		 for ( $x=128; $x <=143; $x++ ){
		 $conv['utf'][]=chr(209).chr($x);
		 $conv['win'][]=chr($x+112);
		 }
		 for ( $x=144; $x <=191; $x++ ){
		 $conv['utf'][]=chr(208).chr($x);
		 $conv['win'][]=chr($x+48);
		 }
		 $conv['utf'][]=chr(208).chr(129);
		 $conv['win'][]=chr(168);
		 $conv['utf'][]=chr(209).chr(145);
		 $conv['win'][]=chr(184);
		 }
		 if ( $type=='w' ) return str_replace ( $conv['utf'], $conv['win'], $str );
		 elseif ( $type=='u' ) return str_replace ( $conv['win'], $conv['utf'], $str );
			 else return $str;
	};
   
  
	//================================================
	// Главная функция экспорта в файл
	//================================================
	function ExportXLSX($teacher)
	{
		//==================================================================
		// ГЕНЕРАЦИЯ МАТРИЦЫ ТАБЛИЦЫ
		//==================================================================
		$year = array( '09' => 'Сентябрь',
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
		
		//echo "<p>Выполнение обработки на преподавателя ".$teacher."</p>";
		//echo "<p>---------------------------------------</p>";
		
		// ЗАПРОСЫ
		//----------------------------------------------------------
		$query = "select lesson,groupnum,date_format(hours.day,'%m') as mounth,sum(hours) as summa from hours 
						where   teacher='".$teacher."'
						group by lesson,groupnum,mounth
						order by lesson,groupnum,mounth
						";
						
		$query_nobudg = "select number from students where budg=0";
		
		$query_tarif = "select * from tarif where fio='".$teacher."'";
		
		// ВЫПОЛНЕНИЕ ЗАПРОСОВ
		//--------------------------------------------------------------------------------------
		//echo "<p>===> Получение часов преподавателя </p>";
		$result = mysql_query($query) or die('Ошибка выполнения запроса #1! '+mysql_error());
		
		//echo "<p>===> Получение списка внебюджетных групп </p>";
		$result_nobudg = mysql_query($query_nobudg) or die('Ошибка выполнения запроса #2! '+mysql_error());
		
		//echo "<p>===> Получение списка тарификации </p>";
		$result_tarif = mysql_query($query_tarif) or die('Ошибка выполнения запроса #5! '+mysql_error());
		
		// ОБРАБОТКА ПОЛУЧЕННЫХ ДАННЫХ
		//--------------------------------------------------------------------------------------
		//echo "<p>===> Обработка полученных данных и построение матрицы...</p>";
		//--------------------------------------------------------------------------------------
		
		$Матрица;
		
		//Список внебюджетных групп
		$Внебюджетные_Группы;
		if($result_nobudg)
		while($row = mysql_fetch_array($result_nobudg))
		{
			$Внебюджетные_Группы[$row['number']] = 1;
		};
		unset($result_nobudg);
		
		//Подсчет тарификации
		$Тарификация;
		// Инициализация
		$Тарификация['ekz_budg']=0;
		$Тарификация['ekz_nbudg']=0;
		$Тарификация['tarif_budg']=0;
		$Тарификация['tarif_nbudg']=0;
		$Тарификация['pred_budg']=0;
		$Тарификация['pred_nbudg']=0;
		$Тарификация['perc_budg']=0;
		$Тарификация['perc_nbudg']=0;
		$Тарификация['not_budg']=0;
		$Тарификация['not_nbudg']=0;
		// из БД
		if($result_tarif)
		while($row = mysql_fetch_assoc($result_tarif))
		{	
			$Тарификация['ekz_budg']=$row['ekz_budg'];
			$Тарификация['ekz_nbudg']=$row['ekz_nbudg'];
			$Тарификация['tarif_budg']=$row['tarif_budg'];
			$Тарификация['tarif_nbudg']=$row['tarif_nbudg'];
			$Тарификация['pred_budg']=$row['pred_budg'];
			$Тарификация['pred_nbudg']=$row['pred_nbudg'];
			$Тарификация['perc_budg']=$row['perc_budg'];
			$Тарификация['perc_nbudg']=$row['perc_nbudg'];
			$Тарификация['not_budg']=$row['not_budg'];
			$Тарификация['not_nbudg']=$row['not_nbudg'];
		};
		unset($result_tarif);
		
		//Основные часы
		//------------------------------------
		// Структура матрицы:
		// Месяц
		//    -> БЮДЖЕТ =
		//    -> ВНЕБЮДЖЕТ =
		//    -> 'ПРЕДМЕТЫ'
		//           -> НазваниеПредмета
		//                  -> НомерГруппы
		//                       -> КоличествоЧасов
		//------------------------------------
		while($row = mysql_fetch_assoc($result)){
		
			$Матрица [$row['mounth']]
						['ПРЕДМЕТЫ']
							[$row['lesson']]
									[$row['groupnum']] = $row['summa'];
							
			$Матрица [$row['mounth']]['БЮДЖЕТ'] = 0;
			
			$Матрица [$row['mounth']]['ВНЕБЮДЖЕТ'] = 0;
			
		};
		unset($result);
		
		//Если часов нет
		if(  !isset($Матрица) || 
			(count($Матрица) == 0))
			{
				echo "no hours!";
				return;
			};
			
		//Подсчет бюджета/внебюджета за месяц
		$Количество_Столбцов = 0;
		$Предмет_КоличествоГрупп;
		if($Матрица)
		foreach( array_keys($Матрица) as $mounth ){
			foreach( array_keys($Матрица[$mounth]['ПРЕДМЕТЫ']) as $lesson ){
				foreach( array_keys($Матрица[$mounth]['ПРЕДМЕТЫ'][$lesson]) as $groupnum){
					if(isset($Внебюджетные_Группы[$groupnum]))
					{
						$Матрица[$mounth]['ВНЕБЮДЖЕТ'] += $Матрица[$mounth]['ПРЕДМЕТЫ'][$lesson][$groupnum];
					}else{
						$Матрица[$mounth]['БЮДЖЕТ'] += $Матрица[$mounth]['ПРЕДМЕТЫ'][$lesson][$groupnum];
					};
					$Предмет_КоличествоГрупп[$lesson][$groupnum]=1;
				};
			};
		};
		foreach ( array_keys($Предмет_КоличествоГрупп) as $Предмет) 
			$Количество_Столбцов += count($Предмет_КоличествоГрупп[$Предмет]);
			
		
		
		
		//==================================================================
		// ВЫГРУЗКА ТАБЛИЦЫ
		//==================================================================
		
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/Samara');
		

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Фамилия И.О.")
									 ->setLastModifiedBy("Фамилия И.О.")
									 ->setTitle("Отчет по Ф3")
									 ->setSubject("Отчет по Форме 3")
									 ->setDescription("Отчет из учебной части по форме Ф-3")
									 ->setKeywords("office PHPExcel php")
									 ->setCategory("Отчет");

		$objPHPExcel->setActiveSheetIndex(0);
		
		// Имя листа
		$objPHPExcel->getActiveSheet()->setTitle("Форма 3");
		
		//ШАПКА ТАБЛИЦЫ
		$x=1;
		$y=8;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Дисциплина/Группа"); 
		$x++;
		foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет){
			$curX = $x;
			$nextX = $curX + count($Предмет_КоличествоГрупп[$Предмет]) - 1;
			$objPHPExcel->getActiveSheet()->mergeCells( xy($curX,$y).":".xy($nextX,$y));
			$objPHPExcel->getActiveSheet()->setCellValue( xy($curX,$y) , $Предмет);
			$x = $nextX + 1;
		};
		$objPHPExcel->getActiveSheet()->mergeCells( xy($x,$y).":".xy($x+1,$y));
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Всего");
		//ВТОРАЯ СТРОКА
		$x=1;
		$y=9;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Месяц");
		$x++;
		foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет)
			foreach( array_keys($Предмет_КоличествоГрупп[$Предмет]) as $Группа)
			{
				$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , $Группа);
				$x++;
			};
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Бюджет"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Внебюджет");
		
		
		$x=1;$y=10;
		foreach( $semestr as $Семестр => $МесяцМассив)
		{
			foreach( $МесяцМассив as $Месяц => $Название )
			{
			  $objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , $Название); $x++;
			  foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет)
				foreach( array_keys($Предмет_КоличествоГрупп[$Предмет]) as $Группа)
				{
					if( isset($Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа]) ) {
						$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , $Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа]);
					};
					$x++;
					
				};
			  if( isset($Матрица[$Месяц]) ){
				$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , 
					($Матрица[$Месяц]['БЮДЖЕТ'] == 0) ? '' : $Матрица[$Месяц]['БЮДЖЕТ']);$x++;
				$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , 
					($Матрица[$Месяц]['ВНЕБЮДЖЕТ'] == 0)? '': $Матрица[$Месяц]['ВНЕБЮДЖЕТ']);$x++;
			  };
			  
			  $y++;
			  $x=1;
			};
			//ОКОНЧАНИЕ СЕМЕСТРА
			$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , $Семестр); $x++;
			foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет)
			   foreach( array_keys($Предмет_КоличествоГрупп[$Предмет]) as $Группа)
				{
					$Итого = 0;
					foreach( $МесяцМассив as $Месяц => $Название )
					  if( isset($Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа]) ) 
						 $Итого += $Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа];
					
					$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Итого == 0) ? '': $Итого); $x++;
				};
			$Бюджет = 0;
			$Внебюджет = 0;
			foreach( $МесяцМассив as $Месяц => $Название )
				if( isset($Матрица[$Месяц]) )
				{
					$Бюджет += $Матрица[$Месяц]['БЮДЖЕТ'];
					$Внебюджет += $Матрица[$Месяц]['ВНЕБЮДЖЕТ'];
				};
			$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Бюджет == 0) ? '' : $Бюджет);$x++;
			$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Внебюджет == 0) ? '' : $Внебюджет);$x++;
			$y++;
			$x=1;

		};
		
		// СТРОКА ВСЕГО ЧАСОВ ДАНО ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Всего часов дано"); $x++;
		foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет)
		   foreach( array_keys($Предмет_КоличествоГрупп[$Предмет]) as $Группа)
			{
				$Итого = 0;
				foreach($year as $Месяц => $Название)
					if( isset($Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа]) )
						$Итого += $Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа];
						
				$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Итого == 0) ? '': $Итого); $x++;
				
			};
		$Бюджет = 0;
		$Внебюджет = 0;
		foreach( $year as $Месяц => $Название )
			if( isset($Матрица[$Месяц]) )
			{
				$Бюджет += $Матрица[$Месяц]['БЮДЖЕТ'];
				$Внебюджет += $Матрица[$Месяц]['ВНЕБЮДЖЕТ'];
			};
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Бюджет == 0) ? '' : $Бюджет);$x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Внебюджет == 0) ? '' : $Внебюджет);$x++;
		$x=1;$y++;
		
		
		$ДаноЗаГодБюджет = ($Бюджет + $Тарификация['ekz_budg']);
		$ДаноЗаГодВнебюджет = ($Внебюджет + $Тарификация['ekz_nbudg']);
		$ПослеСнятияБюджет = ($Тарификация['tarif_budg'] - $Тарификация['pred_budg'] - $Тарификация['perc_budg']);
		$ПослеСнятияВнебюджет = ($Тарификация['tarif_nbudg'] - $Тарификация['pred_nbudg'] - $Тарификация['perc_nbudg']);
		
		// СТРОКА ЭКЗАМЕНЫ ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Экзаменационные часы"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($Тарификация['ekz_budg']==0)? '':$Тарификация['ekz_budg']);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($Тарификация['ekz_nbudg']==0)? '':$Тарификация['ekz_nbudg']);
		$x=1;
		$y++;
		// СТРОКА ТАРИФИКАЦИЯ ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Всего по тарификации"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($Тарификация['tarif_budg']==0)? '':$Тарификация['tarif_budg']);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($Тарификация['tarif_nbudg']==0)? '':$Тарификация['tarif_nbudg']);
		$x=1;
		$y++;
		// СТРОКА ИЗМЕНЕНИЕ ПЕДНАГРУЗКИ ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Изменение педнагрузки"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($Тарификация['pred_budg']==0)? '':$Тарификация['pred_budg']);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($Тарификация['pred_nbudg']==0)? '':$Тарификация['pred_nbudg']);
		$x=1;
		$y++;
		// СТРОКА 5% ПРАЗДНИЧНЫХ ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "5% праздничных"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($Тарификация['perc_budg']==0)? '':$Тарификация['perc_budg']);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($Тарификация['perc_nbudg']==0)? '':$Тарификация['perc_nbudg']);
		$x=1;
		$y++;
		
		
		// СТРОКА ПОСЛЕ СНЯТИЯ ==================================================
		$budg = ($ПослеСнятияБюджет > 0) ? $ПослеСнятияБюджет : '';
		$nbudg = ($ПослеСнятияВнебюджет > 0) ? $ПослеСнятияВнебюджет : '';
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Количество часов после снятия"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), $budg);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), $nbudg);
		$x=1;
		$y++;
		

		// СТРОКА НЕ ВЫПОЛНЕНО ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Не выполнено"); $x++;
		//$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+4,$y), 
		//		($Тарификация['not_budg']==0)? '':$Тарификация['not_budg']);
		//$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+5,$y), 
		//		($Тарификация['not_nbudg']==0)? '':$Тарификация['not_nbudg']);
		$bud = $Тарификация['not_budg'];$bud = ($bud > 0) ? $bud : '';
		$nbud = $Тарификация['not_nbudg'];$nbud = ($nbud > 0) ? $nbud : '';
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), $bud);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), $nbud);
		$x=1;
		$y++;
		
		// СТРОКА СВЕРХ ==================================================
		$bud = ($ДаноЗаГодБюджет - $ПослеСнятияБюджет); $bud = ($bud > 0) ? $bud : '';
		$nbud = ($ДаноЗаГодВнебюджет - $ПослеСнятияВнебюджет); $nbud = ($nbud > 0) ? $nbud : '';
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Дано часов сверх плана"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), $bud);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), $nbud);
		$x=1;
		$y++;
		
		// СТРОКА ДАНО ЗА ГОД ==================================================
		$bud = $ДаноЗаГодБюджет;
		$nbud = $ДаноЗаГодВнебюджет;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Всего дано за год"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($bud==0)? '':$bud);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($nbud==0)? '':$nbud);
		$x=1;
		$y++;
		
		//РАМКА
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);
		
		for($x1=1;$x1 < ($Количество_Столбцов+4);$x1++)
			for($y1=8; $y1<32;$y1++)
				$objPHPExcel->getActiveSheet()->getStyle(xy($x1,$y1))->applyFromArray($styleThinBlackBorderOutline);
			
		
		$objPHPExcel->getActiveSheet()->getStyle("A1:".xy($Количество_Столбцов+3,40))->getFont()->setName('Times New Roman');
		$objPHPExcel->getActiveSheet()->getStyle("A1:".xy($Количество_Столбцов+3,40))->getFont()->setSize(12);
		
		
		//ПРОШУ
		$objPHPExcel->getActiveSheet()->mergeCells("A32:L35");
		$objPHPExcel->getActiveSheet()->getStyle("A32:L40")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A32:L40")->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A32')->getFont()->setName('Times New Roman');
		$objPHPExcel->getActiveSheet()->getStyle('A32')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A32:L40")->getAlignment()->setWrapText(true);
		
		//Сверх плана
		$bud = ($ДаноЗаГодБюджет - $ПослеСнятияБюджет); $bud = ($bud > 0) ? $bud : 0;
		$nbud = ($ДаноЗаГодВнебюджет - $ПослеСнятияВнебюджет); $nbud = ($nbud > 0) ? $nbud : 0;
		if( ($bud > 0) or ($nbudg > 0) ) {
			$objPHPExcel->getActiveSheet()->setCellValue("A32","Педагогическая нагрузка выполнена полностью. Прошу оплатить дополнительно часов: ".$bud." из бюджета и часов: ".$nbud." из внебюджета.");
		}else{
			$objPHPExcel->getActiveSheet()->setCellValue("A32","Педагогическая нагрузка не выполнена полностью. По причине ______");
		};

		
		$objPHPExcel->getActiveSheet()->mergeCells("A36:L36");	
		$objPHPExcel->getActiveSheet()->setCellValue("A36","Зав. учебной частью _______________ ");
		$objPHPExcel->getActiveSheet()->mergeCells("A37:L37");
		$objPHPExcel->getActiveSheet()->setCellValue("A37","Заместитель директора по учебно-воспитательной работе _______________ Фамилия И.О.");
		$objPHPExcel->getActiveSheet()->mergeCells("A38:L38");
		$objPHPExcel->getActiveSheet()->setCellValue("A38","Годовой учет часов, данных преподавателем, ведется учебной частью на основании записей");
		$objPHPExcel->getActiveSheet()->mergeCells("A39:L39");
		$objPHPExcel->getActiveSheet()->setCellValue("A39","в ведомости учета часов учебной работы преподавателей ( форма №2 )");
		
		//Шапка
		$objPHPExcel->getActiveSheet()->getStyle("A8:".xy($Количество_Столбцов+3,9))->applyFromArray(
			array('fill' 	=> array(
										'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
										'color'		=> array('rgb' => 'C4C4BC')
									),
				)
			);
		$objPHPExcel->getActiveSheet()->getStyle("A14:".xy($Количество_Столбцов+3,14))->applyFromArray(
			array('fill' 	=> array(
										'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
										'color'		=> array('rgb' => 'F5F12C')
									),
				)
			);
		$objPHPExcel->getActiveSheet()->getStyle("A22:".xy($Количество_Столбцов+3,22))->applyFromArray(
		array('fill' 	=> array(
									'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
									'color'		=> array('rgb' => 'F5F12C')
								),
			)
		);
		//Высота столбца 
		//$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(1);
		//Ширина столбца
		// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		//Объединение ячеек
		//$objPHPExcel->getActiveSheet()->mergeCells("A3:E14");
		
		// Шапка -------------------------------------------------------------------------------------------------------	
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,1) , 'УЧРЕЖДЕНИЕ');
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,2) , 'ГОДОВОЙ УЧЕТ ЧАСОВ,');
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,3) , 'Данных преподавателями за 2016-2017 учебный год');
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,5) , 'Учет часов работы за 2016-2017 учебный год');
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,6) , 'Фамилия преподавателя:  '.$teacher);
		
		
		// Для шапки
		for($y=1;$y<8;$y++) 
			$objPHPExcel->getActiveSheet()->mergeCells(xy(1,$y).":L".$y);
			
		$objPHPExcel->getActiveSheet()->getStyle('A1:L3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A1:L3")->applyFromArray(
					array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('argb' => 'FF000000'),
						),
					),
				));
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.xy($Количество_Столбцов+3,50))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.xy($Количество_Столбцов+3,50))->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A8:'.xy($Количество_Столбцов+3,31))->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A8:'.xy($Количество_Столбцов+3,8))->getAlignment()->setShrinkToFit(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth((int)(150/7.5));
		$objPHPExcel->getActiveSheet()->getColumnDimension(xColumn($Количество_Столбцов+2))->setWidth((int)(80/7.5));
		$objPHPExcel->getActiveSheet()->getColumnDimension(xColumn($Количество_Столбцов+3))->setWidth((int)(90/7.5));

		//Сохранение файла!!!!!
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(Encode("export/".$teacher.".xlsx",'w'));
		
		
		unset($objWriter);
		unset($objPHPExcel);
		unset($Матрица);
		
   };
 
   
	//================================================
	// Главная функция экспорта в файл
	//================================================
	function ExportXLSXToPath($teacher,$path)
	{
		//==================================================================
		// ГЕНЕРАЦИЯ МАТРИЦЫ ТАБЛИЦЫ
		//==================================================================
		$year = array( '09' => 'Сентябрь',
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
		
		//echo "<p>Выполнение обработки на преподавателя ".$teacher."</p>";
		//echo "<p>---------------------------------------</p>";
		
		// ЗАПРОСЫ
		//----------------------------------------------------------
		$query = "select lesson,groupnum,date_format(hours.day,'%m') as mounth,sum(hours) as summa from hours 
						where   teacher='".$teacher."'
						group by lesson,groupnum,mounth
						order by lesson,groupnum,mounth
						";
						
		$query_nobudg = "select number from students where budg=0";
		
		$query_tarif = "select * from tarif where fio='".$teacher."'";
		
		// ВЫПОЛНЕНИЕ ЗАПРОСОВ
		//--------------------------------------------------------------------------------------
		//echo "<p>===> Получение часов преподавателя </p>";
		$result = mysql_query($query) or die('Ошибка выполнения запроса #1! '+mysql_error());
		
		//echo "<p>===> Получение списка внебюджетных групп </p>";
		$result_nobudg = mysql_query($query_nobudg) or die('Ошибка выполнения запроса #2! '+mysql_error());
		
		//echo "<p>===> Получение списка тарификации </p>";
		$result_tarif = mysql_query($query_tarif) or die('Ошибка выполнения запроса #5! '+mysql_error());
		
		// ОБРАБОТКА ПОЛУЧЕННЫХ ДАННЫХ
		//--------------------------------------------------------------------------------------
		//echo "<p>===> Обработка полученных данных и построение матрицы...</p>";
		//--------------------------------------------------------------------------------------
		
		$Матрица;
		
		//Список внебюджетных групп
		$Внебюджетные_Группы;
		if($result_nobudg)
		while($row = mysql_fetch_array($result_nobudg))
		{
			$Внебюджетные_Группы[$row['number']] = 1;
		};
		unset($result_nobudg);
		
		//Подсчет тарификации
		$Тарификация;
		// Инициализация
		$Тарификация['ekz_budg']=0;
		$Тарификация['ekz_nbudg']=0;
		$Тарификация['tarif_budg']=0;
		$Тарификация['tarif_nbudg']=0;
		$Тарификация['pred_budg']=0;
		$Тарификация['pred_nbudg']=0;
		$Тарификация['perc_budg']=0;
		$Тарификация['perc_nbudg']=0;
		$Тарификация['not_budg']=0;
		$Тарификация['not_nbudg']=0;
		// из БД
		if($result_tarif)
		while($row = mysql_fetch_assoc($result_tarif))
		{	
			$Тарификация['ekz_budg']=$row['ekz_budg'];
			$Тарификация['ekz_nbudg']=$row['ekz_nbudg'];
			$Тарификация['tarif_budg']=$row['tarif_budg'];
			$Тарификация['tarif_nbudg']=$row['tarif_nbudg'];
			$Тарификация['pred_budg']=$row['pred_budg'];
			$Тарификация['pred_nbudg']=$row['pred_nbudg'];
			$Тарификация['perc_budg']=$row['perc_budg'];
			$Тарификация['perc_nbudg']=$row['perc_nbudg'];
			$Тарификация['not_budg']=$row['not_budg'];
			$Тарификация['not_nbudg']=$row['not_nbudg'];
		};
		unset($result_tarif);
		
		//Основные часы
		//------------------------------------
		// Структура матрицы:
		// Месяц
		//    -> БЮДЖЕТ =
		//    -> ВНЕБЮДЖЕТ =
		//    -> 'ПРЕДМЕТЫ'
		//           -> НазваниеПредмета
		//                  -> НомерГруппы
		//                       -> КоличествоЧасов
		//------------------------------------
		while($row = mysql_fetch_assoc($result)){
		
			$Матрица [$row['mounth']]
						['ПРЕДМЕТЫ']
							[$row['lesson']]
									[$row['groupnum']] = $row['summa'];
							
			$Матрица [$row['mounth']]['БЮДЖЕТ'] = 0;
			
			$Матрица [$row['mounth']]['ВНЕБЮДЖЕТ'] = 0;
			
		};
		unset($result);
		
		//Если часов нет
		if(  !isset($Матрица) || 
			(count($Матрица) == 0))
			{
				echo "no hours!";
				return;
			};
			
		//Подсчет бюджета/внебюджета за месяц
		$Количество_Столбцов = 0;
		$Предмет_КоличествоГрупп;
		if($Матрица)
		foreach( array_keys($Матрица) as $mounth ){
			foreach( array_keys($Матрица[$mounth]['ПРЕДМЕТЫ']) as $lesson ){
				foreach( array_keys($Матрица[$mounth]['ПРЕДМЕТЫ'][$lesson]) as $groupnum){
					if(isset($Внебюджетные_Группы[$groupnum]))
					{
						$Матрица[$mounth]['ВНЕБЮДЖЕТ'] += $Матрица[$mounth]['ПРЕДМЕТЫ'][$lesson][$groupnum];
					}else{
						$Матрица[$mounth]['БЮДЖЕТ'] += $Матрица[$mounth]['ПРЕДМЕТЫ'][$lesson][$groupnum];
					};
					$Предмет_КоличествоГрупп[$lesson][$groupnum]=1;
				};
			};
		};
		foreach ( array_keys($Предмет_КоличествоГрупп) as $Предмет) 
			$Количество_Столбцов += count($Предмет_КоличествоГрупп[$Предмет]);
			
		
		
		
		//==================================================================
		// ВЫГРУЗКА ТАБЛИЦЫ
		//==================================================================
		
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/Samara');
		

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Фамилия И.О.")
									 ->setLastModifiedBy("Фамилия И.О.")
									 ->setTitle("Отчет по Ф3")
									 ->setSubject("Отчет по Форме 3")
									 ->setDescription("Отчет из учебной части по форме Ф-3")
									 ->setKeywords("office PHPExcel php")
									 ->setCategory("Отчет");

		$objPHPExcel->setActiveSheetIndex(0);
		
		// Имя листа
		$objPHPExcel->getActiveSheet()->setTitle("Форма 3");
		
		//ШАПКА ТАБЛИЦЫ
		$x=1;
		$y=8;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Дисциплина/Группа"); 
		$x++;
		foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет){
			$curX = $x;
			$nextX = $curX + count($Предмет_КоличествоГрупп[$Предмет]) - 1;
			$objPHPExcel->getActiveSheet()->mergeCells( xy($curX,$y).":".xy($nextX,$y));
			$objPHPExcel->getActiveSheet()->setCellValue( xy($curX,$y) , $Предмет);
			$x = $nextX + 1;
		};
		$objPHPExcel->getActiveSheet()->mergeCells( xy($x,$y).":".xy($x+1,$y));
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Всего");
		//ВТОРАЯ СТРОКА
		$x=1;
		$y=9;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Месяц");
		$x++;
		foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет)
			foreach( array_keys($Предмет_КоличествоГрупп[$Предмет]) as $Группа)
			{
				$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , $Группа);
				$x++;
			};
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Бюджет"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Внебюджет");
		
		
		$x=1;$y=10;
		foreach( $semestr as $Семестр => $МесяцМассив)
		{
			foreach( $МесяцМассив as $Месяц => $Название )
			{
			  $objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , $Название); $x++;
			  foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет)
				foreach( array_keys($Предмет_КоличествоГрупп[$Предмет]) as $Группа)
				{
					if( isset($Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа]) ) {
						$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , $Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа]);
					};
					$x++;
					
				};
			  if( isset($Матрица[$Месяц]) ){
				$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , 
					($Матрица[$Месяц]['БЮДЖЕТ'] == 0) ? '' : $Матрица[$Месяц]['БЮДЖЕТ']);$x++;
				$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , 
					($Матрица[$Месяц]['ВНЕБЮДЖЕТ'] == 0)? '': $Матрица[$Месяц]['ВНЕБЮДЖЕТ']);$x++;
			  };
			  
			  $y++;
			  $x=1;
			};
			//ОКОНЧАНИЕ СЕМЕСТРА
			$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , $Семестр); $x++;
			foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет)
			   foreach( array_keys($Предмет_КоличествоГрупп[$Предмет]) as $Группа)
				{
					$Итого = 0;
					foreach( $МесяцМассив as $Месяц => $Название )
					  if( isset($Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа]) ) 
						 $Итого += $Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа];
					
					$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Итого == 0) ? '': $Итого); $x++;
				};
			$Бюджет = 0;
			$Внебюджет = 0;
			foreach( $МесяцМассив as $Месяц => $Название )
				if( isset($Матрица[$Месяц]) )
				{
					$Бюджет += $Матрица[$Месяц]['БЮДЖЕТ'];
					$Внебюджет += $Матрица[$Месяц]['ВНЕБЮДЖЕТ'];
				};
			$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Бюджет == 0) ? '' : $Бюджет);$x++;
			$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Внебюджет == 0) ? '' : $Внебюджет);$x++;
			$y++;
			$x=1;

		};
		
		// СТРОКА ВСЕГО ЧАСОВ ДАНО ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Всего часов дано"); $x++;
		foreach( array_keys($Предмет_КоличествоГрупп) as $Предмет)
		   foreach( array_keys($Предмет_КоличествоГрупп[$Предмет]) as $Группа)
			{
				$Итого = 0;
				foreach($year as $Месяц => $Название)
					if( isset($Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа]) )
						$Итого += $Матрица[$Месяц]['ПРЕДМЕТЫ'][$Предмет][$Группа];
						
				$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Итого == 0) ? '': $Итого); $x++;
				
			};
		$Бюджет = 0;
		$Внебюджет = 0;
		foreach( $year as $Месяц => $Название )
			if( isset($Матрица[$Месяц]) )
			{
				$Бюджет += $Матрица[$Месяц]['БЮДЖЕТ'];
				$Внебюджет += $Матрица[$Месяц]['ВНЕБЮДЖЕТ'];
			};
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Бюджет == 0) ? '' : $Бюджет);$x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , ($Внебюджет == 0) ? '' : $Внебюджет);$x++;
		$x=1;$y++;
		
		
		$ДаноЗаГодБюджет = ($Бюджет + $Тарификация['ekz_budg']);
		$ДаноЗаГодВнебюджет = ($Внебюджет + $Тарификация['ekz_nbudg']);
		$ПослеСнятияБюджет = ($Тарификация['tarif_budg'] - $Тарификация['pred_budg'] - $Тарификация['perc_budg']);
		$ПослеСнятияВнебюджет = ($Тарификация['tarif_nbudg'] - $Тарификация['pred_nbudg'] - $Тарификация['perc_nbudg']);
		
		// СТРОКА ЭКЗАМЕНЫ ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Экзаменационные часы"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($Тарификация['ekz_budg']==0)? '':$Тарификация['ekz_budg']);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($Тарификация['ekz_nbudg']==0)? '':$Тарификация['ekz_nbudg']);
		$x=1;
		$y++;
		// СТРОКА ТАРИФИКАЦИЯ ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Всего по тарификации"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($Тарификация['tarif_budg']==0)? '':$Тарификация['tarif_budg']);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($Тарификация['tarif_nbudg']==0)? '':$Тарификация['tarif_nbudg']);
		$x=1;
		$y++;
		// СТРОКА ИЗМЕНЕНИЕ ПЕДНАГРУЗКИ ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Изменение педнагрузки"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($Тарификация['pred_budg']==0)? '':$Тарификация['pred_budg']);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($Тарификация['pred_nbudg']==0)? '':$Тарификация['pred_nbudg']);
		$x=1;
		$y++;
		// СТРОКА 5% ПРАЗДНИЧНЫХ ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "5% праздничных"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($Тарификация['perc_budg']==0)? '':$Тарификация['perc_budg']);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($Тарификация['perc_nbudg']==0)? '':$Тарификация['perc_nbudg']);
		$x=1;
		$y++;
		
		
		// СТРОКА ПОСЛЕ СНЯТИЯ ==================================================
		$budg = ($ПослеСнятияБюджет > 0) ? $ПослеСнятияБюджет : '';
		$nbudg = ($ПослеСнятияВнебюджет > 0) ? $ПослеСнятияВнебюджет : '';
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Количество часов после снятия"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), $budg);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), $nbudg);
		$x=1;
		$y++;
		

		// СТРОКА НЕ ВЫПОЛНЕНО ==================================================
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Не выполнено"); $x++;
		//$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+4,$y), 
		//		($Тарификация['not_budg']==0)? '':$Тарификация['not_budg']);
		//$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+5,$y), 
		//		($Тарификация['not_nbudg']==0)? '':$Тарификация['not_nbudg']);
		$bud = $Тарификация['not_budg'];$bud = ($bud > 0) ? $bud : '';
		$nbud = $Тарификация['not_nbudg'];$nbud = ($nbud > 0) ? $nbud : '';
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), $bud);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), $nbud);
		$x=1;
		$y++;
		
		// СТРОКА СВЕРХ ==================================================
		$bud = ($ДаноЗаГодБюджет - $ПослеСнятияБюджет); $bud = ($bud > 0) ? $bud : '';
		$nbud = ($ДаноЗаГодВнебюджет - $ПослеСнятияВнебюджет); $nbud = ($nbud > 0) ? $nbud : '';
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Дано часов сверх плана"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), $bud);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), $nbud);
		$x=1;
		$y++;
		
		// СТРОКА ДАНО ЗА ГОД ==================================================
		$bud = $ДаноЗаГодБюджет;
		$nbud = $ДаноЗаГодВнебюджет;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($x,$y) , "Всего дано за год"); $x++;
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+2,$y), 
				($bud==0)? '':$bud);
		$objPHPExcel->getActiveSheet()->setCellValue( xy($Количество_Столбцов+3,$y), 
				($nbud==0)? '':$nbud);
		$x=1;
		$y++;
		
		//РАМКА
		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
				),
			),
		);
		
		for($x1=1;$x1 < ($Количество_Столбцов+4);$x1++)
			for($y1=8; $y1<32;$y1++)
				$objPHPExcel->getActiveSheet()->getStyle(xy($x1,$y1))->applyFromArray($styleThinBlackBorderOutline);
			
		
		$objPHPExcel->getActiveSheet()->getStyle("A1:".xy($Количество_Столбцов+3,40))->getFont()->setName('Times New Roman');
		$objPHPExcel->getActiveSheet()->getStyle("A1:".xy($Количество_Столбцов+3,40))->getFont()->setSize(12);
		
		
		//ПРОШУ
		$objPHPExcel->getActiveSheet()->mergeCells("A32:L35");
		$objPHPExcel->getActiveSheet()->getStyle("A32:L40")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("A32:L40")->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A32')->getFont()->setName('Times New Roman');
		$objPHPExcel->getActiveSheet()->getStyle('A32')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A32:L40")->getAlignment()->setWrapText(true);
		
		//Сверх плана
		$bud = ($ДаноЗаГодБюджет - $ПослеСнятияБюджет); $bud = ($bud > 0) ? $bud : 0;
		$nbud = ($ДаноЗаГодВнебюджет - $ПослеСнятияВнебюджет); $nbud = ($nbud > 0) ? $nbud : 0;
		if( ($bud > 0) or ($nbudg > 0) ) {
			$objPHPExcel->getActiveSheet()->setCellValue("A32","Педагогическая нагрузка выполнена полностью. Прошу оплатить дополнительно часов: ".$bud." из бюджета и часов: ".$nbud." из внебюджета.");
		}else{
			$objPHPExcel->getActiveSheet()->setCellValue("A32","Педагогическая нагрузка не выполнена полностью. По причине ______");
		};

		
		$objPHPExcel->getActiveSheet()->mergeCells("A36:L36");	
		$objPHPExcel->getActiveSheet()->setCellValue("A36","Зав. учебной частью _______________ ");
		$objPHPExcel->getActiveSheet()->mergeCells("A37:L37");
		$objPHPExcel->getActiveSheet()->setCellValue("A37","Заместитель директора по учебно-воспитательной работе _______________ Фамилия И.О.");
		$objPHPExcel->getActiveSheet()->mergeCells("A38:L38");
		$objPHPExcel->getActiveSheet()->setCellValue("A38","Годовой учет часов, данных преподавателем, ведется учебной частью на основании записей");
		$objPHPExcel->getActiveSheet()->mergeCells("A39:L39");
		$objPHPExcel->getActiveSheet()->setCellValue("A39","в ведомости учета часов учебной работы преподавателей ( форма №2 )");
		
		//Шапка
		$objPHPExcel->getActiveSheet()->getStyle("A8:".xy($Количество_Столбцов+3,9))->applyFromArray(
			array('fill' 	=> array(
										'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
										'color'		=> array('rgb' => 'C4C4BC')
									),
				)
			);
		$objPHPExcel->getActiveSheet()->getStyle("A14:".xy($Количество_Столбцов+3,14))->applyFromArray(
			array('fill' 	=> array(
										'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
										'color'		=> array('rgb' => 'F5F12C')
									),
				)
			);
		$objPHPExcel->getActiveSheet()->getStyle("A22:".xy($Количество_Столбцов+3,22))->applyFromArray(
		array('fill' 	=> array(
									'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
									'color'		=> array('rgb' => 'F5F12C')
								),
			)
		);
		//Высота столбца 
		//$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(1);
		//Ширина столбца
		// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		//Объединение ячеек
		//$objPHPExcel->getActiveSheet()->mergeCells("A3:E14");
		
		// Шапка -------------------------------------------------------------------------------------------------------	
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,1) , 'УЧРЕЖДЕНИЕ');
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,2) , 'ГОДОВОЙ УЧЕТ ЧАСОВ,');
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,3) , 'Данных преподавателями за 2016-2017 учебный год');
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,5) , 'Учет часов работы за 2016-2017 учебный год');
		$objPHPExcel->getActiveSheet()->setCellValue( xy(1,6) , 'Фамилия преподавателя:  '.$teacher);
		
		
		// Для шапки
		for($y=1;$y<8;$y++) 
			$objPHPExcel->getActiveSheet()->mergeCells(xy(1,$y).":L".$y);
			
		$objPHPExcel->getActiveSheet()->getStyle('A1:L3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A1:L3")->applyFromArray(
					array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('argb' => 'FF000000'),
						),
					),
				));
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.xy($Количество_Столбцов+3,50))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.xy($Количество_Столбцов+3,50))->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A8:'.xy($Количество_Столбцов+3,31))->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('A8:'.xy($Количество_Столбцов+3,8))->getAlignment()->setShrinkToFit(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth((int)(150/7.5));
		$objPHPExcel->getActiveSheet()->getColumnDimension(xColumn($Количество_Столбцов+2))->setWidth((int)(80/7.5));
		$objPHPExcel->getActiveSheet()->getColumnDimension(xColumn($Количество_Столбцов+3))->setWidth((int)(90/7.5));

		//Сохранение файла!!!!!
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(Encode($path."/".$teacher.".xlsx",'w'));
		
		
		unset($objWriter);
		unset($objPHPExcel);
		unset($Матрица);
		
   };
 
 ?>
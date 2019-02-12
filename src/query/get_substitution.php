 <?php
	include("connection.php");
	
	$group = $_GET['group'];
	$mounth = $_GET['mounth'];
	$year = $_GET['year'];
	
	$query = "select * from substitution where date_format(substitution.day,'%m-%Y')='".$mounth."-".$year."' 
									and substitution.groupnum='".$group."';";
	
	#echo $query;					
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
		
		foreach ($arr as $lesson => $data1){
			echo '<div class="sublesson" name="'.$lesson.'">';
				foreach($data1 as $teacher => $data2){
					echo '<div class="subteacher" name="'.$teacher.'">';
						foreach($data2 as $brignum => $data3)
							foreach($data3 as $ispractic => $data4){
								echo '<div class="subcalendar" 
											brignum="'.$brignum.'"
											ispractic="'.$ispractic.'">';
											
									foreach($data4 as $day => $hour){
										echo '<div class="subday" name="'.$day.'">'.$hour.'</div>';
									};
								echo '</div>';
							};
					echo '</div>';
				};
			echo '</div>';
		};
	};
	mysql_close($connection);
	
	?>

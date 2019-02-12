<?php
 if( isset($_POST['mounth']))
 {
	list($mounth,$year) = split("-",$_POST['mounth']);
	
	echo '<div class="Calendar" brignum="456">
			<div class="View" id="1">Теория</div> 
			<div class="brigNum" id="2">Бригада 2</div>
			<p>
		  ';
									
/*	$d = date("w", mktime(0, 0, 0, $mounth, 1, $year));
	$d = ($d==0)?7:$d;
	for($i=1; $i<$d; $i++)
	{
		echo '<input class="nullDay" readonly>
			';
	};
*/	
	$num = cal_days_in_month(CAL_GREGORIAN, $mounth, $year);
								
	$sum=0;
	for($j=1; $j<=$num; $j++)
	{
		echo '	<input class="calInputDay" value="'.$j.'" readonly>
			';
	};
								
	echo '	<input class="calAwerall" value="Итого: 0" readonly>
			<input class="calDelete"  value="X" readonly>
			</p>
			
			
			';
	echo '	';
	echo '</div>';
						
			
 };
?>

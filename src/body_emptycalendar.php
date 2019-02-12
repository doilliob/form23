<?php
 if( isset($_POST['group']) && isset($_POST['mounth']))
 {
 
	include("connection.php");
	
	$group = $_POST['group'];
	list($mounth,$year) = split("-",$_POST['mounth']);
	
	echo '<div class="Calendar" brignum="'.$brignum.'" ispractic="'.$ispractic.'">
		';
	echo '<input class="View" value="Теория" readonly>
		';
	echo '<input class="brigNum" value="Бригада" readonly>
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
	for($i=1; $i<$d; $i++)
	{
		echo '<input class="nullDay" readonly>
			';
	};
	
	$num = cal_days_in_month(CAL_GREGORIAN, $mounth, $year);
								
	$sum=0;
	for($j=1; $j<=$num; $j++)
	{
		echo '<input class="calInputDay" value="'.$j.'" readonly>
			';
	};
								
	echo '<input class="calAwerall" value="Итого:" readonly>';
	echo '<input class="calDelete" value="Удалить практику/теорию" readonly>';
	echo '</div>';
						
			
 
 };
?>

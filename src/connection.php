<?php
	$connection = mysql_connect("localhost","dbuser","dbpassword") or die(mysql_error());
	mysql_select_db("database");
	mysql_query("SET NAMES UTF8");

	if(!function_exists('cal_days_in_month')){
      define('CAL_GREGORIAN',1);
	  function cal_days_in_month($cal,$month, $year) {
	    return date('t', mktime(0, 0, 0, $month+1, 0, $year));
	  }
	}

	$my_year_now = '2018';
	$my_year_next = '2019';
?>

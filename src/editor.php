  <html>
	
	<head>
		<meta lang="ru">
		<meta charset="utf-8">
		<!-- Подключаю JQuery и Jquery UI -->
		<script type="text/javascript" src="ui/js/jquery-1.8.0.js"></script>
		<link type="text/css" href="ui/css/sunny/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="ui/js/jquery-ui-1.8.23.custom.min.js"></script>
		<!-- Мои скрипты -->
		<link type="text/CSS" rel="stylesheet"  href="styles/editor.css" />
		<link type="text/CSS" rel="stylesheet"  href="styles/calendar-flat.css" />
		<link type="text/CSS" rel="stylesheet"  href="styles/pager.css" />
		<script type="text/javascript" src="js/editor.js"></script>
		<title>Редактор часов для составления расписания ГБОУ СПО "СМК им. Н. Ляпиной"</title>
	</head>
	
	<body>
		<!-- Верхнее меню --------------------------------->
		<?php include('connection.php'); ?>
		<ul class="Pager">
			<li class="chooseGroup">Группа:
				<?php if(isset($_POST['group'])){ echo ' '.$_POST['group'];};?>
			</li>
			
			<li class="chooseMounth">Месяц:
				<?php 
				  if(isset($_POST['mounth'])){
					switch($_POST['mounth']){
						case "09-".$my_year_now : echo 'Сентябрь, '.$my_year_now.'г.';break;
						case "10-".$my_year_now : echo 'Октябрь, '.$my_year_now.'г.';break;
						case "11-".$my_year_now : echo 'Ноябрь, '.$my_year_now.'г.';break;
						case "12-".$my_year_now : echo 'Декабрь, '.$my_year_now.'г.';break;
						case "01-".$my_year_next : echo 'Январь, '.$my_year_next.'г.';break;
						case "02-".$my_year_next : echo 'Февраль, '.$my_year_next.'г.';break;
						case "03-".$my_year_next : echo 'Март, '.$my_year_next.'г.';break;
						case "04-".$my_year_next : echo 'Апрель, '.$my_year_next.'г.';break; 
						case "05-".$my_year_next : echo 'Май, '.$my_year_next.'г.';break; 
						case "06-".$my_year_next : echo 'Июнь, '.$my_year_next.'г.';break; 
						case "07-".$my_year_next : echo 'Июль, '.$my_year_next.'г.';break;
					};
				};?>
			</li>
			
			<li class="SaveAll">Сохранить все</li>
			<li class="chooseExit">Выход</li>
		</ul>
		
		<!-- Список групп (получаем из скрипта) ----------->	
		<ul class="listGroups">
			<?php include('editor_listgroups.php'); ?>
		</ul>
		<!-- Список месяцев ------------------------------->
		<ul class="listMounth">
				<li id="09-<?php echo $my_year_now; ?>">Сентябрь, <?php echo $my_year_now; ?>г.</li>
				<li id="10-<?php echo $my_year_now; ?>">Октябрь, <?php echo $my_year_now; ?>г.</li>
				<li id="11-<?php echo $my_year_now; ?>">Ноябрь, <?php echo $my_year_now; ?>г.</li> 
				<li id="12-<?php echo $my_year_now; ?>">Декабрь, <?php echo $my_year_now; ?>г.</li> 
				<li id="01-<?php echo $my_year_next; ?>">Январь, <?php echo $my_year_next; ?>г.</li> 
				<li id="02-<?php echo $my_year_next; ?>">Февраль, <?php echo $my_year_next; ?>г.</li> 
				<li id="03-<?php echo $my_year_next; ?>">Март, <?php echo $my_year_next; ?>г.</li> 
				<li id="04-<?php echo $my_year_next; ?>">Апрель, <?php echo $my_year_next; ?>г.</li> 
				<li id="05-<?php echo $my_year_next; ?>">Май, <?php echo $my_year_next; ?>г.</li> 
				<li id="06-<?php echo $my_year_next; ?>">Июнь, <?php echo $my_year_next; ?>г.</li> 
				<li id="07-<?php echo $my_year_next; ?>">Июль, <?php echo $my_year_next; ?>г.</li> 
		</ul>
			
		<!-- Скрипт выводящий список предметов и преподавателей------------->
		<fieldset>
			<?php include('editor_body.php'); ?>
		</fieldset>
		
		
		<!-- Добавление нового предмета ------------------------------------>
		<?php if( isset($_POST['group']) && isset($_POST['mounth']) ) { ?>
		<!------------------------------------------------------------------>
		<div class="newLesson">
			<p>Выберите предмет:</p>
			<select class="listLessons">
				<?php include('editor_listlessons.php'); ?>
			</select>
			<div id="Add">Добавить предмет</div>
		</div>
		<br>
		<div class="addToDBLesson">Дисциплины нет в списке?</div>
		<!------------------------------------------------------------------>
		<?php }; ?>
		<!------------------------------------------------------------------>
		
		<!-- Список преподавателей -->
		<div class="SelectTeacherForm">
			<p>Выберите преподавателя:</p>
			<select class="listTeachers">
				<?php include('editor_listteachers.php'); ?> 
			</select>
			<div id="Select">Добавить</div>
			<div id="Close">Закрыть</div>
		</div>
		
		<!-- Добавить Теорию/Практику -->
		<div class="AddCalendarForm">
			<p>Выберите вид занятия:</p>
			<select class="calView">
				<option>Теория</option>
				<option>Практика</option>
			</select>
			<p>Выберите бригаду:</p>
			<select class="calBrignum">
				<option>Вся группа</option>
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
			</select>
			<div id="Add">Добавить</div>
			<div id="Close">Закрыть</div>
		</div>
		
		
		<!-- Редактировать количество часов -->
		<div class="HoursEditForm">
			<div class="sHour" id="0">0</div>
			<div class="sHour" id="2">2</div>
			<div class="sHour" id="4">4</div>
			<div class="sHour" id="6">6</div>
			<div class="sHour" id="8">8</div>
			<p>Заместитель:</p>
			<select class="listTeachers">
				<option>Без заместителя</option>
				<?php include('editor_listteachers.php'); ?> 
			</select>
		</div>
		
		<!-- Пустой бланк календаря -->
		<div class="EmptyCalendar">
			<?php include('editor_emptycalendar.php'); ?>
		</div>
	
		<!-- Форма для отправки настроек ------------------>
			<form class="Settings" action="editor.php" method="POST">
				<input type="input" id="group"  name="group" <?php echo 'value="'.((isset($_POST['group']))?$_POST['group']:'').'"';?> >
				<input type="input" id="mounth" name="mounth" <?php echo 'value="'.((isset($_POST['mounth']))?$_POST['mounth']:'').'"';?> >
			</form>
			
		<!-- Новая форма для добавления предмета не из списка -->
		<div class="addToDBLessonForm">
			<label>Введите название дисциплины:</label>
			<input type="text">
			<div id="Add">Добавить</div>
			<div id="Close">Закрыть</div>
		</div>
		<br><br><br>
	</body>
</html>


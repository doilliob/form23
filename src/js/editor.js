 $(document).ready(function() {
	 //== Раздаем всем события ======================================
	 events_bind();
	 
	$('.listMounth, .listGroups,.EmptyCalendar,.AddCalendarForm, .HoursEditForm, .SelectTeacherForm,.addToDBLessonForm').hide();
	 
	 
	 
	//== ВЫХОД ======================================================
	$('.chooseExit').click(function(){
		$('<form action="mainmenu.php" method=POST></form>').submit();
	});
	
	//== Добавление несуществующего предмета ========================
	$('.addToDBLesson').click(function() {
		var teachForm = $('.addToDBLessonForm');
		var pos_top = $(window).scrollTop()+1;
		var pos_left = ($(window).width()-teachForm.width())/2;
		teachForm.css("position","absolute");
		teachForm.css("top", pos_top+'px');
		teachForm.css("left",pos_left+'px');
		teachForm.find('input').attr('value','');
		teachForm.slideDown('speed');
		
		teachForm.find('#Add').bind('click',function() {
		  if( 
		      (teachForm.find('input').attr('value') != '' ) &&
			  ($('.Settings > #mounth').attr('value') != '') && 
			  ($('.Settings > #group').attr('value') != '')
			){
				var group = $('.Settings > #group').attr('value');
				var day = ($('.Settings > #mounth').attr('value').split('-'))[0];
				day = (day.substr(0,1)==0) ? day.substr(1) : day;
				var polugodie = (day > 8) ? 1:2;
				var course = group.substr(1,1);
				//Сохранение
				  $.ajax({
							type: "POST",
							url:  "editor_addtodblesson.php",
							data: "groupnum=" + group + "&course=" + course + "&polugodie=" + polugodie + "&name=" + teachForm.find('input').attr('value') ,
							success: function(html) {
								// html is a string of all output of the server script.
								if( html == "OK" ){
									$('.newLesson > .listLessons').append('<option>' + teachForm.find('input').attr('value') + '</option>');
									teachForm.find('#Add,#Close').unbind('click');
									teachForm.slideUp('speed');
								}else{
									alert("ОШИБКА СОХРАНЕНИЯ ДАННЫХ - ОБРАТИТЕСЬ К АДМИНИСТРАТОРУ!!!!!!"+'|'+html+'|');
								};
							}

				 });
				
			};
		});
		
		teachForm.find('#Close').bind('click',function(){
			teachForm.find('#Add,#Close').unbind('click');
			teachForm.slideUp('speed');
		});
		
	});
	 
	//== ОФРМЛЕНИЕ ВЕРХНЕГО МЕНЮ ====================================
	$('.chooseMounth').click(function() {
		var voi = $('.listMounth');
		if(voi.attr('id')=='showen') {
				voi.slideUp('speed');
				voi.attr('id','hidden');
		}else{
				voi.slideDown('speed');
				voi.attr('id','showen');
			};
		
	});
	
	$('.chooseGroup').click(function() {
		var voi = $('.listGroups');
		if(voi.attr('id')=='showen') {
				voi.slideUp('speed');
				voi.attr('id','hidden');
		}else{
				voi.slideDown('speed');
				voi.attr('id','showen');
			};
	});
	
	$('.listGroups li').click(function() {
			$(this).parent().slideUp('speed');
			$('.chooseGroup').text('Группа: '+ $(this).text());
			$('.Settings #group').attr('value',$(this).text());
			if( $('.Settings #mounth').attr('value') != ""){
				$('.Settings').submit();
			}
	});
	
	$('.listMounth li').click(function() {
			$(this).parent().slideUp('speed');
			$('.chooseMounth').text('Месяц: '+ $(this).text());
			$('.Settings #mounth').attr('value',$(this).attr('id'));
			if( $('.Settings #group').attr('value') != ""){
				$('.Settings').submit();
			}
	});
	

	//===== ДОБАВИТЬ ПРЕДМЕТ ==============================
	$('.newLesson > #Add').click(function() {
		var curPos = $(window).scrollTop(); //Позиция прокрутки окна браузера
		var text = $('.newLesson > .listLessons > option:selected').val();
		var bol = 0;
		$('.lessonName').each(function() { if($(this).text() == text) {	bol=1; } });
		if(bol==0){
			$('fieldset').append('<div class="lesson"><div class="lessonName">'+text+'</div><div class="cAddTeacher">Добавить преподавателя</div><div class="cDelLesson">Удалить предмет</div></div>');
			events_bind();
		};
		$('html,body').animate({scrollTop:curPos},10);
	});
	
	
	//===== События =======================================
	function events_bind() {
		
		//Элементы интерфейса
		// Для каждого названия предмета
		$('.lessonName').each(function() {
			var lessonName = $(this);
			var parent = lessonName.parent();
			
			lessonName.unbind('click');
			lessonName.bind('click',function(){
				parent.find('.teacher').each(function() {
					if( lessonName.attr('id') == 'showen' ){
						$(this).slideDown('speed');
					}else{
						$(this).slideUp('speed');
					};
				});
				lessonName.attr('id', (lessonName.attr('id')=='showen')?'hidden':'showen');
			});
		});
		
		// Для каждого преподавателя
		$('.teacherName').each(function() {
			var lessonName = $(this);
			var parent = lessonName.parent();
			
			lessonName.unbind('click');
			lessonName.bind('click',function(){
				parent.find('.Calendar').each(function() {
					if( lessonName.attr('id') == 'showen' ){
						$(this).slideDown('speed');
					}else{
						$(this).slideUp('speed');
					};
				});
				lessonName.attr('id', (lessonName.attr('id')=='showen')?'hidden':'showen');
			});
		});
		
		/*
		// Для каждого управляющего элемента
		$('.cAddTeacher, .cDelLesson,.cAddCalendar,.cDelTeacher').hide();
		
		$('.lesson').each(function() {
			var lesson = $(this);
			lesson.unbind('mouseover');
			lesson.bind('mouseover',function() {
				$lesson.find('.cAddTeacher, .cDelLesson').each(function() {
					$(this).slideDown('slow');
				});
			});
	        lesson.bind('mouseout',function() {
				lesson.find('.cAddTeacher, .cDelLesson').each(function() {
					$(this).slideUp('speed');
				});
			});
		});
		
		
		$('.teacher').each(function() {
			var teacher = $(this);
			teacher.unbind('mouseover');
			teacher.bind('mouseover',function() {
				teacher.find('.cAddCalendar,.cDelTeacher').each(function() {
					$(this).slideDown('slow');
				});
			});
	        teacher.bind('mouseout',function() {
				teacher.find('.cAddCalendar,.cDelTeacher').each(function() {
					$(this).slideUp('speed');
				});
			});
		});
		*/
		
		//Для каждого УДАЛИТЬ УРОК
		$('fieldset > .lesson > .cDelLesson').each(function() {
			$(this).unbind('click');
			$(this).bind('click',function() {
				if( confirm("Вы действительно хотите удалить предмет?") )
				{
					var curPos = $(window).scrollTop(); //Позиция прокрутки окна браузера
					$(this).parent().remove();
					$('html,body').animate({scrollTop:curPos},10);
				};
			});
			
		});
		
		//Для каждого ДОБАВИТЬ ПРЕПОДАВАТЕЛЯ
		$('fieldset > .lesson > .cAddTeacher').each(function() {
					$(this).unbind('click');
					$(this).bind('click',function() {
						//$('body').append('<p>'+$(window).scrollTop());
						var current_lesson = $(this).parent();
						
						var teachForm = $('.SelectTeacherForm');
						var pos_top = $(window).scrollTop()+1;
						var pos_left = ($(window).width()-teachForm.width())/2;
						teachForm.css("position","absolute");
						teachForm.css("top", pos_top+'px');
						teachForm.css("left",pos_left+'px');
						teachForm.slideDown('speed');
						
						teachForm.find('#Select').bind('click',function() {
							var curPos = $(window).scrollTop(); //Позиция прокрутки окна браузера
							var teacher = teachForm.find('.listTeachers > option:selected').text();
							//Проверка
							var bot=0;
							current_lesson.find('.teacherName:contains(\''+teacher+'\')').each(function(){
								bot=1;
							});
							//Если пройдена
							if(bot==0){
								teachForm.find('#Select').unbind('click');
								teachForm.slideUp('speed');
								var teacher_html = $('<div class="teacher"></div>');
								teacher_html.append('		<div class="Selector">+</div>');
								teacher_html.append('		<div class="teacherName">'+teacher+'</div>');
								teacher_html.append('		<div class="cAddCalendar">Добавить Теорию/Практику</div>');
								teacher_html.append('		<div class="cDelTeacher">Удалить преподавателя</div>');
								current_lesson.append(teacher_html);
								$('html,body').animate({scrollTop:curPos},10);
								events_bind();
							};
						}); 
						teachForm.find('#Close').bind('click',function() {
							$(this).unbind('click');
							teachForm.find('#Select').unbind('click');
							teachForm.slideUp('speed');
						}); 
					});
		});
		
		//Для каждого УДАЛИТЬ ПРЕПОДАВАТЕЛЯ
		$('fieldset > .lesson > .teacher > .cDelTeacher').each(function() {
			$(this).unbind('click');
			$(this).bind('click',function() {
				var teacher = $(this).parent().find('.teacherName').text();
				if( confirm("Вы действительно хотите удалить преподавателя "+teacher+"?") ){
					var curPos = $(window).scrollTop(); //Позиция прокрутки окна браузера
					$(this).parent().remove();
					$('html,body').animate({scrollTop:curPos},10);
				};
			});
		});
		
		//Для каждого ДОБАВИТЬ ТЕОРИЮ/ПРАКТИКУ
		$('fieldset > .lesson > .teacher > .cAddCalendar').each(function() {
			$(this).unbind('click');
			$(this).bind('click',function() {
				    var curPos = $(window).scrollTop();
					var current_teacher = $(this).parent();
					
					var calForm = $('.AddCalendarForm');
					var pos_top = $(window).scrollTop()+1;
					var pos_left = ($(window).width()-calForm.width())/2;
					calForm.css("position","absolute");
					calForm.css("top", pos_top+'px');
					calForm.css("left",pos_left+'px');
					calForm.slideDown('speed');
					
					// Добавить календарь
					calForm.find('#Add').bind('click', function() {
						var view = calForm.find('.calView > option:selected').text();
						var brnum = calForm.find('.calBrignum > option:selected').text();
						
						//Проверка
						var bob = 0;
						current_teacher.find('.Calendar').each(function() {
							if( ($(this).attr('view') == view) && ($(this).attr('brignum') == brnum) ) bob=1;
						});
						
						//Если пройдена
						if(bob==0){
							calForm.find('#Add, #Close').unbind('click');
							calForm.slideUp('speed');
							//Заполняем календарь
							var cal = $('.EmptyCalendar > .Calendar');
							cal.attr('view',view);
							cal.attr('brignum',brnum);
							cal.find('.View').text(view);
							cal.find('.brigNum').text(((brnum == 'Вся группа')?brnum:'Бригада '+brnum));
							//Вставляем заполненный календарь
							var curPos = $(window).scrollTop(); //Позиция прокрутки окна браузера
							current_teacher.append($('.EmptyCalendar').html());
							$('html,body').animate({scrollTop:curPos},10);
							events_bind();						
						};
					});
			
					calForm.find('#Close').bind('click', function() {
						calForm.find('#Add','#Close').unbind('click');
						calForm.slideUp('speed');
					});
			$(window).scrollTop(curPos);
		});
		});
	
		//Для каждого УДАЛИТЬ ЗАНЯТИЕ
		$('fieldset > .lesson > .teacher > .Calendar > p > .calDelete').each(function(){
			$(this).unbind('click');
			$(this).bind('click',function(){
				if( confirm("Вы действительно хотите далить этот список часов?") ){
					var curPos = $(window).scrollTop(); //Позиция прокрутки окна браузера
					$(this).parent().parent().remove();
					$('html,body').animate({scrollTop:curPos},10);
				};
			});
			
		});//УДАЛИТЬ ЗАНЯТИЕ
		
		//Для каждого РЕДАКТИРОВАТЬ ЯЧЕЙКУ
		$('.calInputDay, .calSubDay, .calFullDay').each(function(){
				var cell = $(this);
				var calendar = cell.parent().parent();
				 
				$(this).unbind('click');
				$(this).bind('click',function(){
					// Показываем панель
					var editForm = $('.HoursEditForm');
					var pos_top = $(window).scrollTop()+1;
					var pos_left = ($(window).width()-editForm.width())/2;
					editForm.css("position","absolute");
					editForm.css("top", pos_top+'px');
					editForm.css("left",pos_left+'px');
					editForm.slideDown('speed');
					
					//Выставляем нуевые значения
					editForm.find(' .listTeachers > option:contains(\'Без заместителя\')').attr('selected','selected');
					
					// Если уже есть значения, выставляем их
					if( cell.attr('class') != 'calInputDay' ){
						
						//Выствим количество часов
						//***********************************************************************
						//editForm.find('#'+ cell.attr('value')).css('color','red');
						//************************************************************************
						
						//Если заместитель
						if( cell.attr('class') == 'calSubDay' )
						{
							editForm.find(' .listTeachers > option').each(function(){
								if( $(this).text() == cell.attr('subs') ) $(this).attr('selected','selected');
							});
							
						}else{
							editForm.find(' .listTeachers > option:contains(\'Без заместителя\')').attr('selected','selected');
						};
							
					};
					
					//Если нажата кнопка с Часом!!!!!
					editForm.find('.sHour').bind('click',function(){
						var hours = $(this).attr('id');
						var subs = editForm.find('.listTeachers > option:selected').text();
						
						if(hours > 0)//Если ненулевое значение
						{
							//Если нет заместителя
							if(subs == "Без заместителя"){
								if(cell.attr('class')=='calInputDay'){
									var day = cell.attr('value');
									cell.attr('id',day);
								};
								cell.attr('class','calFullDay');
								cell.attr('value',hours);
								cell.unbind('click');
							}else{
								//Если заместитель
								if(cell.attr('class')=='calInputDay'){
									var day = cell.attr('value');
									cell.attr('id',day);
								};
								cell.attr('class','calSubDay');
								cell.attr('value',hours);
								cell.attr('subs',subs); //<=Записываем заместителя
								cell.unbind('click');
							};//конец если заместитель
					
						}else{ //Если выставлено нулевое значение клетка превращается в нулевую
								var day = (cell.attr('class')=='calInputDay') ? cell.attr('value'):cell.attr('id');
								cell.attr('class','calInputDay');
								cell.attr('value',day);
								cell.unbind('click');
						};
						//Зачищаем следы
						editForm.find('.sHour').unbind('click');
						editForm.slideUp('speed');
						
						//Высчитываем сумму
						var summ = 0;
						calendar.find('p > .calFullDay').each(function(){
							summ += parseInt($(this).attr('value'));
						});
						calendar.find('p > .calAwerall').attr('value','Итого: '+summ+'ч');
						
						//Перезагружаем события
						events_bind();
					});
					
					
				});
			
		});
		
		
		
		
		//ОБЛАЧКО-ПОДСКАЗКА*****************************************
		$('.calFullDay, .calInputDay, .calSubDay').unbind('mouseover');
		$('.calFullDay, .calInputDay, .calSubDay').unbind('mouseout');
		
		$('.calFullDay, .calInputDay').mouseover(function(e){
			var text;
			switch($(this).attr('class')){
				case 'calFullDay': text = $(this).attr('id'); break;
				case 'calInputDay': text = $(this).attr('value'); break;
			};
			
			var cloud = $('<div class="Cloud">'+text+'</div>');
			$('body').append(cloud);
			cloud.css('left',e.pageX+20);
			cloud.css('top',e.pageY+20);
			
		});
		
		$('.calFullDay, .calInputDay').mouseout(function(e){
			$('.Cloud').each(function(){$(this).remove();});
		});
		
		//Облачко - подсказка заместителя
		$('.calSubDay').mouseover(function(e){
			var text = $(this).attr('subs');
			var cloud = $('<div class="SubCloud">'+text+'</div>');
			$('body').append(cloud);
			cloud.css('left',e.pageX+20);
			cloud.css('top',e.pageY+20);
			
		});
		
		$('.calSubDay').mouseout(function(e){
			$('.SubCloud').each(function(){$(this).remove();});
		});
		//************************************************************
		
	};
	
	// Процесс сохранения таблицы!!!!!!!!!!
	$('.SaveAll').click(function() {
		
		//Проверка
	  if( ($('.Settings > #mounth').attr('value') != '') && ($('.Settings > #group').attr('value') != '')) 
	  {
		
		var arr_full="";
		var arr_subs="";
		
		//Для каждого урока
		$('fieldset > .lesson').each(function(){
			// Предмет
			var lesson = $(this);
			lesson.find('.teacher').each(function() {
				// Преподаватель
				var teacher = $(this);
				teacher.find('.Calendar').each(function() {
					//Календарь
					var calendar = $(this);
					calendar.find('p > .calFullDay, p > .calSubDay').each(function(){
						//Ячейка
						var cell = $(this);
						if( cell.attr('class') == 'calFullDay' ){
							//Если просто часы
							//Находим дату
							var day = $('.Settings > #mounth').attr('value').split('-');
							day = day[1]+"-"+day[0]+"-"+(( parseInt(cell.attr('id')) > 9) ? cell.attr('id'): "0"+cell.attr('id'));
							arr_full += "|'"+lesson.find('.lessonName').text()+"','"+teacher.find('.teacherName').text()+"','"+calendar.attr('brignum')+"','"+calendar.attr('view')+"','"+  day +"',"+cell.attr('value');	
														
						}else{//Если совместительство
							//Находим дату
							var day = $('.Settings > #mounth').attr('value').split('-');
							day = day[1]+"-"+day[0]+"-"+(( parseInt(cell.attr('id')) > 9) ? cell.attr('id'): "0"+cell.attr('id'));
							arr_subs += "|'"+lesson.find('.lessonName').text()+"','"+teacher.find('.teacherName').text()+"','"+cell.attr('subs')+"','"+calendar.attr('brignum')+"','"+calendar.attr('view')+"','"+ day +"',"+cell.attr('value');
						};
						
					});
				});
			});
		});
	
	 //Постобработка	
	 arr_full = (arr_full != "")? arr_full.substr(1):"";
	 arr_subs = (arr_subs != "")? arr_subs.substr(1):""; 
	 //$('body').append('<p>F='+arr_full+'<p>S='+arr_subs);
	  //Сохранение
	  $.ajax({
				type: "POST",
				url:  "editor_save.php",
				data: "mounth="+$('.Settings > #mounth').attr('value')+"&group="+$('.Settings > #group').attr('value')+"&full="+arr_full+"&subs="+arr_subs,
				success: function(html) {
					// html is a string of all output of the server script.
					if( html == " OK" ){
						alert("Все данные успешно сохранены!");
					}else{
						alert("ОШИБКА СОХРАНЕНИЯ ДАННЫХ - ОБРАТИТЕСЬ К АДМИНИСТРАТОРУ!!!!!!"+html);
						//$('body').append(html);
					};
				}

	 });

    };//Проверка	
	});//****************************************
	/*
	//jQuery.fn.center = 
	function fcenter(e) {
		var x = $('.cCursor > .X').text();
		var y = $('.cCursor > .Y').text();
		
		e.css("position","absolute");
		e.css("top", (y + 10)  + "px");
		e.css("left",(x - e.width()/2) + "px");
		return e;
	};
	*/ 
 });

  $(document).ready(function() {

	console.log("YEP!");
	function SaveRecord(Teacher)
	{
		//ПРОВЕРКА НАДПИСЕЙ	
		
		//Получение параметров
		var ff  = Teacher.find('#fio').val();
		var fio = Teacher.find('#fio').val();
		var ekz_budg = Teacher.find('#ekz_budg').val();
		var ekz_nbudg = Teacher.find('#ekz_nbudg').val();
		var tarif_budg = Teacher.find('#tarif_budg').val();
		var tarif_nbudg = Teacher.find('#tarif_nbudg').val();
		var pred_budg = Teacher.find('#pred_budg').val();
		var pred_nbudg = Teacher.find('#pred_nbudg').val();
		var perc_budg = Teacher.find('#perc_budg').val();
		var perc_nbudg = Teacher.find('#perc_nbudg').val();
		var not_budg = Teacher.find('#not_budg').val();
		var not_nbudg = Teacher.find('#not_nbudg').val();
		
		$.post( "save.php", { 'fio' : fio,
									'ekz_budg' :  ekz_budg,
									'ekz_nbudg' : ekz_nbudg,
									'tarif_budg' : tarif_budg,
									'tarif_nbudg' : tarif_nbudg,
									'pred_budg' :  pred_budg,
									'pred_nbudg' : pred_nbudg,
									'perc_budg' : perc_budg,
									'perc_nbudg' : perc_nbudg,
									'not_budg' :  not_budg,
									'not_nbudg' : not_nbudg }, 
				function (data) {
					if( data == " ok" )
					{
						var bg = Teacher.css('background-color');
						Teacher.css('background-color','green');
						alert( "Данные о преподавателе успешно обновлены!");
						Teacher.css('background-color',bg);
					
					}else{
					    var bg = Teacher.css('background-color');
						Teacher.css('background-color','red');
						alert( "Произошла ошибка сохранения!" + data);
						Teacher.css('background-color',bg);
					};
				});
								
		
		
	};
	
	// Подсчет целых 5%
	function Percent(Teacher)
	{
		var tarif_budg = Teacher.find('#tarif_budg').val();
		var tarif_nbudg = Teacher.find('#tarif_nbudg').val();
		Teacher.find('#perc_budg').val((tarif_budg*0.05).toFixed());
		Teacher.find('#perc_budg').css("background-color","green");
		Teacher.find('#perc_nbudg').val((tarif_nbudg*0.05).toFixed());
		Teacher.find('#perc_nbudg').css("background-color","green");
	};
	
	function unbindAll()
	{
		$('.Teacher').each(function() {
			$(this).find('.Save').unbind("click");
			$(this).find('.Percent').unbind("click");
		});
	};
	
	function bindAll()
	{	
		$('.Teacher').each( function() {
			var T = $(this);
			$(this).find('.Save').bind("click", function () {
				SaveRecord(T);
			});
			$(this).find('.Percent').bind("click", function () {
				Percent(T);
			});
		});
	};
	
	 //Получает список преподавателей по запросу
	 function getTeachers(){
		console.log("YEP3");
		var value = $('#search').val();
		var bod = $.post("body.php",{ search : value }, function (data) {
			console.log(data);
			unbindAll();
			$('#result').html(data);
			bindAll();
		});
	 };
	 
	 // Бинд введения символа в окно поиска
	 $("#search").keyup(function() {
			getTeachers();
		});
	 
	// Выводим вначале список всех преподавателей
	getTeachers();
	console.log("YEP2!");
	 
	 
 });
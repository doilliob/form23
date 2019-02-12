 $(document).ready(function() {
	
	$('.listMounth, .listGroups, .Settings').hide();
	
	//== ВЫХОД ======================================================
	$('.chooseExit').click(function(){
		$('<form action="mainmenu.php" method=POST></form>').submit();
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
	
	
});	

 $(document).ready( function() {
	 
		// Прячем все формы
		$('.chooseGroup').hide();
		$('.chooseDate').hide();
		$('.chooseSpec').hide();
		
		
		// Добавляем список сециальностей из myTree
		$('#myTree > .cTreeSpec').each(function() {
			var name = $(this).attr('id');
			$('.chooseSpec').append('<div class="cSpec" >' + name + '</div>');
		});
		
		$('.chooseSpec').slideDown("speed");
		$('.chooseSpec > .cSpec').click(function() {
				$('.chooseSpec').slideUp('speed');
				var selected = $(this).text();
				$('#myTree > .cTreeSpec[id="' + selected + '"] > .cTreeGroup').each(
					function() {
						var id = $(this).attr('id');
						var value = $(this).text();
						$('.chooseGroup').append('<input class="cGroup" id="'+id+'" value="'+value+'" readonly>');
				});
				$('.chooseGroup').slideDown("speed");
		});
		
		
		/*
		$('.chooseGroup').slideDown("speed");
		$('.chooseGroup > .cGroup').click(function() {
			
			$('#sendingForm > #group').attr('value',$(this).attr('id'));
			$('.chooseGroup').slideUp("speed");
			
		});*/
	 
	 
 });

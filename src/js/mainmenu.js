$(document).ready(function() {
	  //$('body').append('dasddasdasd'); 
	  $('ul > li').click(function(){
			
			var dest;
			var id = $(this).attr('id');
			switch ( id ){
				case '1': dest='editor.php';break;
				case '2': dest='f2.php';break;
				case '3': dest='f3.php';break;
				case '4': dest='base_addteachers.php';break;
				//case '5': dest='tarif/tarif.php';break;
			};
			
			
			$('<form action="' + dest + '" method=POST></form>').submit();
		  
		  
	  });
	  
});

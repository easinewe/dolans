jQuery(document).ready(function($){
    
	$(window).resize(function() { 
		var gc_height = $('.greeting_container').height();
		var gc_width = $('.greeting_container').width();
		//$('.greeting_container').css('height', gc_height );
		//$('.greeting_container').css('width', gc_width );
		//$('.greeting_container').css('margin-right', ((gc_width/2)*-1) );
		//$('.greeting_container').css('margin-top', ((gc_height/2)*-1) );
	});
	
	
	//mobile nav control
	$('#hamburger_menu').toggle(function() {
	  $('#navigation ul').css('height','35rem');
	  $('#cover-up').css('height','100%');
	  $('#cover-up').css('opacity','0.8');

	}, function() {
	  $('#navigation ul').css('height','0rem');
	  $('#cover-up').css('height','0');
	  $('#cover-up').css('opacity','0');
	});
	
	
	  //var gc_height = $('.greeting_container').height();
	  //var gc_width = $('.greeting_container').width();
	  //$('.greeting_container').css('height', gc_height );
	  //$('.greeting_container').css('width', gc_width );
	  //$('.greeting_container').css('margin-right', ((gc_width/2)*-1) );
	  //$('.greeting_container').css('margin-top', ((gc_height/2)*-1) );

	//get the time....
	  var currentTime = new Date();
	  var hours = currentTime.getHours();
	  var minutes = currentTime.getMinutes();
	  
	  if (minutes < 10) {
		  minutes = "0" + minutes;
	  }
	  var greeting;
	  var good_morning 		=	'Dia dhuit ar maidin'; //good morning
	  var good_afternoon 	=	'Fáilte';				//welcome
	  var good_evening 		=	'Tráthnóna maith&nbsp;duit'; //good evening
	  
	  if( hours > 5  && hours < 12){
		greeting = good_morning;
	  }
	  else if( hours > 12  && hours < 17 ){
		greeting = good_afternoon;
	  } else {
		greeting = good_evening;
	  }
	  
	  $('#galic_greeting').html(greeting);
	//end
  
  
	//set initial position of family tree  
    if( $('#dynamic_family_tree').length ){
		
		leftPostion = $('#70').offset().left;
		topPostion = $('#70').offset().top;

		$('html,body').animate({
			scrollTop: topPostion + 300,
			scrollLeft:  leftPostion
		}, 800, function(){
			$('html,body').clearQueue();
		});
	}
    
	//adjust the height of the family-tree div
	if( $('#dynamic_family_tree').length || $(window).resize){
		nav = $('#navigation').height();
		wpadminbar = $('#wpadminbar').height();
		fullpage = $(document).height();

		adjustedHeight = fullpage - (wpadminbar + nav);
		$('#family_tree').css('height', adjustedHeight );

	}

});



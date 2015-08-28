	$(document).ready(function() {
		// EDIT: find out if the endpoint button is checked
			var continue_story = $(".endtarget").is(':checked');
			if (continue_story==false) {	
					$('#edit-options').toggle();
			}
			$('.endtarget').change(function() {
			 	if ( $(this).is(':checked')){
					$('#edit-options').slideDown();
				} else {
					$('#edit-options').slideUp();
				}
			});
	
		
		// READ: display edit link on hover over text
		// find the edit link and hide it (it defaults to visible for no-js)
		
		$("#page #edit-chapter").on("textHover",  function(e){
			$(this).fadeIn(200);
		});
		
		$("#page #edit-chapter").on("mouseleft",  function(e){
			$(this).fadeOut(400);
		});
		
		$("#chapter-text").hover(function(){
			$("#page #edit-chapter").trigger("textHover");
		});
		
		$("#chapter-text").mouseleave(function(){
			$("#page #edit-chapter").trigger("mouseleft");
		});
		
		$("#page #edit-chapter").on("mouseenter", function(e){
			console.log("stopping");
			$(this).stop().animate({opacity:'100'});
		});
		$("#page #edit-chapter").on("mouseleave", function(e){
			$(this).trigger("mouseleft");
		});
		
		// Autofill text in form fields
	  $('#login-form input.username').autofill({
	    value: "enter username"
	  });
	 $('#login-form input.password').autofill({
	    value: "enter password"
	  });
	});
	
	// Auto-Fill Plugin
	// Written by Joe Sak http://www.joesak.com/2008/11/19/a-jquery-function-to-auto-fill-input-fields-and-clear-them-on-click/
	(function($){
	  $.fn.autofill = function(options){
	    var defaults = {
	      value:'',
	      defaultTextColor:"#868378",
	      activeTextColor:"#0E7B96",
	      password: false
	    };
	    var options = $.extend(defaults,options);
	    return this.each(function(){
	      var obj=$(this);
	      obj.css({color:options.defaultTextColor})
	        .val(options.value)
	        .focus(function(){
	          if(obj.val()==options.value){
	            obj.val("").css({color:options.activeTextColor});
	            if (options.password && obj.attr('type') == 'text') {
	              obj.attr('type', 'password');
	            }
	          }
	        })
	        .blur(function(){
	          if(obj.val()==""){
	            obj.css({color:options.defaultTextColor}).val(options.value);
	            if (options.password && obj.attr('type') == 'password') {
	              obj.attr('type', 'text');
	            }
	          }
	        });
	    });
	  };
	})(jQuery);
$(document).ready(function() {
		// EDIT: find out if the endpoint button is checked
  var end_story = $("#endpoint").is(':checked');
	if (end_story==true) {
	  $('#edit-options').toggle();
	}
	$('#endpoint').change(function() {
		console.log('change!')
	 	if ( $(this). prop("checked") == false ){
			$('#edit-options').slideDown();
		} else {
			$('#edit-options').slideUp();
		}
	});

	// Confirm delete
	$(".delete").click(function(){
		var answer = confirm('Are you sure you want to delete?');
	    return answer // answer is a boolean
	});

	// auto focus on the right fields at right times
	$("input#new-story").focus();
	$(".new textarea#edit-chapter-text").focus();

	// Handle the menu in mobile sites
	var w = $(document).width();
	if (w<=768){
		// we have small screen, so hide menu
		$("#primary-navigation").addClass("collapsible-menu").hide();
		$("#menu-link").addClass("expander").toggle(function(){
			$("#primary-navigation").slideDown('fast');
		}, function(){
		  $("#primary-navigation").slideUp('fast');
		});
	}

});





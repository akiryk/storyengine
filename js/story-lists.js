$(document).ready(function() {
	// var stories = $("ul.my-stories li");
	// var str;
	// var num;
	// var numArray = new Array();
	// for (var i=0; i<stories.length; i++){
	// 	// get the class name (e.g. 'strength8')
	// 	str = $(stories[i]).attr("class");
	// 	// get the number from end of class (e.g. '8')
	// 	num = str.substring(8,str.length);
	// 	numArray.push(parseInt(num)); // convert string to number
	// }
	// 
	// // we now have an array of numbers. Find the highest and the lowest.
	// var largest = Math.max.apply(Math, numArray);
	// var smallest = Math.min.apply(Math, numArray);
	// 
	// var diff = largest - smallest;
	// // x = what percent of diff?
	// // what = diff/x
	// for (var i=0; i<stories.length; i++){
	// 	var x = numArray[i];
	// 	var d = x-smallest;
	// 	var percent;
	// 	if (d <= 0) {
	// 		percent = 0;
	// 	} else {
	// 		percent = d/diff; 	// a number from 0.0 to 1.0
	// 	}
	// 
	// 	$(stories[i]).find("span").css('opacity', percent);
	// }
	
	// create a delete confirmation
	$("li.delete").click(function(){
		var answer = confirm('Are you sure you want to delete?');
	    return answer // answer is a boolean
	});
	
});


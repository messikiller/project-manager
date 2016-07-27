$(function(){
	var w_height = $(window).outerHeight();
	var nav_height = $('#header').outerHeight();
	var wrap_height = w_height - nav_height;
	$('#wrap .lefter').css('min-height', wrap_height);
	$('#wrap .main').css('min-height', wrap_height);
	$('#wrap .main .content-frame').css('min-height', wrap_height);
});
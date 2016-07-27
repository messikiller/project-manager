$('.lefter dd > a').each(function(){
	$(this).click(function(){
		$('.lefter dd > a.active').removeClass('active');
		$(this).removeClass('active').addClass('active');
	});
});
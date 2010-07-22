//Loader Fadein
$(document).ajaxStart(function(){
	$("#footer_loading_text").html("Loading");
	$("#footer_loading").stop(true,true).fadeIn(500);
});
//Loader Fadeout
$(document).ajaxStop(function(){
	if($("#footer_loading_text").html() != "Error Loading")
	{
		$("#footer_loading").fadeOut(500);
	}
});
//Loader Error
$(document).ajaxError(function(event, request, settings){
	$("#footer_loading_text").html("Error Loading");
	if($("#footer_loading").css('display') == 'none')
	{
		$("#footer_loading").stop(true,true).fadeIn(500);
	}
	setTimeout(function(){
		$("#footer_loading").fadeOut(500);
	}, 3000);
});
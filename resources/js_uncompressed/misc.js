$(document).ready(function(){
	$("#login_username_input input").focus(function(){
		if($("#login_username_input").hasClass("greyinput"))
		{
			$("#login_username_input").removeClass("greyinput");
			$(this).val('');
		}
	});
	$("#login_username_input input").blur(function(){
		if(!$("#login_username_input").hasClass("greyinput") && !$(this).val())
		{
			$("#login_username_input").addClass("greyinput");
			$(this).val('Username');
		}
	});
	$("#login_password_input input").focus(function(){
		if($("#login_password_input").hasClass("greyinput"))
		{
			$("#login_password_input").removeClass("greyinput");
			$(this).val('');
		}
	});
	$("#login_password_input input").blur(function(){
		if(!$("#login_password_input").hasClass("greyinput") && !$(this).val())
		{
			$("#login_password_input").addClass("greyinput");
			$(this).val('password');
		}
	});
});

function LoadStatus()
{
	$.ajax({
		url:"includes/ajax/server_status.php",dataType:"json",data:{sure: 'yea'},type:"POST",
		beforeSend: function() {
			$("#status_table").mask("<img src='images/cataclysm/mask-loader.gif' alt='Loading...' height='21' width='56' />");
		},
		success: function(data){
			totalonline = 0;
			totalhorde = 0;
			totalalliance = 0;
			for(x in data) {
				totalonline += parseInt(data[x]['online']);
				totalhorde += parseInt(data[x]['horde']);
				totalalliance += parseInt(data[x]['alliance']);
				if(data[x]['status']) {
					$("#status_loadingicon_" + x).stop().hide();
					$("#status_downarrow_" + x).stop().hide();
					$("#status_uparrow_" + x).stop().fadeIn(500);
				}
				else {
					$("#status_loadingicon_" + x).stop().hide();
					$("#status_uparrow_" + x).stop().hide();
					$("#status_downarrow_" + x).stop().fadeIn(500);
				}
				$("#status_online_" + x).html(data[x]['online']);
				$("#status_alliance_" + x).html(data[x]['alliance']);
				$("#status_horde_" + x).html(data[x]['horde']);
				$("#status_alliance_" + x).css("width", (parseInt(data[x]['alliance'])+1) * 189 / (parseInt(data[x]['online'])+2));
				$("#status_horde_" + x).css("width", (parseInt(data[x]['horde'])+1) * 189 / (parseInt(data[x]['online'])+2));
				$("#status_uptime_" + x).html(data[x]['uptime']);
				$("#status_maxonline_" + x).html(data[x]['maxplayers']);
				$("#status_loader_" + x).hide();
				$("#status_table").unmask();
				$("#status_content_" + x).fadeIn(500);
			}
			$("#status_online_total").html(totalonline);
			$("#status_horde_total").html(totalhorde);
			$("#status_alliance_total").html(totalalliance);
			$("#status_horde_total").css("width", (totalhorde+1) * 189 / (totalonline+2));
			$("#status_alliance_total").css("width", (totalalliance+1) * 189 / (totalonline+2));
			$("#status_loader_total").hide();
			$("#status_content_total").fadeIn(500);
		},
		error: function(){
			
		}
	});
	setTimeout("LoadStatus()", 120000);
}
$(document).ready(function() {
	LoadStatus();
});
$(window).load(function(){
$('#slider').nivoSlider({
effect:'random', //Specify sets like: 'fold,fade,sliceDown'
slices:15,
animSpeed:500, //Slide transition speed
pauseTime:5000,
directionNav:true, //Next & Prev
directionNavHide:true, //Only show on hover
controlNav:true, //1,2,3...
controlNavThumbs:true, //Use thumbnails for Control Nav
pauseOnHover:true, //Stop animation while hovering
captionOpacity:0.8 //Universal caption opacity
});
});
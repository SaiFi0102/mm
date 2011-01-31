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
$(document).ready(function(){$("#login_username_input input").focus(function(){if($("#login_username_input").hasClass("greyinput")){$("#login_username_input").removeClass("greyinput");$(this).val('')}});$("#login_username_input input").blur(function(){if(!$("#login_username_input").hasClass("greyinput")&&!$(this).val()){$("#login_username_input").addClass("greyinput");$(this).val('Username')}});$("#login_password_input input").focus(function(){if($("#login_password_input").hasClass("greyinput")){$("#login_password_input").removeClass("greyinput");$(this).val('')}});$("#login_password_input input").blur(function(){if(!$("#login_password_input").hasClass("greyinput")&&!$(this).val()){$("#login_password_input").addClass("greyinput");$(this).val('password')}})});function LoadStatus(){$.ajax({url:"includes/ajax/server_status.php",dataType:"json",data:{sure:'yea'},type:"POST",success:function(data){for(x in data){if(data[x]['status']){$("#status_loadingicon_"+x).stop().hide();$("#status_downarrow_"+x).stop().hide();$("#status_uparrow_"+x).stop().fadeIn(500)}else{$("#status_loadingicon_"+x).stop().hide();$("#status_uparrow_"+x).stop().hide();$("#status_downarrow_"+x).stop().fadeIn(500)}$("#status_online_"+x).html(data[x]['online']);$("#status_alliance_"+x).html(data[x]['alliance']);$("#status_horde_"+x).html(data[x]['horde']);$("#status_uptime_"+x).html(data[x]['uptime']);$("#status_maxonline_"+x).html(data[x]['maxplayers']);$("#status_loader_"+x).hide();$("#status_content_"+x).fadeIn(500)}},error:function(){}});setTimeout("LoadStatus()",120000)}$(document).ready(function(){LoadStatus()});
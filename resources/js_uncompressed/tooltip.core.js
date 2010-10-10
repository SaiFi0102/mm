$(document).ready(function()
{
	$("body").append("<div id='aToolTip' style='position:absolute;'></div>");
});
var timeout1 = null;
var timeout2 = null;
$.fn.tooltip = function(o, extra)
{
	this.each(function(i)
	{
		o = $.extend( { //defaults, can be overidden
			content: (typeof o == "string") ? o : null, //string content for tooltip.
			ajax: null, //path to content for tooltip
			html: false, //Allow HTML or not
			follow: false, //does tooltip follow the cursor?
			offsetY: 10, //offsetY and offsetX properties designate position from the cursor
			offsetX: 10,
			callBefore: function(tooltip,element,settings,event) {}, //called when mouse enters the area
			callAfter: function(tooltip,element,settings,event) {}, //called when mouse leaves the area (same as "callback" option)
			clickAction: function (tooltip,element,settings,event) {}, //called when the element is clicked, with access to tooltip
			delay: 0, //delay (in milliseconds)before tooltip appears and callBefore executes
			timeout: 0, //delay (in milliseconds)before tooltip transitions away, and callAfter executes
			opacity: 100, //Opacity of tooltip in percentage
			constantx: "right", //Works with Right, left and null
			constanty: null, //Works with Top and Bottom and null
			switchside: false //Switches the tooltip to other other side of the cursor if it reaches maximum height or width
		}, o || {});
		
		var root = this;
		var tooltip = document.getElementById('aToolTip');
		var hovered = false;
		var active = false;
		                                                 
		
		$(tooltip).hide();
		$(tooltip).css({ position: 'absolute', zIndex: '100000' });
		
		$(this).click(function(ev)
		{
			var e = ( ev ) ? ev : window.event;
			if(typeof o.clickAction == "function")
			{
				o.clickAction(tooltip, root, o, e);
			}
		});
		
		$(this).mouseover(function(ev)
		{
			var e = ( ev ) ? ev : window.event;
			show(e);
		});
		
		$(this).mouseout(function(ev)
		{
			var e = ( ev ) ? ev : window.event;
			hide(e);
		});
		
		$(this).mousemove(function(ev)
		{
			if(active && o.follow)
			{
				var e = ( ev ) ? ev : window.event;
				move(e, 100);
			}
		});
		
		function show(e)
		{
			hovered = true;
			if(o.ajax != null)
			{
				$.get(o.ajax, function(data) {
					if(data)o.content = data;
				});
			}
			if(o.content != null)
			{
				var innerhtml;
				if(!o.html) innerhtml = "<div class='tooltip'>";
				if(o.html) innerhtml = o.content;
				else innerhtml += o.content.replace(/<\/?[^>]+>/gi, '');
				if(!o.html) innerhtml += "</div>";
				$(tooltip).html(innerhtml);
			}
			if(o.opacity != null)
			{
				$(tooltip).attr({opacity: o.opacity});
			}
			opacity = o.opacity / 100;
			$(tooltip).css('opacity',opacity)
			clearTimeout(timeout2);
			timeout1 = setTimeout(function() {
				if(hovered && !active)
				{
					active = true;
					move(e, 0);
					$(tooltip).show();
					if(typeof o.callBefore == "function")
					{
						o.callBefore(tooltip, root, o, e);
					}
					if(!o.follow)
					{
						if(o.constantx == "left")
						{
							$(tooltip).animate({left: "-="+o.offsetX}, {duration: 200, queue: false});
						}
						else if(o.constantx == "right")
						{
							$(tooltip).animate({left: "+="+o.offsetX}, {duration: 200, queue: false});
						}
						if(o.constanty == "top")
						{
							$(tooltip).animate({top: "-="+o.offsetY}, {duration: 200, queue: false});
						}
						else if(o.constanty == "bottom")
						{
							$(tooltip).animate({top: "+="+o.offsetY}, {duration: 200, queue: false});
						}
					}
				}
			}, o.delay);
		}
		
		function hide(e)
		{
			hovered = false;
			if(active)
			{
				clearTimeout(timeout1);
				timeout2 = setTimeout(function() {
					if(!hovered)
					{
						$(tooltip).hide();
						active = false;
						if(typeof o.callAfter == "function")
						{
							o.callAfter(tooltip, root, o, e);
						}
					}
				}, o.timeout);
			}
		}
		
		function move(e, speed)
		{
			var scrollY = $(window).scrollTop();
			var scrollX = $(window).scrollLeft();
			if(o.follow)
			{
				var top = e.clientY + scrollY + o.offsetY;
				var left = e.clientX + scrollX + o.offsetX;
			}
			else
			{
				var top = $(root).offset().top;
				var left = $(root).offset().left + $(root).width();
				if(o.constantx == "left") left += o.offsetX;
				if(o.constanty == "top") top += o.offsetY;
			}
			
			//MAXING STUFF START
			var maxLeft = $( window ).width() + scrollX - $(tooltip).outerWidth();
			var maxTop = $( window ).height() + scrollY - $(tooltip).outerHeight();
			maxed = ( top > maxTop || left > maxLeft ) ? true : false;
			if(left - scrollX <= 0 && o.offsetX < 0)
			{
				left = scrollX;
			}
			else
			{
				if(left > maxLeft) 
				{
					if(o.switchside)
					{
						left -= $(tooltip).width() + 26;
					}
					else
					{
						left = maxLeft;
					}
				}
			}
				
			if(top - scrollY <= 0 && o.offsetY < 0)
			{
				top = scrollY;
			}
			else
			{
				if(top > maxTop)
				{
					if(o.switchside)
					{
						top -= $(tooltip).height() + 26;
					}
					else
					{
						top = maxTop;
					}
				}
			}
			//MAXING STUFF END
			$(tooltip).css({top: top, left: left});
		}
		
		if(extra == 'show')
		{
			$(document).one("mouseover", function(e){
				show(e);
			});
		}
		if(extra == 'hide')
		{
			$(document).one("click", function(e)
			{
				hide(e);
			});
		}
		
	});
};

function toolTip(element, o, ev)
{
	$(element).tooltip(o, 'show');
}
$.fn.PageSort = function(o)
{
	this.each(function(i)
	{
		var root = this;
		var TotalElements = 0;
		
		
		o = $.extend(
		{
			ElementsPerPage: 10, //Elements Per Page
			OrderColumn: null, //Column to order by
			OrderMethod: "DESC", //Order by method: ASC or DESC only
			
			Columns: {}, //{nameincolumn: "nameindatabase"}
			
			JSONFile: null //includes/json/xyz.json.php
		}, o || {});
		
		function LoadTotal()
		{
			$.ajax(
			{
				url: o.JSONFile,
				dataType: "html",
				data: {data: "totalonly"},
				type: "POST",
				
				success: function(msg)
				{
					TotalElements = parseInt(msg);
					return true
				},
				
				error: function()
				{
					return false;
				}
			});
		}
	});
}

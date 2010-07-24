var TotalElements = 0;
var TotalPages = 1;

var CurrentPage = 1;
var PageSortError = false;
var PrevAjaxLoad;

var LocationHref = window.location.href;
HrefPageNo = LocationHref.match(/#Page:([0-9]+)/i);
if(HrefPageNo != null)CurrentPage = parseInt(HrefPageNo[1]);

$.fn.PageSort = function(o)
{
	this.each(function(i)
	{
		var root = this;
		
		o = $.extend(
		{
			ElementsPerPage: 10, //Elements Per Page
			OrderColumn: null, //Column to order by
			OrderMethod: "DESC", //Order by method: ASC or DESC only
			
			Columns: {}, //{nameincolumn: "nameindatabase"}
			
			PagesTableContainer: null,
			PagesTableNum: 3,
			PagesTableStyle: "3ColWide", //Values: 3ColWide
			
			CallBeforeLoadTotal: function (){},
			CallAfterLoadTotal: function (TotalElements){},
			CallOnLoadTotalError: function (XMLHttpRequest, textStatus, errorThrown){},
			
			CallBeforeLoad: function (){},
			CallAfterLoad: function (JSONData){},
			CallOnLoadError: function (XMLHttpRequest, textStatus, errorThrown){},
			
			CallOnError: function (XMLHttpRequest, textStatus, errorThrown){},
			
			JSONFile: null //includes/json/xyz.json.php
		}, o || {});
		
		/**
		 * ************* FUNCTIONS **************
		 */
		//Load Total Elements
		function LoadTotal(callback)
		{
			//Call Before Loading Total
			$(o.PagesTableContainer).hide();
			o.CallBeforeLoadTotal();
			
			//Load
			$.ajax(
			{
				url: o.JSONFile,
				dataType: "html",
				data: {data: "totalonly"},
				type: "POST",
				
				success: function(total)
				{
					TotalElements = parseInt(total);
					TotalPages = Math.floor(TotalElements/o.ElementsPerPage);
					if(CurrentPage > TotalPages)
					{
						CurrentPage = TotalPages;
					}
					o.CallAfterLoadTotal(total);
					callback();
				},
				
				error: function(XMLHttpRequest, textStatus, errorThrown)
				{
					o.CallOnLoadTotalError(XMLHttpRequest, textStatus, errorThrown);
					o.CallOnError(XMLHttpRequest, textStatus, errorThrown);
					PageSortError = true;
				}
			});
		}
		
		function LoadJSONData(callback)
		{
			//Call before loading data
			o.CallBeforeLoad();
			
			//Load
			PrevAjaxLoad = $.ajax(
			{
				url: o.JSONFile,
				dataType: "json",
				data: {data: "JSONData", ordercolumn: o.OrderColumn, ordermethod: o.OrderMethod, limit: GetLimit()},
				type: "POST",
				
				success: function(JSONData)
				{
					o.CallAfterLoad(JSONData);
					callback();
				},
				
				error: function(XMLHttpRequest, textStatus, errorThrown)
				{
					o.CallOnLoadError(XMLHttpRequest, textStatus, errorThrown);
					o.CallOnError(XMLHttpRequest, textStatus, errorThrown);
					PageSortError = true;
				}
			});
			
			//Return bool
			if(PageSortError == true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		function GetLimit()
		{
			limit = (o.ElementsPerPage * (CurrentPage - 1));
			limit += ",";
			limit += o.ElementsPerPage;
			
			return limit;
		}
		
		function WritePagesTable(callback)
		{
			if(o.PagesTableContainer == null)
			{
				return;
			}
			
			switch(o.PagesTableStyle)
			{
				case "3ColWide":
				break;
				default:
					o.PagesTableStyle = "3ColWide";
				break;
			}

			if(o.PagesTableStyle == "3ColWide")
			{
				//Next and Previous
				pagetablehtml = '';
				if(CurrentPage > 1)
				{
					pagetablehtml += '<div class="left" style="font-size: 20px"><a class="switchpage" href="#Page:'+(CurrentPage-1)+'">Previous</a></div>';
				}
				if(CurrentPage < TotalPages)
				{
					pagetablehtml += '<div class="right" style="font-size: 20px"><a class="switchpage" href="#Page:'+(CurrentPage+1)+'">Next</a></div>';
				}
				
				pagetablehtml += '<div align="center" style="font-size: 15px"><table cellpadding="2px" cellspacing="0" border="1px"><tr>';
				
				//First Page
				if(CurrentPage > (o.PagesTableNum+1))
				{
					pagetablehtml += '<td><a class="switchpage" href="#Page:1">1</a></td><td>...</td>'
				}
				
				//Looping
				pi = o.PagesTableNum;
				while(pi >= 0)
				{
					if((CurrentPage - pi) < 1)
					{
						pi--;
						continue;
					}
					if((CurrentPage - pi) >= CurrentPage)
					{
						break;
					}
					pagetablehtml += '<td><a class="switchpage" href="#Page:'+(CurrentPage - pi)+'">'+(CurrentPage - pi)+'</a></td>';
					pi--;
				}
				
				pagetablehtml += '<td>'+CurrentPage+'</td>';
				
				fi = 1;
				while(fi <= o.PagesTableNum)
				{
					if((fi + CurrentPage) > TotalPages)
					{
						break;
					}
					pagetablehtml += '<td><a class="switchpage" href="#Page:'+(CurrentPage + fi)+'">'+(CurrentPage + fi)+'</a></td>';
					fi++;
				}
				
				//Last Page
				if((CurrentPage + fi) <= TotalPages)
				{
					pagetablehtml += '<td>...</td><td><a class="switchpage" href="#Page:'+TotalPages+'">'+TotalPages+'</a></td>'
				}
				
				pagetablehtml += '</tr></table></div>';
				
				//Clear Float
				pagetablehtml += '<div class="clear"></div>';
			}
			
			$(o.PagesTableContainer).html(pagetablehtml).stop(true,true).fadeIn(500);
			delete pagetablehtml;
			callback();
		}
		
		function BindPageSwitch()
		{
			$("a[class=switchpage]").click(function()
			{
				var StrToPageNum = $(this).attr('href');
				var MatchPageNum = StrToPageNum.match(/#Page:([0-9]+)/i);
				if(MatchPageNum != null)
				{
					CurrentPage = parseInt(MatchPageNum[1]);
				}
				else
				{
					CurrentPage = 1;
				}
				if(typeof(PrevAjaxLoad.abort) == "function")
				{
					PrevAjaxLoad.abort();
				}
				Load();
			});
		}
		
		function Load()
		{
			if(typeof(CurrentPage) != "number")
			{
				CurrentPage = 1;
			}
			if(CurrentPage < 1)
			{
				CurrentPage = 1;
			}
			
			LoadTotal(function()
			{
				if(!PageSortError)
				{
					LoadJSONData(function()
					{
						if(!PageSortError)
						{
							WritePagesTable(BindPageSwitch);
						}
					});
				}
			});
		}
		
		/**
		 * ************* INITIALIZE *************
		 */
		if(o.JSONFile == null)
		{
			return false;
		}
		Load();
	});
}

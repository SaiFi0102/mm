var TotalElements=0;var TotalPages=1;var CurrentPage=1;var PageSortError=false;var PrevAjaxLoad;var LocationHref=window.location.href;HrefPageNo=LocationHref.match(/#Page:([0-9]+)/i);if(HrefPageNo!=null)CurrentPage=parseInt(HrefPageNo[1]);$.fn.PageSort=function(o){this.each(function(i){var e=this;o=$.extend({ElementsPerPage:10,OrderColumn:null,OrderMethod:"DESC",Columns:{},ExtraPostData:{},PagesTableContainer:null,PagesTableNum:3,PagesTableStyle:"3ColWide",CallBeforeLoad:function(){},CallAfterLoad:function(a){},CallOnLoadError:function(a,b,c){},CallOnError:function(a,b,c){},JSONFile:null},o||{});function LoadJSONData(d){$(o.PagesTableContainer).hide();o.CallBeforeLoad();PrevAjaxLoad=$.ajax({url:o.JSONFile,dataType:"json",data:{data:"JSONData",ordercolumn:o.OrderColumn,ordermethod:o.OrderMethod,limitstart:GetLimitStart(),limitrows:o.ElementsPerPage},type:"POST",success:function(a){SetElementVariables(a.TotalElements);o.CallAfterLoad(a,TotalElements,TotalPages);d()},error:function(a,b,c){o.CallOnLoadError(a,b,c);o.CallOnError(a,b,c);PageSortError=true}})}function SetElementVariables(a){TotalElements=parseInt(a);TotalPages=Math.ceil(TotalElements/o.ElementsPerPage);if(CurrentPage>TotalPages&&TotalPages!=0){CurrentPage=TotalPages}}function GetLimitStart(){start=(o.ElementsPerPage*(CurrentPage-1));return start}function WritePagesTable(a){if(o.PagesTableContainer==null){return}switch(o.PagesTableStyle){case"3ColWide":break;default:o.PagesTableStyle="3ColWide";break}if(o.PagesTableStyle=="3ColWide"){pagetablehtml='';if(CurrentPage>1){pagetablehtml+='<div class="left"><div class="pagestablelr"><a class="switchpage" href="#Page:'+(CurrentPage-1)+'">Previous</a></div></div>'}if(CurrentPage<TotalPages){pagetablehtml+='<div class="right"><div class="pagestablelr"><a class="switchpage" href="#Page:'+(CurrentPage+1)+'">Next</a></div></div>'}pagetablehtml+='<div align="center"><table cellpadding="2px" cellspacing="0" border="1px" class="pagestable"><tr>';if(CurrentPage>(o.PagesTableNum+1)){pagetablehtml+='<td><a class="switchpage" href="#Page:1">1</a></td><td>...</td>'}pi=o.PagesTableNum;while(pi>=0){if((CurrentPage-pi)<1){pi--;continue}if((CurrentPage-pi)>=CurrentPage){break}pagetablehtml+='<td><a class="switchpage" href="#Page:'+(CurrentPage-pi)+'">'+(CurrentPage-pi)+'</a></td>';pi--}pagetablehtml+='<th>'+CurrentPage+'</th>';fi=1;while(fi<=o.PagesTableNum){if((fi+CurrentPage)>TotalPages){break}pagetablehtml+='<td><a class="switchpage" href="#Page:'+(CurrentPage+fi)+'">'+(CurrentPage+fi)+'</a></td>';fi++}if((CurrentPage+fi)<=TotalPages){pagetablehtml+='<td>...</td><td><a class="switchpage" href="#Page:'+TotalPages+'">'+TotalPages+'</a></td>'}pagetablehtml+='</tr></table></div>';pagetablehtml+='<div class="clear"></div>'}$(o.PagesTableContainer).html(pagetablehtml).stop(true,true).fadeIn(500,"easeOutQuad");delete pagetablehtml}function BindPageSwitch(){$("a[class=switchpage]").click(function(){var a=$(this).attr('href');var b=a.match(/#Page:([0-9]+)/i);if(b!=null){CurrentPage=parseInt(b[1])}else{CurrentPage=1}if(typeof(PrevAjaxLoad.abort)=="function"){PrevAjaxLoad.abort()}Load()})}function Load(){if(typeof(CurrentPage)!="number"){CurrentPage=1}if(CurrentPage<1){CurrentPage=1}LoadJSONData(function(){if(!PageSortError){WritePagesTable();BindPageSwitch()}})}if(o.JSONFile==null){return false}Load()})};
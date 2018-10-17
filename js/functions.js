/**
 * 
 */


function getURLItems (parUrl)
{
	
	
	var queryurljson = {"queryurl":parUrl};
	var a='';
	
	$.post ("getURLItems.php",{parameterjson:JSON.stringify (queryurljson)})
	.done (function (data)
			{
			//alert (data);
			a =  JSON.stringify (data);
			
			}
			);

return a;	
}


function convertJSONToTable (rowsStringArray)
{
	
	

	var rowsArray= rowsStringArray.split(',');  

	var arHeader = [];
	var arRows = [];
	
	
	
	
	rowsArray.forEach (function (index,value,array)
			{
	
			var strFields = [];
			$.each (value, function (index,value)
					{
					if  (arHeader.indexOf (index) ===0 )
						{
							arHeader.push (index);
						}
					strFields.push (value);
					});
			arRows.push (strFields);
			});	

	
		
	
	
	
	
	var htmltable ='<table class="table">';
	htmltable +=  '<thead>';
	htmltable +=  '<tr>';

	arHeader.forEach (function (index, value , array )
			{
				htmltable +=  '<th scope="col">' + index+ '</th>' ;
			}
			);
	
	htmltable +=  '</tr>';
	htmltable +=  '</thead>';
	htmltable +=  '<tbody>';
	
	
	arRows.forEach (function (index,value,array)
			{
				htmltable +=  '<tr>';
				value.forEach (function (index,value,array)
						{
						htmltable +=  '<td>'+ value + '</td>';
						});
				htmltable +=  '</tr>';
		
			});
	htmltable +=  '</tbody>';
	htmltable +=  '</table>';
	
	

	return htmltable;
}
		
 



function addURLItemsToView (parUrl)
{
	var strJsonItems   = getURLItems(parUrl) ;
	alert (strJsonItems);
	var tablehtml= convertJSONToTable (strJsonItems);
	$("body").append (tablehtml)  ;
	
	
	
	
}




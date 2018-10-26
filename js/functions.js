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
			$("#tagtable").remove ();
			$("body").append (convertJSONToTable (data));
			}
			);

}



function convertJSONToTable (rowsStringArray)
{
	
	//input like    {"a":"a","b":"b"};{"a":"c","b":"d"}    ; separated JSONs as string  
	//output a HTML table 
	
	var rowsArray= rowsStringArray.split(';');  
	var arHeader = [];
	var arRows = [];
	rowsArray.forEach (function (row ,index,array)
			{
			
			var strFields = [];
			$.each (JSON.parse(row), function (index,value)
					{
					if  (arHeader.indexOf (index) === -1 )
						{
							arHeader.push (index);
						}
					strFields.push (value);
					});
			arRows.push (strFields);
			});	
	
	var htmltable ='<table class="table" id="tagtable">';
	htmltable +=  '<thead>';
	htmltable +=  '<tr>';

	arHeader.forEach (function (value, index , array )
			{
				htmltable +=  '<th scope="col">' + value+ '</th>' ;
			}
			);
	
	htmltable +=  '</tr>';
	htmltable +=  '</thead>';
	htmltable +=  '<tbody>';
	
	
	arRows.forEach (function (value,indey,array)
			{
				htmltable +=  '<tr>';
				value.forEach (function (value,index,array)
						{
						htmltable +=  '<td>'+ value + '</td>';
						});
				htmltable +=  '<td>'+ '<img src="img/minus-circle.svg" style ="	height:20px; width:20px;">' + '</td>';
				htmltable +=  '</tr>';
		
			});
	htmltable +=  '</tbody>';
	htmltable +=  '</table>';
	return htmltable;
}
		
 






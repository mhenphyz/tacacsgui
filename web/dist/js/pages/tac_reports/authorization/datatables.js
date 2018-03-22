checkConfiguration()
getUserInfo()
$("#filterButton").click(function() { 
			
	if ($("#filterFields").css('display') != 'none') {
		$("#filterFields").hide("slow"); 
		return;
	};
	if ($("#filterFields").css('display') == 'none') {$("#filterFields").show("slow"); return;};
			
});
			
var dataTable =  $('#authorizationDataTable').DataTable( {
				
	//scrollX: true,	
	processing: true,
	serverSide: true,
	autoWidth: false,
	orderCellsTop: true,
	
	"createdRow": function( row, data, dataIndex){
		if(data['disabled']==1) $(row).addClass('disabledRow');
	},
	
	"columns": [ 
	{"title": "ID", "data" : "id"},
	{"title": "Date", "data" : "date", 'dateColumn' : true},
	{"title": "NAS IP Addr", "data" : "NAS"},
	{"title": "Username", "data" : "username",},
	{"title": "NAC IP Addr", "data" : "NAC"},
	{"title": "Line", "data" : "line"},
	{"title": "Action", "data" : "action", "searchable": false},
	{"title": "CMD", "data" : "cmd"},
	 ],
	
	"columnDefs": [ 
	{
		"targets": [5,6,7],
		"orderable": false
	} ],
	
	"order":[[0,'desc']],
	
	"lengthMenu": [ 25, 50, 75, 100 ],
							
	ajax: {"url": API_LINK+"tacacs/reports/authorization/datatables/",
		"type": "POST",
		"data": {
			"temp": "acc"
		}
	}, // json datasource
	
	
	"drawCallback": function( settings ) {
		var filterRow='';
		var dateColumn;
		var filterRowElement=$('<tr role="row" id="filterFields" style="display: none;"></tr>')
		for (i = 0; i < settings.aoColumns.length; i++) { 
			filter='<td></td>';
			if (settings.aoColumns[i].bSearchable) 
			{
				var filter = $("<td></td>");
				var inputElement=$('<input searchCol_id="'+i+'" class="search-input form-control">')
				if (settings.aoColumns[i].dateColumn) {
					inputElement=dateColumn=$('<input searchCol_id="'+i+'" class="form-control pull-right daterange">');
				}
				filterRowElement.append(filter.append(inputElement))
			}
			filterRow+=filter;
		}
        $('#authorizationDataTable thead').append(filterRowElement);
		////daterange initiating////start
		if (dateColumn) {
			dateColumn.daterangepicker({
				autoUpdateInput:false, 
				locale:{ format: 'YYYY-MM-DD', cancelLabel: 'Clear'}, 
			})
			dateColumn.on('apply.daterangepicker', function(ev, picker){
				var dateRange = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD')
				$(this).val(dateRange)
				var i =$(this).attr('searchCol_id');  // getting column index
				dataTable.columns(i).search(dateRange).draw();
			})
			dateColumn.on('cancel.daterangepicker', function(ev, picker){
				$(this).val('')
				var i =$(this).attr('searchCol_id');  // getting column index
				dataTable.columns(i).search('').draw();
			})
		}
		////daterange initiating////end
    }

});

$.fn.dataTable.ext.errMode = 'throw';
				
$("#authorizationDataTable_filter").css("display","none");  // hiding global search box

$(document).on('keyup click change', '.search-input', function(){  
	var i =$(this).attr('searchCol_id');  // getting column index
	var v =$(this).val();  // getting search input value
	dataTable.columns(i).search(v).draw();
} );
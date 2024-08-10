
item = null;
conditions = [];

function addSearch (e) {


	 if ($(e).val ().length > 0) {
		  
		  conditions [$(e).attr ('id')] = $(e).val ();
	 }
}

function search () {

	 console.log (item);
	 
	 let url = '/tickets/index';

	 $('.search-condition').each (function (i, e) {

		  if ($(e).val ().length > 0) {
				
				conditions [$(e).attr ('id')] = $(e).val ();
				url += '/' + $(e).attr ('id') + '/' + $(e).val ();
		  }
	 });
	 
	 console.log (url);

	 controller (url, true);
}

$('#item_search').typeahead ({

	 source: function (query, result) {
		  
		  $.ajax ({
            url: "/search/items/sku_and_desc/" + query,
            type: "GET",
            success: function (data) {
					 
					 data = JSON.parse (data);
					 
					 result ($.map (data, function (item) {
						  
						  return item
					 }));
            }
        });
    },
	 updater: function (result) {
		  
		  console.log (result);
		  item = result.item;
		  $('#item_search').attr ('placeholder', result.name);

	 }
});

$('.start-date').datepicker ({
	 dateFormat: 'mm/dd/yy',
	 altFormat: 'yy-mm-dd',
	 altField: '#start_date',
	 maxDate: 0});

$('.end-date').datepicker ({
	 dateFormat: 'mm/dd/yy',
	 altFormat: 'yy-mm-dd',
	 altField: '#end_date',
	 maxDate: 0});

$(".currency-format").mask (currencyFormat, {reverse: true});
$(".integer-format").mask (integerFormat, {reverse: true});

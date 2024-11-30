
var item = null;
itemID = 0;

$('#add_item').change (function () {

	 console.log ('add item... ' + '/items/edit/0/' + $('#add_item').val ());

	 if ($('#add_item').val ()) {
		  
		  openForm (0, '/items/edit/0/' + $('#add_item').val ());
	 }
});

$('#department_id').change (function () {

	 controller ('/items/index/department_id/' + $('#department_id').val (), false);
});

$('#pricing_id').change (function () {

	 controller ('/items/index/pricing/' + $('#pricing_id').val (), true);
});

function search (id) {

	 console.log ('search... ' + id);
	 
	 if ($('#' + id).val ().length > 0) {
		  
		  controller ('/items/index/' + id + '/' + $('#' + id).val (), true);
	 }
}

$('#item_desc').typeahead ({
	 
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
	 updater: function (item) {
		  
		  openForm (item ['id'], '/items/edit/' + item ['id'] + '/0');
	 }
});


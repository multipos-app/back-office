
$('#add_discount').change (function () {

	 console.log ('add discount... ' + $('#add_discount').val ());
	 openForm (0, '/discounts/' + $('#add_discount').val ());
});


$('#addon_typeahead').typeahead ({
	 
    source: function (query, result) {
		  
		  console.log (query);
		  
		  $.ajax ({
            url: "<?= $this->request->getAttribute ('webroot'); ?>addons/get_items/" + query,
            type: "GET",
            success: function (data) {

					 console.log (data);
					 data = JSON.parse (data);
					 
					 result ($.map (data, function (item) {
						  
						  return item;
                }));
            }
        });
    },
	 updater: function (item) {

		  console.log (item);
		  // window.location = "/addons/index/" + item.id;
	 }
});



function search (id) {

	 if ($('#' + id).val ().length > 0) {
		  
		  controller ('/discounts/index/' + id + '/' + $('#' + id).val (), true);
	 }
}

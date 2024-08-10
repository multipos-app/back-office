
$('#mix_and_match_update').on ('click', function (e){

    e.preventDefault ();

	 console.log ($('#mix_and_match_edit').serialize ());

	 let url = '/discounts/update/';

	 $.ajax ({type: "POST",
				 url: url,
				 data: $('#mix_and_match_edit').serialize (),
				 success: function (data) {

					  closeForm ();
					  controller ('discounts', false);
				 },
				 fail: function () {

					  console.log ('fail...');
				 },
				 always: function () {

					  console.log ('always...');
				 }
				});
});

$('#markdown_amount').change (function () {
	 
	 var discount = parseFloat ($('#markdown_amount').val ());
	 var discountPrice = 0;
	 var discountProfit = 0;

	 addon ['params'] ['amount'] = discount;
});

$('#link_search').typeahead ({

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

		  links.push (result.item);
		  renderItemLinks ();
	 }
});


function renderItemLinks () {
	 
	 $('#links').html ('');
	 
	 console.log (links);

	 let html = '';
	 
	 $.each (links, function (i, item) {
		  
		  console.log ('link... ' + i);
		  console.log (item);

		  html +=
				'<input type="hidden" name="addon_links[' + i + ']" value="' + item.id + '"/>' +
				'<div class="grid-cell grid-cell-left">' + item.sku + '</div>' + 
				'<div class="grid-cell grid-cell-left item-desc">' + item.item_desc + '</div>' + 
				'<div class="grid-cell grid-cell-right">' + item.item_prices [0].price + '</div>' + 
				'<div class="grid-cell grid-cell-right">' + item.item_prices [0].cost + '</div>' + 
				'<div class="grid-cell grid-cell-right ">' +
				'<i class="fa fa-x fa-med" onclick="delLink(' + i + ');"></i>' + 
				'</div>';
	 });
	 
 	 $('#links').html (html);
}

function delLink (i) {

	 console.log ('delink... ' + i);
	 links.splice (i, 1);
	 renderItemLinks ();
}

if (links.length > 0) {
	 
	 renderItemLinks ();
}

$('input.datetimepicker-input').datepicker ({});

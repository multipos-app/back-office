	 
var index = 0;
var field = 'quantity';

$(document).ready (function () {
	 
	 $.each (items, function (i, item) {

		  render (item, i);
	 });
	 
 	 currencyFormatter ('en_US');
 	 integerFormatter ();
	 updateTotals ();
	 $('head title').text ('<?= __ ('Order') ?>' + ' ' + order.id);
	 
	 $('#' + field + '_' + index).addClass ('order-select');

});

let totalQuantity = 0;
let totalCost = 0;

function render (item, i) {

	 console.log (item);
	 
	 let rowClass = 	((i % 2) == 0) ? ' even-cell' : '';

	 let row = 
		  '<div class="grid-cell grid-cell-left' + rowClass + ' data-cell">' + item.sku + '</div>' +
		  '<div class="grid-cell grid-cell-left' + rowClass + ' data-cell">' + item.item_desc + '</div>';

	 if (status < 2) {

		  let total = parseFloat (item ['cost']) * parseFloat (item ['order_quantity']);
		  
		  row += 
				'<div class="grid-cell grid-cell-right' + rowClass + ' data-cell" id="quantity_' + i + '">' + item ['order_quantity'] + '</div>' + 
				
		  '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell" id="cost_' + i + '">' + currency (parseFloat (item ['cost'])) + '</div>' + 
				
	  	  '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell"><span id="total_' + i + '">' + currency (total) +'</span></div>';

		  $('#order_items').append (row);
	 }

	 updateTotals ();
}

function n (n) {

	 items [index] ['dirty'] = true;
	 switch (field) {

	 case 'quantity':
		  
		  items [index] ['order_quantity'] = (items [index] ['order_quantity'] * 10) + n; 
		  $('#' + field + '_' + index).html (items [index] ['order_quantity']);
		  break;
		  
	 case 'cost':
		  
		  let cost = currency ((parseFloat (items [index] ['cost']) * 10.0) + parseFloat (n) * .01);

		  items [index] ['cost'] = cost;
		  $('#' + field + '_' + index).html (items [index] ['cost']);
		  break;
	 }
	 
	 updateTotal ();
	 updateTotals ();
}

function del () {

	 console.log ('old quantity... ' + items [index] ['order_quantity']);
	 
	 items [index] ['dirty'] = true;

	 switch (field) {

	 case 'quantity':
		  
		  items [index] ['order_quantity'] = parseInt (items [index] ['order_quantity'] / 10);
		  $('#' + field + '_' + index).html (items [index] ['order_quantity']);
		  break;
		  
	 case 'cost':

		  items [index] ['cost'] = currency (parseFloat (items [index] ['cost']) / 10.0);
		  $('#' + field + '_' + index).html (items [index] ['cost']);
		  break;
	 }
	 
	 updateTotal ();
	 updateTotals ();
}

function move (n) {

	 console.log ('move... ' + n + ' ' + field + '_' + index);
	 
	 $('#' + field + '_' + index).removeClass ('order-select');
	 
	 if (field == 'cost') {

		  index += n;

		  if (index < 0) {

				index = 0;
		  }
		  
		  if (index == items.length) {

				index = items.length - 1;
		  }
		  
		  field = 'quantity';
	 }
	 else {
		  
		  field = 'cost';
	 }
	 
	 $('#' + field + '_' + index).addClass ('order-select');
}

function save () {

	 dirty = [];
	 $.each (items, function (i, item) {

		  if (typeof item ['dirty'] !== "undefined") {

				dirty.push (item);
		  }
	 });

	 if (dirty.length > 0) {

		  console.log (dirty);
		  
		  $.ajax ({type: "POST",
					  url: "<?= $this->request->getAttribute ('webroot'); ?>suppliers/order/" + order ['id'],
					  data: {"order_id": order ['id'],
								"status": order ['status'],
								"order_type": order ['order_type'],
								"items": dirty},
					  success: function (data) {
							
							// console.log (data);
					  }
					 });
	 }
}

function cancel () {

	 cancelled = true;
	 window.close ();
}

window.onunload = function () {

	 window.opener.location.reload ();
};


function updateTotal () {
	 
	 $('#total_' + index).
		  html (
				currency (parseFloat (items [index] ['order_quantity']) *
							 parseFloat (items [index] ['cost'])));
	 
 	 $('#save_button').removeClass ('btn-secondary');
	 $('#save_button').addClass ('btn-success');

	 updateTotals ();
}

function updateTotals () {

	 let totalCost = 0;
	 let totalQuantity = 0.0;

	 $.each (items, function (i, item) {

		  totalQuantity += item ['order_quantity'];
		  totalCost += parseFloat (item ['cost']) * parseFloat (item ['order_quantity']);
	 });
	 
	 $('#total_quantity').html (totalQuantity);
	 $('#total_cost').html (currency (totalCost));
}

function currency (d) {
	 
	 return d.toFixed (2);
}

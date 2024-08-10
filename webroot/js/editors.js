
var controls = {};
var saveMenu = "Save";
var resetMenu = "Reset";
var clearKey = "Clear Key";
var editors = [];

/**
*
* Methods
*
*/

editors ['default_params'] =  function (control) {

	 return '';
}
	 
editors ['item'] = function (control) {

	 attr = null;
	 var html = '';

	 if (control.params.length !== 0) {

		  console.log (control);
		  
		  $('#cell_text').val (control.text);
		  html +=
				item (control.params.sku, control.text);
	 }
	 
	 html +=
				'<div class="form-cell form-desc-cell">' + xlate.search_items + '</div>' +
				'<div class="form-cell form-control-cell">' +
				'<input type="text" id="item_typeahead" name="item_typeahead" class="form-control" placeholder="Enter SKU or description">' + 
	 			'</div>' +
				'<script>' +
				'itemTypeahead ()' +
				'</script>';

	 return html;
}

editors ['cash_tender'] = function (control) {
	 
	 let amount = '0.00';

	 if (typeof control.params.value !== "undefined") {
		  
		  amount = control.params.value.toFixed (2);
	 }
	 
	 let select =
		  '<select name="cash_opts" id="cash_opts" class="custom-dropdown" onchange="javascript: cashOpts ()">' +
		  '<option value=0>Fixed amount</option>' + 
		  '<option value=1>Sale total</option>' + 
		  '<option value=2>Round up</option>' +
		  '</select>';

	 let html = 
		  '<div class="form-cell form-desc-cell">' + xlate.cash_amount + '</div>' +
		  '<div id="controls" class="form-cell form-control-cell">' +
	 	  '<input type="text" id="value" name="value" class="form-control currency-format" value="' + amount + '" onkeyup="params (\'value\', \'currency\');" onclick="this.select ()">' + 
	 	  '</div>' +
	 	  '<script>' +
	  	  '$(".currency-format").mask ("#,##0.00", {reverse: true})' +
		  '</script>';

	 console.log (html);
	 
	 return html;
};

function cashOpts () {

	 console.log ('cash opts... ' + $('#cash_opts').val ());
	 params ('cash_opts', 'select');
}

editors ['credit_tender'] = function (control) {
	 
	 let openDrawer = control.params.open_drawer ? 'checked' : '';
	 let printReceipt = control.params.print_receipt ? 'checked' : '';
	 let debitSelect = control.params.debit_credit === 'debit' ? ' selected' : '';
	 let creditSelect = control.params.debit_credit === 'credit' ? ' selected' : '';
	 let ebtFoodstampSelect = control.params.debit_credit === 'ebt_foodstamp' ? ' selected' : '';
	 let ebtCashSelect = control.params.debit_credit === 'ebt_cash' ? ' selected' : '';
	 let creditReturnSelect = control.params.debit_credit === 'credit_return' ? ' selected' : '';
	 let debitReturnSelect = control.params.debit_credit === 'debit_return' ? ' selected' : '';
	 
	 let html = 		 
		  '<div class="form-cell form-control-cell">' +
		  '<div form-check form-switch">' +
		  '<input type="checkbox" ' + openDrawer + ' class="form-check-input" name="open_drawer" id="open_drawer" type="checkbox" onclick="params (\'open_drawer\', \'checkbox\');">' +
		  '<label for="open_drawer" class="form-check-label">' + xlate.open_drawer + '</label>' +
		  '</div>' + 
		  '</div>' +
		  '<div class="form-cell form-desc-cell">' + xlate.open_drawer_desc + '</div>' +

		  
		  '<div class="form-cell form-control-cell">' +
		  '<div form-check form-switch">' +
		  '<input type="checkbox" ' + printReceipt + ' class="form-check-input" name="print_receipt" id="print_receipt" type="checkbox" onclick="params (\'print_receipt\', \'checkbox\');">' +
		  '<label for="print_receipt" class="form-check-label">' + xlate.print_receipt + '</label>' +
		  '</div>' + 
		  '</div>' +
		  '<div class="form-cell form-desc-cell">' + xlate.print_receipt_desc + '</div>' +

		  '<div class="form-cell form-desc-cell">' + xlate.debit_credit + '</div>' +
		  
		  '<div class="form-cell form-control-cell">' +
		  '<select id="debit_credit" name="debit_credit" class="custom-dropdown" onchange="params (\'debit_credit\', \'select\')">' +
		  '<option value="debit"' + debitSelect + '>' + xlate.debit + '</option>' +
		  '<option value="credit"' + creditSelect + '>' + xlate.credit + '</option>' +
		  '<option value="ebt_foodstamp"' + ebtFoodstampSelect + '>' + xlate.ebt_foodstamp + '</option>' +
		  '<option value="ebt_cash"' + ebtCashSelect + '>' + xlate.ebt_cash + '</option>' +
		  '<option value="credit_return"' + creditReturnSelect + '>' + xlate.credit_return + '</option>' +
 		  '<option value="debit_return"' + debitReturnSelect + '>' + xlate.debit_return + '</option>' +
		  '</select>' +
	 	  '</div>';
	 
	 return html;
};

editors ['markdown_percent'] = function (control) {
	 	 
	 let value = getParam (control.params, 'percent', xlate.percent_placeholder);
	 let desc = getParam (control.params, 'receipt_desc', '');
	 
	 return '<div class="form-cell form-desc-cell">' + xlate.receipt_desc + '</div>' +
		  '<div class="form-cell form-control-cell">' +
	 	  '<input type="text" id="receipt_desc" name="receipt_desc" class="form-cell form-control-cell" ' + desc + '" onblur="params (\'receipt_desc\', \'text\');">' + 
		  '</div>' +
		  '<div class="form-cell form-desc-cell">' + xlate.percent_markdown + '</div>' +
		  '<div class="form-cell form-control-cell">' +
	 	  '<input type="text" id="percent" name="percent" class="percent-format" ' + value + '" onblur="params (\'percent\', \'percent\');">' + 
		  '</div>' +
	 	  '<div class="grid-cell grid-span-all">' +
		  xlate.percent_markdown_desc +
		  '</div>' +
		  '<script>' +
		  '$(".percent-format").mask ("#,##0.000", {reverse: true})' +
		  '</script>';
};

editors ['markdown_amount'] = function (control) {

	 let value = getParam (control.params, 'amount', xlate.amount_placeholder);
	 let desc = getParam (control.params, 'receipt_desc', '');
	 
	 return '<div class="form-cell form-desc-cell">' + xlate.receipt_desc + '</div>' +
		  '<div class="form-cell form-control-cell">' +
	 	  '<input type="text" id="receipt_desc" name="receipt_desc" class="form-cell form-control-cell" ' + desc + '" onblur="params (\'receipt_desc\', \'text\');">' + 
		  '</div>' +
		  '<div class="form-cell form-desc-cell">' + xlate.amount_markdown + '</div>' +
		  '<div class="form-cell form-control-cell">' +
	 	  '<input type="text" id="amount" name="amount" class="currency-format" ' + value + '" onblur="params (\'amount\', \'currency\');">' + 
		  '</div>' +
	 	  '<div class="grid-cell grid-span-all">' +
		  xlate.amount_markdown_desc +
		  '</div>' +
		  '<script>' +
	  	  '$(".currency-format").mask ("#,##0.00", {reverse: true})' +
		  '</script>';
};


editors ['discount_percent'] = function (control) {

	 let value = getParam (control.params, 'percent', xlate.percent_placeholder);
	 let desc = getParam (control.params, 'receipt_desc', '');
	 
	 return '<div class="form-cell form-desc-cell">' + xlate.receipt_desc + '</div>' +
		  '<div class="form-cell form-control-cell">' +
	 	  '<input type="text" id="receipt_desc" name="receipt_desc" class="form-cell form-control-cell" ' + desc + '" onblur="params (\'receipt_desc\', \'text\');">' + 
		  '</div>' +
		  '<div class="form-cell form-desc-cell">' + xlate.percent_discount + '</div>' +
		  '<div class="form-cell form-control-cell">' +
	 	  '<input type="text" id="percent" name="percent" class="percent-format" ' + value + '" onblur="params (\'percent\', \'percent\');">' + 
		  '</div>' +
	 	  '<div class="grid-cell grid-span-all">' +
		  xlate.percent_discount_desc +
		  '</div>' +
		  '<script>' +
		  '$(".percent-format").mask ("#,##0.000", {reverse: true})' +
		  '</script>';
};

editors ['nav_menus'] = function (control) {

	 attr = null;
	 let html =
		  '<div class="form-cell form-desc-cell">' + xlate.nav_menu + '</div>' +
		  '<div class="form-cell form-control-cell">';
		  
	 let value = null;
	 if (typeof cells [cellIndex].params ['value'] !== "undefined") {

		  value = cells [cellIndex].params ['value'];
	 }

	html +=		  
	 	  '<select id="nav_index" name="nav_index" class="custom-dropdown" onchange="javascript:navMenu ();">' +
		  '<option value="" selected disabled>Select a menu</option>';

	 let m = config [menus] ['horizontal_menus'];
	 
	 console.log (m);
	 for (var i = 0; i < m.length; i ++) {

		  let selected = value != null ? ' selected' : '';
		  html += '<option value="' + i + '"' + selected + '>' +  config [m [i] ['name']] ['name'] + '</option>';
		  
		  console.log (config [m [i] ['name']]);
	 }
	 
	 html +=
		  '</select>' +
	 	  '</div>';
	 	 	 
	 return html;
}

editors ['bank'] = function (control) {

	 var params = cells [cellIndex].params;

	 let functions = [{name: "open_amount", desc: xlate.open_amount},
							{name: "cash_drop", desc: xlate.cash_drop},
							{name: "paid_in", desc: xlate.paid_in},
							{name: "paid_out", desc: xlate.paid_out}];
	 
	 let html = 

		  '<div class="form-cell form-desc-cell">' + xlate.nav_menu + '</div>' +
		  '<div class="form-cell form-control-cell">' +
		  '<select id="type" name="type" class="custom-dropdown" onchange="params (\'type\', \'select\')">' +
		  '<option value="" selected disabled>' + xlate.bank_functions + '</option>';
	 
	 $.each (functions, function (key, value) {
		  
		  let selected = params.type == value.name ? ' selected' : ' ';
		  html += '<option value="' + value.name + '"' + selected+ '>' + value.desc + '</option>';
	 });
	 
	 html +=
		  '</select>' +
		  '</div>';
	 
	 return html;
}

function itemTypeahead () {
	 
	 if (typeof $('#item_typeahead') != 'undefined') {

		  $('#edit_desc').html ('Search for item or create a new item');
		  
		  $('#item_typeahead').typeahead ({
				
		  		source: function (query, process) {
					 
		  			 return $.get ('/items/item_typeahead/' + query, function (data) {

		  				  data = JSON.parse (data);
		  				  process (data);
		  				  return;
		  			 });
		  		},
		  		updater: function (searchItem) {

		  			 cells [cellIndex].text = searchItem.name;
		  			 cells [cellIndex].params = { sku: searchItem.sku };
					 
		  			 $('#cell_' + cellIndex).html (searchItem.name);
		  			 $('#cell_text').val (searchItem.name);
		  			 $('#edit_button').html (item (searchItem.sku, searchItem.name) + menuControls);
					 $('#cell-editor').html (editors ['item'] (cells [cellIndex]));
		  		}
		  });
	 }
}

function navMenu () {
	 
	 cells [cellIndex] ['params'] = { menu_index:  $('#nav_index').val () };
}

 function editItem (sku) {

	  console.log ('edit item... ' + sku);
	  window.open ('/pos-app/index/params/items/edit-item-sku/'+ sku);
 }

/**
*
* utils
*
*/

function item (sku, name) {

	 var html =
		  '<div class="form-cell form-desc-cell">Edit item</div>' +
		  '<div id="controls" class="form-cell form-control-cell">' +
		  '<a class="btn btn-secondary btn-block btn-stretch form-left" onclick="javascript:editItem (\'' + sku + '\')">' + name + '</a>' + 
		  '</div>';

	 return html;
}
 
var menuControls =
	 '<div class="grid-cell-center">' +
	 '<button class="btn btn-danger btn-block" onclick="javascript:clearButton()">' + clearKey + '</button>' +
	 '</div>';

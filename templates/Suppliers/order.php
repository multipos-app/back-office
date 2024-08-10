<style>

 .item-search-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: .5fr 2fr 10fr .3fr;
	  grid-column-gap: 10px;
 }

 .order-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 2fr 1fr repeat(2, 1fr) .3fr;
	  grid-column-gap: 0px;
 }

 .order-input {
	  margin-left: 5px !important;
 }
 
 .span-2-6 {
     grid-column: 2 / 7;
 }
 
 .span-1-4 {
     grid-column: 2 / 6;
 }
 
 .search-pad {
     padding-left: 20px !important;
 }
 
</style>

<script>
 
 supplierID = <?= $order ['supplier_id']?>;
 order = <?= $order ?>;
 items = <?= json_encode ($order ['items']) ?>;
 status = <?= $order ['status'] ?>;
 
</script>

<?= $this->Form->create (null, ['id' => 'order', 'name' => 'order']) ?>

<input type="hidden" name="supplier_id" value="<?= $order ['supplier_id'] ?>>">
<input type="hidden" name="bu_id" value="<?= $order ['bu_id'] ?>">
<input type="hidden" name="order_id" value="<?= $order ['id'] ?>">
<input type="hidden" name="status" value="<?= $order ['status'] ?>">
<input type="hidden" name="order_type" value="<?= $order ['order_type'] ?>">

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __('Order Detail') ?></legend>

	 <?php
	 /* if ($order ['status'] < 2) {*/
	 if ($order ['status'] == 100) {
	 ?>
		  <div class="item-search-grid top30">
				
				<div class="grid-cell grid-cell-right">
					 <a href="/suppliers/print-order/<?= $order ['id'] ?>" target="_blank"><i class="fal fa-print fa-med"></i></a>
					 <a onclick="orderExport()"><i class="fal fa-download fa-med"></i></a>
					 <a href="/suppliers/print-order/<?= $order ['id'] ?>" target="_blank"><i class="fal fa-printer fa-med"></i></a>
				</div>
				
		  </div>

	 <?php
	 }
	 ?>
	 
	 <div class="order-grid top30">

		  <div class="grid-cell grid-cell-left top15">
				<input type="text" id="item_typeahead" name="item_typeahead" class="form-control" placeholder="<?= __ ('Search SKU or description') ?>"></input>
		  </div>
		  <div class="grid-cell grid-cell-left top30 search-pad span-2-6"><?= __ ('Add item') ?></div>

		  <div class="grid-cell grid-cell-left grid-cell-separator top30"><?= __ ('SKU') ?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator top30"><?= __ ('Description'); ?></div>
		  <div class="grid-cell grid-cell-center grid-cell-separator top30"><?= __ ('Last 7 days'); ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator top30"><?= __ ('Quantity') ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator top30"><?= __ ('Cost') ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator top30"><?= __ ('Total') ?></div>

	 </div>
	 
	 <div class="order-grid" id="order_items"></div>
	 
	 <div class="order-grid top10">

		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Totals')?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><span id="total_quantity"></span></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"></span></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><span id="total_order"></span></div>
		  		  
	 </div>

	 <?php
	 if ($order ['status'] < 2) {
	 ?>
		  <div class="row top30">
				<div class="col-sm-4"></div>
				
				<div class="col-sm-2 text-center">
					 <?= $this->Form->submit (__ ('Save'), ['id' => 'save_button', 'class' => 'btn btn-secondary btn-block top15']) ?>
				</div>
				
				<div class="col-sm-2 text-center">
					 <button class="btn btn-warning btn-block top15" onclick="javascript:cancel ()"><?= __ ('Cancel') ?></button>
				</div>
				
				<div class="col-sm-2"></div>
		  </div>
	 <?php
	 }
	 else {
	 ?>
		  <div class="col-sm-2 text-center">
				<button class="btn btn-primary btn-block top15" onclick="javascript:window.close ()"><?= __ ('Orders') ?></button>
		  </div>
	 <?php
	 }
	 ?>
	 
</fieldset>

<script>

 var cancelled = false;
 
 $(document).ready (function () {
	  
	  $.each (items, function (i, item) {

			render (item, i);
	  });
	  
	  updateTotals ();
	  
	  $('head title').text (order.supplier.supplier_name + ' <?= __ ('Order') ?> ' + order.id); 
 });

 $('#item_typeahead').typeahead ({
	  
     source: function (query, result) {
			
			$.ajax ({
				 url: "/search/inv-items/" + supplierID + "/" +query,
				 type: "GET",
				 success: function (data) {

					  data = JSON.parse (data);

					  result ($.map (data, function (item) {

							return item;
					  }));
				 }
			});
     },
	  updater: function (item) {

			console.log (item);
			
			i = items.length;
			items.push (item.item_detail);
			render (item.item_detail, i);
	  }
 });

 $("#order").submit (function (e) {

     e.preventDefault ();

	  if (cancelled) {

			return;
	  }
	  
	  var url = $(this).attr ('action');
	  var data = $(this).serialize ();

	  $.ajax ({type: "POST",
				  url: url,
				  data: data,
				  success: function (data) {
						
						//window.close ();
				  }
	  });
 });

 function cancel () {

	  cancelled = true;
	  window.close ();
 }
 
 window.onunload = function () {

	  window.opener.location.reload ();
 };
 
 let totalQuantity = 0;
 let totalCost = 0;
 
 function render (item, i) {

	  console.log (item);
	  
	  let total = item ['cost'] * item ['order_quantity'];
	  let quantity = item ['order_quantity'];
	  let cost = item ['cost'];
	  
	  let rowClass = 	((i % 2) == 0) ? ' even-cell' : '';

	  let row = 
			'<input type=hidden name="items[' + i + '][item_id]" value="' + item.item_id + '">' + 
			'<input type=hidden name="items[' + i + '][inv_item_id]" value="' + item.inv_item_id + '">' +
			'<input type=hidden name="items[' + i + '][item_prices_id]" value="' + item.item_prices_id + '">' +
			'<input type=hidden name="items[' + i + '][package_quantity]" value="' + item.package_quantity + '">';

	  if (typeof item.add_item !== "undefined") {

			console.log ("add item... ");
			row += 
				 '<input type=hidden name="items[' + i + '][add_item]" value="true">';
	  }
	  
	  row +=
			'<div class="grid-cell grid-cell-left' + rowClass + ' data-cell">' + item.sku + '</div>' +
			'<div class="grid-cell grid-cell-left' + rowClass + ' data-cell">' + item.item_desc + '</div>' +
			'<div class="grid-cell grid-cell-center' + rowClass + ' data-cell">' + item.weeks_sales + '</div>';

	  if (status < 2) {

			row += 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell">' +
				 '<input type="text" maxlength="6" dir="rtl" class="form-control integer-format order-input inline" ' +
				 'id="order_quantity_' + i + '"' +
				 'name="items[' + i + '][order_quantity]"' +
				 'value="' + quantity + '"' +
				 'onblur="updateTotal (' + i + ')">' +
				 '</div>' + 
				 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell">' + 
				 '<input type="text" maxlength="12" dir="rtl" class="form-control currency-format order-input inline" ' +
				 'id="cost_' + i + '"' +
				 'name="items[' + i + '][cost]"' +
				 'value="' + currency (cost) + '" ' +
				 'onblur="updateTotal (' + i + ')">' +
				 '</div>' + 
				 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell"><span id="total_' + i + '">' + currency (total) +'</span></div>';
	  }
	  else {

			row += 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell">' + quantity + '</div>' + 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell">' + currency (cost) + '</div>' + 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell"><span id="total_' + i + '">' + currency (total) +'</span></div>';
	  
	  }
	  $('#order_items').append (row);
 }

 function updateTotal (i) {
	  
			
	  let total = currency ($('#order_quantity_' + i).val () * parseFloat ($('#cost_' + i).val ()));
	  
	  items [i] ['order_quantity'] = parseInt ($('#order_quantity_' + i).val ());
	  items [i] ['cost'] = parseFloat ($('#cost_' + i).val ());
	  
	  $('#total_' + i).html (total);

	  console.log (items [i]);
	  
 	  updateTotals ();
	  
 	  $('#save_button').removeClass ('btn-secondary');
	  $('#save_button').addClass ('btn-success');
 }
 
 
 function updateTotals () {

	  let totalCost = 0;
	  let totalQuantity = 0;

	  $.each (items, function (i, item) {

			totalCost += items [i] ['cost'] * items [i] ['order_quantity'];				 
			totalQuantity += items [i] ['order_quantity'];
	  });

	  $('#total_order').html (currency (totalCost));
	  $('#total_quantity').html (totalQuantity);
 }
 
 function currency (d) {
	  
	  return d.toFixed (2);
 }

 function orderExport () {
	  
	  var csv = 'sku,description,cost,total,quantity\n';
	  var sep = '';
	  
	  $('.data-cell').each (function () {				 
			
			if ($($(this) [0].firstChild).is ('span')) {
				 
				 csv += sep + $($(this) [0].firstChild).text ();
				 sep = ',';
			}
			else if ($($(this) [0].firstChild).is ('input')) {
				 
				 csv += sep + $($(this) [0].firstChild).val ();
				 csv += '\n';
				 sep = '';
			}
			else {
				 
				 csv += sep + $(this) [0].firstChild.data;
				 sep = ',';
			}
	  });

	  csv += '\n';
 	  var e = document.createElement ('a');
	  e.setAttribute ('href', 'data:csv,' + encodeURIComponent (csv));
	  e.setAttribute ('download', 'order-export.csv');
	  e.style.display = 'none';
	  document.body.appendChild (e);
	  e.click ();
	  document.body.removeChild (e);
 }

</script>

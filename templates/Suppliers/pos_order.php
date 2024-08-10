<?= $this->Html->css ("pos-styles.css") ?>
<style>

 .main-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 2fr 1fr;
	  grid-column-gap: 10px;
 	  font-size: 20px !important;
}

 .grid-scroll {

	  height: 450px;
 	  overflow-x: hidden !important;
}
 
 .order-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 2fr 1fr repeat(2, 1fr) 1fr;
	  grid-column-gap: 0px;
 	  cursor:pointer;
	  font-size: 18px !important;
 }
 
 .keypad-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: repeat(3, 1fr);
	  grid-column-gap: 10px;
	  grid-row-gap: 10px;
 }
 
 .nav-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: repeat(2, 1fr);
	  grid-column-gap: 10px;
 }
 
 .controls-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 2fr repeat(3, 1fr) 2fr;
	  grid-column-gap: 10px;
 }

 .order-btn {
	  height: 72px !important;
 }
 
 .span-1-3 {
     grid-column: 1 / 3;
  }
 
 .span-3-6 {
     grid-column: 2 / 6;
 }
 
 .span-3-6 {
     grid-column: 3 / 6;
 }
 
</style>

<script>
 
 supplierID = <?= $order ['supplier_id']?>;
 order = <?= $order ?>;
 items = <?= json_encode ($order ['items']) ?>;
 status = <?= $order ['status'] ?>;

</script>

<input type="hidden" name="supplier_id" value="<?= $order ['supplier_id'] ?>>">
<input type="hidden" name="bu_id" value="<?= $order ['bu_id'] ?>">
<input type="hidden" name="order_id" value="<?= $order ['id'] ?>">
<input type="hidden" name="status" value="<?= $order ['status'] ?>">
<input type="hidden" name="order_type" value="<?= $order ['order_type'] ?>">

<fieldset class="maintenance-border">
	 
	 <legend class="maintenance-border"><?= __('Order Detail') ?></legend>

	 <div class="main-grid top30">

		  <div class="grid-cell grid-scroll">
				
				<div class="order-grid">
					 
					 <div class="grid-cell grid-cell-left span-1-3 top15">
						  <input type="text" id="item_typeahead" name="item_typeahead" class="form-control" placeholder="<?= __ ('Add item, Search SKU or description') ?>"></input>
					 </div>
					 <div class="grid-cell grid-cell-left top30"></div>
					 <div class="grid-cell grid-cell-left top30"></div>
					 <div class="grid-cell grid-cell-left top30"></div>
					 <div class="grid-cell grid-cell-left top30"></div>

					 <div class="grid-cell grid-cell-left grid-cell-separator top15"><?= __ ('SKU') ?></div>
					 <div class="grid-cell grid-cell-left grid-cell-separator top15"><?= __ ('Description'); ?></div>
					 <div class="grid-cell grid-cell-center grid-cell-separator top15"><?= __ ('Last 7 days') ?></div>
					 <div class="grid-cell grid-cell-right grid-cell-separator top15"><?= __ ('Quantity') ?></div>
					 <div class="grid-cell grid-cell-right grid-cell-separator top15"><?= __ ('Cost') ?></div>
					 <div class="grid-cell grid-cell-right grid-cell-separator top15"><?= __ ('Total') ?></div>
				</div>
				
				<div class="order-grid" id="order_items"></div>
				
				<div class="order-grid">

					 <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Totals')?></div>
					 <div class="grid-cell grid-cell-left grid-cell-separator"></div>
					 <div class="grid-cell grid-cell-left grid-cell-separator"></div>
					 <div class="grid-cell grid-cell-right grid-cell-separator"><span id="total_quantity"></span></div>
					 <div class="grid-cell grid-cell-right grid-cell-separator"></span></div>
					 <div class="grid-cell grid-cell-right grid-cell-separator"><span id="total_cost"></span></div>
		  			 
				</div>
				
		  </div>
		  
		  <div class="grid-cell">
				
				<div class="keypad-grid">

					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (7)">7</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (8)">8</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (9)">9</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (4)">4</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (5)">5</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (6)">6</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (1)">1</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (2)">2</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (3)">3</div>
					 <div class="grid-cell grid-cell-center"></div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn" onclick="javascript:n (0)">0</div>
					 <div class="grid-cell grid-cell-center keypad-btn order-btn"><i class="far fa-backspace fa-med icon-btn" onclick="javascript:del ()"></i></div>
					 
				</div>
		  
				<div class="nav-grid top10">
					 
					 <div class="grid-cell grid-cell-center text-center keypad-btn order-btn" onclick="javascript:move(1)"><?= __ ('Next')?></div>
					 <div class="grid-cell grid-cell-center text-center keypad-btn order-btn" onclick="javascript:move(-1)"><?= __ ('Prev')?></div>
					 
				</div>
				
		  </div>

	 </div>
	 
	 <?php
	 if ($order ['status'] < 2) {
	 ?>
		  <div class="controls-grid top10">
				<div class="grid-cell"></div>
				
				<div class="grid-cell">
					 <div id="save_button" class="grid-cell grid-cell-center keypad-btn order-btn inactive-btn" onclick="javascript:save ()"><?= __ ('Save') ?></div>
				</div>
				
				<div class="grid-cell text-center">
					 <div class="grid-cell grid-cell-center keypad-btn order-btn cancel-btn" onclick="javascript:cancel ()"><?= __ ('Cancel') ?></div>
				</div>
				
				<div class="grid-cell text-center">
					 <div class="grid-cell grid-cell-center keypad-btn order-btn primary-btn" onclick="javascript:androidPrint ()"><?= __ ('Print') ?></div>
				</div>
				
				<div class="grid-cell"></div>
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

 var index = 0;
 var field = 'quantity';

 $(document).ready (function () {
	  
	  $.each (items, function (i, item) {

			console.log (items [i]);
			render (item, i);
	  });

	  if (order ['status'] < 2) {
			
 			currencyFormatter ('en_US');
 			integerFormatter ();
	  }
	  
	  updateTotals ();
	  $('head title').text ('<?= __ ('Order') ?>' + ' ' + order.id);
	  
	  $('#' + field + '_' + index).addClass ('order-select');
	  session ()
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
 			currencyFormatter ('en_US');
 			integerFormatter ();
	  }
 });

 let totalQuantity = 0;
 let totalCost = 0;
 
 function render (item, i) {

	  // console.log (item);
	  
	  let rowClass = 	((i % 2) == 0) ? ' even-cell' : '';

	  let row = 
			'<div class="grid-cell grid-cell-left' + rowClass + ' data-cell">' + item.sku + '</div>' +
			'<div class="grid-cell grid-cell-left' + rowClass + ' data-cell">' + item.item_desc + '</div>'+
			'<div class="grid-cell grid-cell-center' + rowClass + ' data-cell">' + item.weeks_sales + '</div>';

	  if (status < 2) {

			let total = parseFloat (item ['cost']) * parseFloat (item ['order_quantity']);
			
			row += 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell" id="quantity_' + i + '" onclick="javascript:jump (\'quantity\', ' + i + ')">' + item ['order_quantity'] + '</div>' + 
				 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell" id="cost_' + i + '" onclick="javascript:jump (\'cost\', ' + i + ')">' + currency (parseFloat (item ['cost'])) + '</div>' + 
				 
	  			 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell"><span id="total_' + i + '">' + currency (total) +'</span></div>';
	  }
	  else {
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell">' + item ['order_quantity'] + '</div>' + 
				 
				 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell">' + currency (parseFloat (item ['cost'])) + '</div>' + 
				 
	  			 '<div class="grid-cell grid-cell-right' + rowClass + ' data-cell">' + currency (total) +'</div>';
	  }

	  $('#order_items').append (row);

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
 	  session ()
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
 	  session ()
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
 	  session ()
}

 function jump (f, i) {

	  console.log ('jump... ' + f + ' ' + i);
	  
	  $('#' + field + '_' + index).removeClass ('order-select');

	  field = f;
	  index = i;
	  
	  $('#' + field + '_' + i).addClass ('order-select');
	  session ()
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
							 
							 window.location = '/suppliers/orders/' + <?= $order ['supplier'] ['id'] ?>;
						}
			});
	  }
 }
 
 function cancel () {

	  window.location = '/suppliers/orders/' + <?= $order ['supplier'] ['id'] ?>;
 }
 
 window.onunload = function () {

	  window.opener.location.reload ();
 };


 function updateTotal () {
	  
	  $('#total_' + index).
					  html (
							currency (parseFloat (items [index] ['order_quantity']) *
								 parseFloat (items [index] ['cost'])));
	  
 	  $('#save_button').removeClass ('inactiv-btn');
	  $('#save_button').addClass ('success-btn');

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

 function androidPrint () {

	  Android.print (JSON.stringify (order));		
 }
 
</script>

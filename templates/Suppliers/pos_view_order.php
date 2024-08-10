<style>

 .order-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 2fr repeat(2, 1fr) .3fr;
	  grid-column-gap: 0px;
 }

 .span-2-5 {
     grid-column: 2 / 6;
 }
 
 .span-1-4 {
     grid-column: 2 / 6;
 }
 
</style>

<script>
 
 order = <?= $order ?>;
 
</script>

<fieldset class="maintenance-border">

	 <legend class="maintenance-border"><?= $order ['supplier'] ['supplier_name'] ?>&nbsp;<?= __ ('Order') ?>&nbsp;#<?= $order ['id'] ?></legend>

	 <div class="order-grid top30">

		  <div class="grid-cell grid-cell-left grid-cell-separator top30"><?= __ ('SKU') ?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator top30"><?= __ ('Description'); ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator top30"><?= __ ('Quantity') ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator top30"><?= __ ('Cost') ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator top30"><?= __ ('Total') ?></div>
	 
		  <?php

		  $totalCost = 0;
		  $totalQuantity = 0;
		  $i = 0;

		  foreach ($order ['items'] as $item) {
		  
				$rowClass = (($i % 2) == 0) ? ' even-cell' : '';
				$total = floatVal ($item ['order_quantity']) * floatVal ($item ['cost']);
				$totalCost += floatVal ($total);
				$totalQuantity += $item ['order_quantity'];

		  ?>
				<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= $item ['sku'] ?></div>
				<div class="grid-cell grid-cell-left<?= $rowClass ?>"><?= $item ['item_desc'] ?></div>
				<div class="grid-cell grid-cell-right<?= $rowClass ?>"><?= $item ['order_quantity'] ?></div>
				<div class="grid-cell grid-cell-right<?= $rowClass ?>"><?= money_format ('%!i', $item ['cost']) ?></div>
				<div class="grid-cell grid-cell-right<?= $rowClass ?>"><?= money_format ('%!i', $total) ?></div>
				
		  <?php

		  $i++;
		  }
		  ?>
		  				
		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Totals')?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= $totalQuantity ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= money_format ('%!i', $totalCost) ?></div>
		  
	 </div>

	 <div class="row top30">
		  <div class="col-sm-5"></div>
		  
		  <div class="col-sm-2 text-center">
				<button class="btn btn-primary btn-block top15" onclick="javascript:androidPrint ()"><?= __ ('Print') ?></button>
		  </div>
		  
	 </div>
	 
</fieldset>

<script>
	 
 function androidPrint () {
	 
	  Android.print (JSON.stringify (order));		
 }
	 
</script>

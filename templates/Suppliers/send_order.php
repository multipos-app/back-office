!<style>

 .order-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 2fr repeat(3, 1fr);
	  grid-column-gap: 0px;
 }
 
</style>

<fieldset class="maintenance-border">
	 
	 <legend class="maintenance-border"><?= __('Order Detail') ?></legend>
	 
	 <div class="order-grid top30">

		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('SKU'); ?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Description'); ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Cost'); ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Order Quantity'); ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= __ ('Total'); ?></div>

		  <?php
		  
		  $orderTotal = 0;
		  $orderQuantity = 0;
		  $this->log ($items, 'debug');
		  
		  foreach ($items as $item) {

				$total = $item ['item_prices'] [0] ['cost'] * $item ['inv_items'] [0] ['order_quantity'];
				$orderQuantity += $item ['inv_items'] [0] ['order_quantity'];
				$orderTotal += $total;
		  ?>
				
				<div class="grid-cell grid-cell-left"><?= $item ['sku'] ?></div>
				<div class="grid-cell grid-cell-left"><?= $item ['item_desc'] ?></div>
				<div class="grid-cell grid-cell-right"><?= money_format ('%!i', $item ['item_prices'] [0] ['cost']) ?></div>
				<div class="grid-cell grid-cell-right"><?= $item ['inv_items'] [0] ['order_quantity'] ?></div>
				<div class="grid-cell grid-cell-right"><?= money_format ('%!i', $total) ?></div>
				
		  <?php
		  }
		  ?>
		  
		  <div class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Totals')?></div>
		  <div class="grid-cell grid-cell-left grid-cell-separator"></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= $orderQuantity ?></div>
		  <div class="grid-cell grid-cell-right grid-cell-separator"><?= $orderTotal ?></div>

	 </div>

</fieldset>

<?= $this->Html->css ("Items/index") ?>

<div class="form-grid controls-grid">
	 
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>

	 <div class="form-cell form-control-cell">
		  <input type="text" id="item_desc" class="form-control" placeholder="<?= __ ('SKU or description') ?>">
	 </div>
	 
	 <div class="grid-cell grid-cell-center action-icon" onclick="search ('item_desc')"><i class="far fa-search fa-med"></i></div>
	 
	 <div class="form-cell">
		  
		  <?php echo $this->Form->select ('department_id',
													 $departments,
													 ['onchange' => "search ('department_id')",
													  'id' => 'department_id',
													  'class' => 'custom-dropdown',
													  'label' => false,
													  'value' => false]); ?>
	 </div>
	 
	 <div class="grid-cell"></div>
	 <div class="form-cell form-right">
		  
		  <?php
		  				
		  echo $this->Form->select ('add_item',
											 $pricingOptions,
											 ['id' => 'add_item',
											  'class' => 'custom-dropdown',
											  'label' => false]);
		  ?>
	 </div>
	 
</div>

<div class="item-grid">

	 <div class="grid-cell grid-cell-separator"></div>
	 <div class="grid-cell grid-cell-separator" onclick="javascript:colSort ('items', 'sku');"><span class="sort-link"><?= __ ('SKU') ?></span></div>
	 <div class="grid-cell grid-cell-separator" onclick="javascript:colSort ('items', 'item_desc');"><span class="sort-link"><?= __ ('Item description') ?></span></div>
	 <div class="grid-cell grid-cell-separator grid-cell-left"><?= __ ('Supplier'); ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-right"><?= __ ('Price'); ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-right"><?= __ ('Cost'); ?></div>
	 <div class="grid-cell grid-cell-separator grid-cell-right"><?= __ ('Inventory'); ?></div>

	 <?php
	 
	 foreach ($items as $item) {

		  $supplierID = 0;

		  if (!isset ($item ['inv_items'] [0])) {

				// set up a dummy
				
				$item ['inv_items'] [0] = ['supplier_id' => 0,
													'on_hand_req' => 0,
													'package_quantity' => 0,
													'on_hand_count' => 0];
		  }
		  
		  $action = 'onclick="openForm (' . $item ['id'] . ',\'/items/edit/' . $item ['id'] . '/0\')"';

	 ?>
	 
	 <div class="grid-row-wrapper" <?= $action ?>>

		  <div id="tag_<?= $item ['id'] ?>" class="grid-cell grid-cell-left"></div>
		  <div class="grid-cell grid-cell-left"><?= $item ['sku'] ?></div>
		  <div class="grid-cell grid-cell-left"><?php echo $item ['item_desc'];?></div>

		  <?php

		  if (isset ($item ['item_prices'] [0])) {

				$pricing = ["class" => "standard",
								"price" => $item ['item_prices'] [0] ['price'],
								"amount"=> $item ['item_prices'] [0] ['price'],
								"cost" => 0];

				if (isset ($item ['item_prices'] [0] ['pricing'])) {
					 
					 $pricing = json_decode ($item ['item_prices'] [0] ['pricing'], true);
				}

				$price = 0;
				$cost = 0;
				$profit = 0;
				$itemDesc = $item ['item_desc'];
				
				switch ($item ['item_prices'] [0] ['class']) {

					 case 'standard':
					 case 'metric':
					 case 'group':

						  $cost = floatval ($item ['item_prices'] [0] ['cost']);
						  
						  if (isset ($item ['item_prices'] [0] ['price'])) {

								$price = floatval ($item ['item_prices'] [0] ['price']);
						  }
						  else if (isset ($item ['item_prices'] [0] ['amount'])) {  // legacy

								$price = floatval ($item ['item_prices'] [0] ['amount']);
						  }
						  

						  if (($price > 0) && ($cost > 0)) {
								
								$profit = 100 - (($cost / $price) * 100.0);
						  }
						  else if (($price > 0) && ($item ['item_prices'] [0] ['cost'] == 0)) {
								
								$profit = 100.0;
						  }

						  $price = $this->moneyFormat ($price);
						  $cost = $this->moneyFormat ($cost);

						  $onHandCount = $item ['inv_items'] [0] ['on_hand_count'] < 0 ? 0 : $item ['inv_items'] [0] ['on_hand_count'];
						  
						  $supplierID = 0;
						  if ($item ['inv_items'] [0] ['supplier_id'] > 0) {

								$supplierID = $item ['inv_items'] [0] ['supplier_id'];
						  }
						  
						  $html =
								'<div class="grid-cell grid-cell-left">' . $suppliers [$supplierID] . '</div>' .
								'<div class="grid-cell grid-cell-right">'.$price.'</div>' .
								'<div class="grid-cell grid-cell-right">' . $cost . '</div>' .
								'<div class="grid-cell grid-cell-right">' . $onHandCount . '</div>';

						  echo $html;
						  break;
						  
					 case 'size':
						  
						  $html =
								'<div class="grid-cell grid-cell-left"></div>' . 
								'<div class="grid-cell grid-cell-left"></div>' . 
								'<div class="grid-cell grid-cell-left"></div>' . 
								'<div class="grid-cell grid-cell-left"></div>';

						  echo $html;
						  break;
						  
					 default:
						  
						  echo '<div class="grid-cell grid-cell-left"></div>';
						  echo '<div class="grid-cell grid-cell-left"></div>';
						  echo '<div class="grid-cell grid-cell-left"></div>';
						  echo '<div class="grid-cell grid-cell-left"></div>';
						  break;
				}
		  }
		  else {
				echo '<div class="grid-cell grid-cell-left"></div>';
				echo '<div class="grid-cell grid-cell-right">0.00</div>';
				echo '<div class="grid-cell grid-cell-right">0.00</div>';
		  }
		  
		  ?>
		  
	 </div>
	 
	 <?php
	 }
	 ?>
</div>

<div id="pages" class="grid-cell grid-cell-center grid-span-all"></div>
<div id="action_form"></div>

<?= $this->Html->script (['Items/index']); ?>


<?= $this->Html->css ("Items/standard_pricing") ?>
<script>

 var item = <?php echo json_encode ($item, true); ?>;
 var itemLinks = <?php echo json_encode ($item ['item_links'], true); ?>;
 var linkTypes = <?php echo json_encode ($linkTypes, true); ?>;
 
 if (itemLinks == null) {
	  
	  itemLinks = [];
 }
 
 var addLinkID = 0;

 console.log ('standard pricing php');
 console.log (itemLinks);

</script>

<?php

$this->debug ('standard pricing php...');

if ($controls) {
	 
	 include ('item_header.php');
}

?>

<form id="item_edit" name="item_edit" class="grid-span-all">
	 
	 <?= $this->Form->hidden ('item[id]', ['value' => $item ['id']]) ?>
	 <?= $this->Form->hidden ('item[bu_index]', ['value' => $buIndex]) ?>
	 <?= $this->Form->hidden ('item[item_price][id]', ['value' => $item ['item_price'] ['id']]) ?>
	 <?= $this->Form->hidden ('item[item_price][class]', ['value' => 'standard']) ?>
	 <?= $this->Form->hidden ('item[item_price][pricing]', ['value' => []]) ?>

	 <div class="form-grid item-edit-grid">
		  
		  <div class="form-cell form-desc-cell"><?= __('SKU/UPC') ?></div>
		  <?= $this->element ('sku', ['item' => $item]) ?>
		  
		  <div class="form-cell form-desc-cell"><?= __('Description') ?></div>
		  <?php
		  echo $this->input ('fa-text-size',
									['id' => 'item_desc',
									 'name' =>'item[item_desc]',
									 'value' => $item ['item_desc'],
									 'class' => 'form-control']);
		  ?>
		  <div class="form-cell form-desc-cell"><?= __('Department') ?></div>
		  <div class="select">
				<?php echo $this->Form->select ('item[department_id]',
														  $departments, ['value' => $item ['department_id'],
																			  'class' => 'custom-dropdown',
																			  'label' => false,
																			  'required' => 'required']); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Tax') ?></div>
		  <div class="select">
				<?php echo $this->Form->select ('item[item_price][tax_group_id]',
														  $taxGroups, ['value' => $item ['item_price'] ['tax_group_id'],
																			'class' => 'custom-dropdown',
																			'label' => false,
																			'required' => 'required']); ?>
		  </div>
		  
		  <?php

		  $taxInc = [0 => __ ('no'), 1 => __ ('yes')];
		  switch ($merchant ['locale']) {

				case 'en_US':
					 
					 break;
					 
				default:
					 
					 echo '<div class="form-cell form-desc-cell">' . __('Tax Included') . '</div>' +
							'<div class="select">' .
							$this->Form->select ('item[tax_inclusive]"',
														$taxInc,
														['name' => 'item[tax_inclusive]', 
														 'id' => 'item[tax_inclusive]', 
														 'class' => 'custom-dropdown',
														 'selected' => 0, 
														 'value' => 0, 
														 'label' => false]) .				 
							'</div>';

		  }
		  ?>
		  
	 </div>

	 <div class="form-grid item-edit-grid">
		  
		  <?php
		  
		  $price = sprintf ("%0.2f", 0.0);
		  $cost = sprintf ("%0.2f", 0.0);
		  
		  if (isset ($item ['item_price'] ['price'])) {
				
				$price = $this->moneyFormat ($item ['item_price'] ['price']);
		  }

		  if (isset ($item ['item_price'] ['cost'])) {
				
				$cost = $this->moneyFormat ($item ['item_price'] ['cost']);
		  }

		  ?>

		  <div class="form-cell form-desc-cell"><?= __('Price') ?></div>

		  <?php 
		  echo $this->input ('fa-dollar-sign',
									['id' => 'price',
									 'name' =>'item[item_price][price]',
									 'value' => $price,
									 'class' => 'form-control currency-format',
									 'placeholder' =>__ ('currency_placeholder')]);
		  
		  ?>
		  
		  <div class="form-cell form-desc-cell"><?= __('Cost') ?></div>
		  <?php
		  echo $this->input ('fa-dollar-sign',
									['id' => 'cost',
									 'name' =>'item[item_price][cost]',
									 'value' => $cost,
									 'class' => 'form-control currency-format',
									 'placeholder' =>__ ('currency_placeholder')]);
		  ?>

	 </div>
	 
	 <div class="form-section">
		  <?= __ ('Link items') ?>
	 </div>

	 <div class="form-grid item-link-grid">

		  <div class="form-cell form-control-cell">
				<input type="text" id="link_desc" name="link_desc" class="form-control" placeholder="SKU or description"></input>
		  </div>
		  
		  <div class="select">
				<?php echo $this->Form->select ('link_type',
														  $linkTypes,
														  ['name' => 'link_type', 
															'id' => 'link_type', 
															'class' => 'custom-dropdown', 
															'selected' => 0, 
															'value' => 0, 
															'label' => false]); ?>
		  </div>
		  
		  <div class="form-cell icon-cell">
				<i class="far fa-plus fa-med" onclick="javacript:addLink()"></i>
		  </div>
	 </div>

	 <span id="item_link_grid"></span>

	 <div class="form-section">
		  <?= __ ('Inventory') ?>
	 </div>

	 <div class="form-grid inv-grid">
		  
		  <div class="form-cell form-desc-cell"><?= __('Supplier') ?></div>
		  <div class="select">
				<?php 

				echo $this->Form->select ('item[inv_item][supplier_id]', 
												  $suppliers,
												  ['value' => $item ['inv_item'] ['supplier_id'], 
													'class' => 'custom-dropdown',
													'label' => false]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Supplier package size') ?></div>
		  <?php 
		  
		  echo $this->input ('fa-hashtag',
									['id' => 'package_quantity',
									 'name' =>'item[inv_item][package_quantity]',
									 'value' => $item ['inv_item']['package_quantity'],
									 'class' => 'form-control integer-format',
									 'onclick' => 'this.select ()',
									 'placeholoder' => __ ('0')]);
		  
		  ?>
		  
		  <div class="form-cell form-desc-cell"><?= __('Desired on hand') ?></div>
		  <?php 

		  echo $this->input ('fa-hashtag',
									['id' => 'on_hand_req',
									 'name' =>'item[inv_item][on_hand_req]',
									 'value' => $item ['inv_item'] ['on_hand_req'],
									 'class' => 'form-control integer-format',
									 'onclick' => 'this.select ()',
									 'placeholoder' => __ ('0')]);
		  
		  ?>

		  <div class="form-cell form-desc-cell"><?= __('On hand count') ?></div>
		  <?php 

		  echo $this->input ('fa-hashtag',
									['id' =>'on_hand_count',
									 'name' => 'item[inv_item][on_hand_count]',
									 'value' => $item ['inv_item'] ['on_hand_count'],
									 'class' =>'form-control integer-format',
									 'onclick' => 'this.select ()',
									 'placeholder' => __ ('0')]);
		  ?>
		  
	 </div>
</form>

<?= $this->Html->script ("Items/standard_pricing") ?>

<?php 
if ($controls) {
?>

	 <div class="form-submit-grid">
		  <div class="item-input">
				<button type="submit" id="item_update" class="btn btn-success"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div>
				<button type="button" class="btn btn-warning" onclick="del ('items', <?= $item ['id']?>, '<?= __ ('Delete') ?> <?= $item ['item_desc'] ?>')"><?= __ ('Delete') ?></button>
		  </div>
	 </div>

<?php 
}
?>
<?= $this->Html->script ("Items/standard_pricing"); ?>
<?= $this->Html->script ("Items/edit"); ?>

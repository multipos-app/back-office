<?= $this->Html->css ("Items/metric_pricing") ?>

<script>
 
 var item = <?php echo json_encode ($item, true); ?>;

</script>

<?php 
if ($controls) {
?>
	 <div class="form-section">
		  <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= $item ['item_desc']?>
	 </div>
<?php
}
?>

<form id="item_edit" name="item_edit" class="grid-span-all">
	 
	 <?= $this->Form->hidden ('item[id]', ['value' => $item ['id']]) ?>
	 <?= $this->Form->hidden ('item[item_price][class]', ['value' => $item ['item_price'] ['class']]) ?>
	 
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

		  <div class="form-cell form-desc-cell"><?= __('Measure') ?></div>
		  <div class="select">
				<?php echo $this->Form->select ('item[item_price][pricing][metric]',
														  $measures, 
														  ['value' => $item ['item_price'] ['pricing'] ['metric'],
															'class' => 'custom-dropdown',
															'label' => false,
															'required' => 'required']); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Decimal places') ?></div>
		  <div class="select">
				<?php echo $this->Form->select ('item[item_price][pricing][decimal_places]',
														  $decimalPlaces, 
														  ['value' => $item ['item_price'] ['pricing'] ['decimal_places'],
															'class' => 'custom-dropdown',
															'label' => false,
															'required' => 'required']); ?>
		  </div>

	 </div>

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
	 </div>
</form>

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


<script>
 $(".currency-format").mask ("<?= __ ('currency_format') ?>", {reverse: true});
 $(".integer-format").mask ("<?= __ ('currency_format') ?>", {reverse: true});
</script>

<?= $this->Html->script ("Items/edit") ?>
<?= $this->Html->script ("Items/metric_pricing") ?>

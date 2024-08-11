<?= $this->Html->css ("Items/standard_pricing") ?>

<script>
 
 var item = <?php echo json_encode ($item, true); ?>;
 var itemLinks = <?php echo json_encode ($item ['item_links'], true); ?>;
 var linkTypes = <?php echo json_encode ($linkTypes, true); ?>;
 
 if (itemLinks == null) {
	  
	  itemLinks = [];
 }

 var addLinkID = 0;

</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= $item ['item_desc']?>
</div>

<form id="item_edit" name="item_edit">
	 
	 <?= $this->Form->hidden ('item[id]', ['value' => $item ['id']]) ?>
	 	 
	 <?= $this->Form->hidden ('item[bu_index]', ['value' => $buIndex]) ?>
	 <?= $this->Form->hidden ('item[item_price][id]', ['value' => $item ['item_price'] ['id']]) ?>
	 <?= $this->Form->hidden ('item[item_price][class]', ['value' => 'open']) ?>
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
																			  'label' => false,
																			  'required' => 'required']); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Tax') ?></div>
		  <div class="select">
				<?php echo $this->Form->select ('item[item_price][tax_group_id]',
														  $taxGroups, ['value' => $item ['item_price'] ['tax_group_id'],
																			'label' => false,
																			'required' => 'required']); ?>
		  </div>
		  
		  <?php

		  $taxInc = [0 => __ ('no'), 1 => __ ('yes')];
		  switch ($merchant ['locale']) {

				case 'en_US':
					 
					 break;
					 
				default:
					 
					 echo '<div class="form-cell form-desc-cell">' . __('Tax Included') . '</div>' .
							'<div class="select">' .
							$this->Form->select ('item[tax_inclusive]',
														$taxInc, 
														['id' => 'item[tax_inclusive]', 
														 'class' => 'custom-dropdown',
														 'selected' => 0, 
														 'value' => 0, 
														 'label' => false]) .		 
							'</div>';

		  }
		  ?>
		  
	 </div>


	 <div class="form-submit-grid">
		  
		  <div class="item-input">
				<button type="submit" id="item_update" class="btn btn-success"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div>
				<button type="button" class="btn btn-warning" onclick="del ('items', <?= $item ['id']?>, '<?= __ ('Delete') ?> <?= $item ['item_desc'] ?>')"><?= __ ('Delete') ?></button>
		  </div>
	 </div>

</form>

<script>
 $(".currency-format").mask ("<?= __ ('currency_format') ?>", {reverse: true});
 $(".integer-format").mask ("<?= __ ('currency_format') ?>", {reverse: true});
</script>

<?= $this->Html->script ("Items/edit") ?>
<?= $this->Html->script ("Items/standard_pricing") ?>
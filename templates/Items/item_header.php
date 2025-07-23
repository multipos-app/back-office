
<?php

$this->debug ($item);

?>

<div class="row g-1 mt-3">
	 <div class="form-check form-switch">

		  <?php
		  $checked = $item ['enabled'] ? ' checked' : '';
		  ?>
		  <input type="checkbox" class="form-check-input" id="enabled" name="enabled" <?= $checked ?>>
		  <label class="grid-label" for="enabled"><?= __ ('Enable/disable item') ?></label>
		  
	 </div>
</div>

<div class="row g-1 mt-3 mt-3">
	 <?php 
	 echo $this->element ('sku', ['sku' => $item ['sku']]);
	 ?>
</div>

<div class="row g-1 mt-3">
	 <label for="item_desc" class="col-sm-2 form-label"><?= __ ('Description') ?></label>
	 <div class="col-sm-10">
		  <?=
		  $this->input ('item_desc',
							 ['name' => 'item_desc',
							  'value' => $item ['item_desc'],
							  'class' => 'form-control',
							  'required' => 'required']);
		  ?>
	 </div>
</div>

<div class="row g-1 mt-3">
	 <label for="item_desc" class="col-sm-2 form-label"><?= __ ('Image URL') ?></label>
	 <div class="col-sm-10">
		  <?=
		  $this->input ('item_url',
							 ['name' => 'item_url',
							  'id' => 'item_url',
							  'value' => isset ($item ['item_url']) ? $item ['item_url'] : '',
							  'class' => 'form-control']);
		  ?>
	 </div>
</div>

<div class="row g-1 mt-3">
 	 <div class="col-sm-12">
		  <?=
		  $this->Form->select ('department_id',
									  $departments,
									  ['name' => 'department_id',
										'value' => $item ['department_id'],
										'class' => 'form-select',
										'label' => false,
										'required' => 'required'])
		  ?>
	 </div>
</div>

<div class="row g-1 mt-3">
 	 <div class="col-sm-12">
		  <?=
		  $this->Form->select ('item_price[tax_group_id]',
									  $taxGroups,
									  ['value' => $item ['item_price'] ['tax_group_id'],
										'class' => 'form-select',
										'label' => false,
										'required' => 'required'])
		  ?>
	 </div>
</div>

<script> 
 $('#modal_title').html ('<?= $editTitle ?>');
</script>

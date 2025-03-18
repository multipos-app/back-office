
<input type="hidden" name="id" value="<?= $item ['id'] ?>">
<input type="hidden" name="item_price[class]" value="standard">

<div class="row g-1">
	 <div class="form-check form-switch">

		  <?php
		  $checked = $item ['enabled'] ? ' checked' : '';
		  ?>
		  <input type="checkbox" class="form-check-input" id="enabled" name="enabled" <?= $checked ?>>
		  <label class="grid-label" for="enabled"><?= __ ('Enable/disable item') ?></label>
		  
	 </div>
</div>

<div class="row g-1 mt-3">
	 <?php 
	 echo $this->element ('sku', ['sku' => $item ['sku']]);
	 ?>
</div>

<div class="row g-1">
	 <label for="item_desc" class="col-sm-3 form-label"><?= __ ('Description') ?></label>
	 <div class="col-sm-9">
		  <?=
		  $this->input ('item_desc',
							 ['name' => 'item_desc',
							  'value' => $item ['item_desc'],
							  'class' => 'form-control']);
		  ?>
	 </div>
</div>

<div class="row g-1">
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

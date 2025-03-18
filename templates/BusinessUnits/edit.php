
<form id="bu_edit" name="bu_edit" method="post" action="/business-units/edit/<?= $bu ['id'] ?>"">

	 <div class="row g-1 m-3">
		  <label for="business_name" class="col-sm-4 form-label"><?= __('Business name') ?></label>
		  <div class="col-sm-8">

				<?= 
				$this->Form->input ('business_name', 
										  ['id' => 'business_name', 
											'value' => $bu ['business_name'], 
											'class' => 'form-control', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>
	 
	 <div class="row g-1 m-3">
		  <label for="email" class="col-sm-4 form-label"><?= __('Email') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('email', 
										  ['id' => 'email', 
											'value' => $bu ['email'], 
											'class' => 'form-control', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="addr_1" class="col-sm-4 form-label"><?= __('Address') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('addr_1', 
										  ['id' => 'addr_1', 
											'value' => $bu ['addr_1'], 
											'class' => 'form-control', 
											'label' => false,
											'required' => 'required']) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="addr_2" class="col-sm-4 form-label"><?= __('Address 2') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('addr_2', 
										  ['id' => 'addr_2', 
											'value' => $bu ['addr_2'], 
											'class' => 'form-control', 
											'label' => false]) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="city" class="col-sm-4 form-label"><?= __('City') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('city', 
										  ['id' => 'city', 
											'value' => $bu ['city'], 
											'class' => 'form-control', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="state" class="col-sm-4 form-label"><?= __('City') ?></label>
		  
		  <div class="col-sm-8">
				<?=
				$this->Form->select ('state',
											$states,
											['name' => 'state', 
											 'id' => 'state',  
											 'class' => 'form-select',
											 'value' => $bu ['state'], 
											 'label' => false])
				?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="postal_code" class="col-sm-4 form-label"><?= __('Zip/postal code') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('postal_code', 
										  ['id' => 'postal_code', 
											'value' => $bu ['postal_code'], 
											'class' => 'form-control postal-code-format', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="phone_1" class="col-sm-4 form-label"><?= __('Phone') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->control ('phone_1',
											 ['id' => 'phone_1', 
											  'value' => $bu ['phone_1'],
											  'class' => 'form-control phone-format',
											  'label' => false,
											  'required' => 'required']) 
				?>
		  </div>
	 </div>
	 
	 <div class="text-center">
		  <button type="submit" class="btn btn-success">Save</button>
	 </div>
		  	 
</form>

<script>
 
 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 $(".postal-code-format").mask ("<?= __ ('postal_code_format') ?>", {});

</script>

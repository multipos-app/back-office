

<form id="customer_edit" name="customer_edit" method="post" action="/customers/edit/<?= $customer ['id'] ?>" class="needs-validation">

	 <div class="row g-1 m-3">
		  <label for="fname" class="col-sm-4 form-label"><?= __('First name') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->input ('fname', 
										  ['id' => 'fname', 
											'value' => $customer ['fname'], 
											'class' => 'form-control', 
											'label' => false])
				?>
		  </div>
	 </div>
	 
	 <div class="row g-1 m-3">
		  <label for="lname" class="col-sm-4 form-label"><?= __('Last name') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->input ('lname', 
										  ['id' => 'lname', 
											'value' => $customer ['lname'], 
											'class' => 'form-control', 
											'label' => false]) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="email" class="col-sm-4 form-label"><?= __('Email') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->input ('email', 
										  ['id' => 'email', 
											'value' => $customer ['email'], 
											'class' => 'form-control', 
											'label' => false]) ?>
		  </div>
	 </div>


	 <div class="row g-1 m-3">
		  <label for="phone" class="col-sm-4 form-label"><?= __('Phone') ?></label>
		  <div class="col-sm-8 input-group">
				
				<?= 
				$this->Form->input ('phone', 
										  ['id' => 'phone', 
											'value' => $customer ['phone'], 
											'class' => 'form-control phone-format has-validation',
											'required' => 'required',
											'label' => false]) ?>
				<div class="invalid-feedback">
					 At least last 4 digits of phone number required.
            </div>
		  </div>
	 </div>
	 
	 <div class="row g-1 m-3">
		  <label for="addr_1" class="col-sm-4 form-label"><?= __('Address') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('addr_1', 
										  ['id' => 'addr_1', 
											'value' => $customer ['addr_1'], 
											'class' => 'form-control', 
											'label' => false]) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="addr_2" class="col-sm-4 form-label"><?= __('Address 2') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('addr_2', 
										  ['id' => 'addr_2', 
											'value' => $customer ['addr_2'], 
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
											'value' => $customer ['city'], 
											'class' => 'form-control', 
											'label' => false]) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="state" class="col-sm-4 form-label"><?= __('State') ?></label>
		  
		  <div class="col-sm-8">
				<?=
				$this->Form->select ('state',
											$states,
											['name' => 'state', 
											 'id' => 'state',  
											 'class' => 'form-select',
											 'value' => $customer ['state'], 
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
											'value' => $customer ['postal_code'], 
											'class' => 'form-control postal-code-format', 
											'label' => false]) ?>
		  </div>
	 </div>
	 
	 <div class="row g-3 mt-3">
		  <div class="col-sm-9 d-grid text-center"></div>
 		  <div class="col-sm-3 d-grid text-center">
				<button type="submit" class="btn btn-success"><?= __ ('Save') ?></button>
		  </div>
	 </div>
</form>

<script>
 
 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 $(".postal-code-format").mask ("<?= __ ('postal_code_format') ?>", {});
 
</script>
<script src="/assets/js/form_validate.js"></script>


<form id="supplier_edit" name="supplier_edit" method="post" action="/suppliers/edit/<?= $supplier ['id'] ?>">

	 <div class="row g-1 m-3">
		  <label for="fname" class="col-sm-4 form-label"><?= __('Supplier name') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->input ('supplier_name', 
										  ['id' => 'supplier_name', 
											'value' => $supplier ['supplier_name'], 
											'class' => 'form-control', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="fname" class="col-sm-4 form-label"><?= __('Contact') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->input ('contact1', 
										  ['id' => 'contact1', 
											'value' => $supplier ['contact1'], 
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
											'value' => $supplier ['email'], 
											'class' => 'form-control', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>


	 <div class="row g-1 m-3">
		  <label for="phone" class="col-sm-4 form-label"><?= __('Phone') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->input ('phone', 
										  ['id' => 'phone', 
											'value' => $supplier ['phone'], 
											'class' => 'form-control phone-format', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>
	 
	 <div class="row g-1 m-3">
		  <label for="addr1" class="col-sm-4 form-label"><?= __('Address') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('addr1', 
										  ['id' => 'addr1', 
											'value' => $supplier ['addr1'], 
											'class' => 'form-control phone-format', 
											'label' => false,
											'required' => 'required']) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="addr2" class="col-sm-4 form-label"><?= __('Address 2') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('addr2', 
										  ['id' => 'addr2', 
											'value' => $supplier ['addr2'], 
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
											'value' => $supplier ['city'], 
											'class' => 'form-control', 
											'label' => false, 
											'required' => 'required']) ?>
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
											 'value' => $supplier ['state'], 
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
											'value' => $supplier ['postal_code'], 
											'class' => 'form-control postal-code-format', 
											'label' => false, 
											'required' => 'required']) ?>
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

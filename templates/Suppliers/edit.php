
<form class="row g-1" id="supplier_edit" name="supplier_edit" method="post" action="/suppliers/edit/<?= $supplier ['id'] ?>">

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
				$this->Form->input ('contact', 
										  ['id' => 'contact', 
											'value' => $supplier ['contact'], 
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
											'value' => $supplier ['email'], 
											'class' => 'form-control', 
											'label' => false]) ?>
		  </div>
	 </div>


	 <div class="row g-1 m-3">
		  <label for="phone_1" class="col-sm-4 form-label"><?= __('Phone') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->input ('phone_1', 
										  ['id' => 'phone_1', 
											'value' => $supplier ['phone_1'], 
											'class' => 'form-control phone-format', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>
	 
	 <div class="row g-1 m-3">
		  <label for="phone_2" class="col-sm-4 form-label"><?= __('Fax') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->input ('phone_2', 
										  ['id' => 'phone_2', 
											'value' => $supplier ['phone_2'], 
											'class' => 'form-control phone-format', 
											'label' => false]) ?>
		  </div>
	 </div>

	 <div class="row g-1 m-3">
		  <label for="addr_1" class="col-sm-4 form-label"><?= __('Address') ?></label>
		  
		  <div class="col-sm-8">
				<?= 
				$this->Form->input ('addr_1', 
										  ['id' => 'addr_1', 
											'value' => $supplier ['addr_1'], 
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
											'label' => false]) ?>
		  </div>
	 </div>
	 
	 <div class="row g-3 mt-3">
		  <div class="col-sm-9 d-grid text-center"></div>
 		  <div class="col-sm-3 d-grid text-center">
				<button type="submit" class="btn btn-success" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
		  </div>
	 </div>
	 
</form>

<script>

 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 $(".postal-code-format").mask ("<?= __ ('postal_code_format') ?>", {});

</script>

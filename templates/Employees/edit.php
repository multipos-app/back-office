

<form id="employee_edit" name="employee_edit" method="post" action="/employees/edit/<?= $employee ['id'] ?>">
	 
	 <div class="form-grid employee-edit-grid">

		  <div class="row g-1 m-3">
				<label for="username" class="col-sm-4 form-label"><?= __('Cashier number') ?></label>
				<div class="col-sm-8">
					 
					 <?= 
					 $this->Form->input ('username', 
												['id' => 'username', 
												 'value' => $employee ['username'], 
												 'class' => 'form-control integer-format', 
												 'label' => false, 
												 'required' => 'required']) ?>
				</div>
		  </div>
		  
		  <div class="row g-1 m-3">
				<label for="fname" class="col-sm-4 form-label"><?= __('First name') ?></label>
				<div class="col-sm-8">
					 
					 <?= 
					 $this->Form->input ('fname', 
												['id' => 'fname', 
												 'value' => $employee ['fname'], 
												 'class' => 'form-control', 
												 'label' => false, 
												 'required' => 'required']) ?>
				</div>
		  </div>
		  
		  <div class="row g-1 m-3">
				<label for="lname" class="col-sm-4 form-label"><?= __('Last name') ?></label>
				<div class="col-sm-8">
					 
					 <?= 
					 $this->Form->input ('lname', 
												['id' => 'lname', 
												 'value' => $employee ['lname'], 
												 'class' => 'form-control', 
												 'label' => false, 
												 'required' => 'required']) ?>
				</div>
		  </div>

		  <div class="row g-1 m-3">
				<label for="password1" class="col-sm-4 form-label"><?= __('PIN') ?></label>
				<div class="col-sm-8">
					 
					 <?= 
					 $this->Form->input ('password1', 
												['id' => 'password1', 
												 'value' => $employee ['password1'], 
												 'class' => 'form-control integer-format', 
												 'label' => false, 
												 'required' => 'required']) ?>
				</div>
		  </div>

		  <div class="row g-1 m-3">
				<label for="password2" class="col-sm-4 form-label"><?= __('Repeat PIN') ?></label>
				<div class="col-sm-8">
					 
					 <?= 
					 $this->Form->input ('password2', 
												['id' => 'password2', 
												 'value' => $employee ['password2'], 
												 'class' => 'form-control integer-format', 
												 'label' => false, 
												 'required' => 'required']) ?>
				</div>
		  </div>

		  <div class="row g-1 m-3">
				<label for="profile" class="col-sm-4 form-label"><?= __('PIN') ?></label>
				<div class="col-sm-8">
					 
					 <?= 
					 $this->Form->select ('profile_id',
												 $profiles, ['class' => 'form-select',
																 'label' => false,
																 'value' => $employee ['profile_id'],
																 'required' => true]);
					 ?>
				</div>
		  </div>

		  <div class="row g-3 mt-3">
				<div class="col-sm-9 d-grid text-center"></div>
 				<div class="col-sm-3 d-grid text-center">
					 <button type="submit" class="btn btn-success" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
				</div>
		  </div>

	 </div>
</form>

<script>
 $(".integer-format").mask ("<?= __ ('integer_format') ?>");
</script>

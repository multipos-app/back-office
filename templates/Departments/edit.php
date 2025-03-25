

<form id="department_edit" name="department_edit" method="post" action="/departments/edit/<?= $department ['id'] ?>">
	 
	 <div class="row g-1 m-3">
		  <label for="department_desc" class="col-sm-4 form-label"><?= __('Department') ?></label>
		  <div class="col-sm-8">

				<?= 
				$this->Form->input ('department_desc', 
										  ['id' => 'department_desc', 
											'value' => $department ['department_desc'], 
											'class' => 'form-control', 
											'label' => false, 
											'required' => 'required']) ?>
		  </div>
	 </div>
	 
	 <div class="row g-1 m-3">
		  <label for="is_negative" class="col-sm-4 form-label"><?= __('Is negative') ?></label>

		  <?php
		  
		  $checked = '';
		  if ($department ['is_negative']) $checked = ' checked';
		  ?>
        <div class="col-sm-8 form-check form-switch">
            <input name="is_negative" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"<?= $checked ?>>
        </div>
		  
    </div>
	 
	 <div class="row g-1 m-3">

		  <label for="department_type" class="col-sm-4 form-label"><?= __('Type') ?></label>
		  <div class="col-sm-8">
				
				<?= 
				$this->Form->select ('department_type"',
											$departmentTypes,
											['name' => 'department_type', 
											 'id' => 'department_type',
											 'class' => 'form-select',
											 'selected' => 0, 
											 'value' => $department ['department_type'], 
											 'label' => false])
				?>
		  </div>
	 </div>

	 <div class="row g-3 mt-3">
		  <div class="col-sm-9 d-grid text-center"></div>
 		  <div class="col-sm-3 d-grid text-center">
				<button type="submit" class="btn btn-succes" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
		  </div>
	 </div>
	 
</form>

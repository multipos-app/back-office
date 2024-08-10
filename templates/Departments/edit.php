<?= $this->Html->css ("Departments/edit") ?>

<script>
 var department = <?php echo json_encode ($department, true); ?>;
</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= $department ['department_desc']?>
</div>

<form id="department_edit" name ="department_edit">
	 
	 <div class="form-grid department-edit-grid">

		  <input type="hidden" name="department_id" value="<?= $department ['id'] ?>">
		  
		  <div class="form-cell form-desc-cell"><?= __('Description') ?></div>

		  <?php
		  echo $this->input ('fa-text-size',
									['id' =>'department_desc',
									 'name' => 'department_desc',
									 'value' => $department ['department_desc'],
									 'class' =>'form-control',
									 'keypress' => 'this->select ()',
									 'placeholder' => __ ('Department description')]);
		  ?>
		  
		  <div class="form-cell form-desc-cell"><?= __('Negative Prices (deposit returns)') ?></div>
		  <div class="select">
				<?=
				$this->Form->select ('is_negative"',
											$isNegative,
											['name' => 'is_negative', 
											 'id' => 'is_negative',
											 'class' => 'custom-dropdown',
											 'selected' => 0, 
											 'value' => $department ['is_negative'], 
											 'label' => false, 
											 'onclick' => 'this.select ()'])
				?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Departent type') ?></div>
		  <div class="select">
				<?=
				$this->Form->select ('department_type',
											$departmentTypes,
											['name' => 'department_type', 
											 'id' => 'department_type', 
											 'class' => 'custom-dropdown',
											 'selected' => 0, 
											 'value' => $department ['department_type'], 
											 'label' => false])
				?>
		  </div>
	 </div>

	 <div class="form-submit-grid">
		  
		  <div>
				<button type="submit" id="department_update" class="btn btn-success"><?= __ ('Save') ?></button>
		  </div>

		  <div>
				<button type="button" class="btn btn-warning" onclick="del ('departments', <?= $department ['id']?>, '<?= __ ('Delete') ?> <?= $department ['department_desc'] ?>')"><?= __ ('Delete') ?></button>
		  </div>
	 </div>

</form>

<?= $this->Html->script ("Departments/edit") ?>

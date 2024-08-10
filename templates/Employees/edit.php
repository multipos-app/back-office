<?= $this->Html->css ("Employees/edit") ?>
<script>
 var employee = <?php echo json_encode ($employee, true); ?>;
</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= $employee ['lname']?>,&nbsp;<?= $employee ['fname']?>
</div>

<form id="employee_edit" name="employee_edit">
	 
	 <div class="form-grid employee-edit-grid">

		  <div class="form-cell form-desc-cell"><?= __('Cashier number') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('username', ['value' => $employee ['username'], 'class' => 'form-control', 'label' => false, 'required' => true]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('First Name') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('fname', ['value' => $employee ['fname'], 'class' => 'form-control', 'label' => false, 'required' => true]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Last Name') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('lname', ['value' => $employee ['lname'], 'class' => 'form-control', 'label' => false, 'required' => true]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('PIN') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('password1', ['value' => '', 'type' => 'password', 'class' => 'form-control', 'label' => false, 'required' => true, 'autocomplete' => false]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Repeat PIN') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('password2', ['value' => '', 'type' => 'password', 'class' => 'form-control', 'label' => false, 'required' => true, 'autocomplete' => false]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Profile') ?></div>
		  <div class="form-cell form-control-cell">
				<div class="select">
					 <?php echo $this->Form->select ('profile_id',
																$profiles, ['class' => 'custom-dropdown',
																				'label' => false,
																				'value' => $employee ['profile_id'],
																				'required' => true]); ?>
				</div>
		  </div>
	 </div>

	 <div class="form-submit-grid">
		  
		  <div>
				<button type="submit" id="employee_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div>
				<button type="button" class="btn btn-warning" onclick="del ('employees', <?= $employee ['id']?>, '<?= __ ('Delete') ?> <?= $employee ['fname'] ?>')"><?= __ ('Delete') ?></button>
		  </div>
		  
	 </div>

</form>

<?= $this->Html->script ("Employees/edit") ?>

<?= $this->Html->css ("Suppliers/edit") ?>

<script>
 
 var supplier = <?php echo json_encode ($supplier, true); ?>;

</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= $supplier ['supplier_name']?>
</div>

<form id="supplier_edit" name ="supplier_edit">
	 
	 <div class="form-grid supplier-edit-grid">

		  <input type="hidden" name="supplier_id" value="<?= $supplier ['id'] ?>"/>

		  <div class="form-cell form-desc-cell"><?= __('Supplier name') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('supplier_name', ['class' => 'form-control',
																					'label' => false,
																					'required' => true,
																					'placeholder' => __ ('Supplier name'),
																					'value' => $supplier ['supplier_name'], 
																					'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Contact') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('contact1', ['class' => 'form-control',
																			 'label' => false,
																			 'required' => true,
																			 'placeholder' => __ (''),
																			 'value' => $supplier ['contact1'], 
																			 'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Email') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('email', ['class' => 'form-control email-format',
																		 'id' => 'email',
																		 'label' => false,
																		 'required' => true,
																		 'placeholder' => __ ('user@email.com'),
																		 'value' => $supplier ['email'], 
																		 'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Phone') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('phone1', ['id' => 'phone',
																		  'class' => 'form-control phone-format',
																		  'label' => false,
																		  'required' => true,
																		  'value' => $supplier ['phone1'], 
																		  'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Address 1') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('addr1', ['class' => 'form-control',
																		 'label' => false,
																		 'placeholder' => __ (''),
																		 'value' => $supplier ['addr1'], 
																		 'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Address 2') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('addr2', ['class' => 'form-control',
																		 'label' => false,
																		 'placeholder' => __ (''),
																		 'value' => $supplier ['addr2'], 
																		 'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('City') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('addr3', ['class' => 'form-control',
																		 'label' => false,
																		 'placeholder' => __ (''),
																		 'value' => $supplier ['addr3'], 
																		 'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('State') ?></div>
		  <div class="select">
				<?php echo $this->Form->select ('addr5', $states, ['label' => false,
																					'class' => 'custom-dropdown',
																					'value' => $supplier ['addr5'], 
																					'onclick' => "this.select ()"]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Postal Code') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('addr6', ['class' => 'form-control postal-code-format',
																		 'label' => false,
																		 'placeholder' => __ ('######-###'),
																		 'value' => $supplier ['addr6'], 
																		 'onclick' => "this.select ()"]); ?>
		  </div>
		  
	 </div>
	 
	 <div class="form-submit-grid">
		  
		  <div>
				<button type="submit" id="supplier_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div>
				<button type="button" class="btn btn-warning" onclick="del ('suppliers', <?= $supplier ['id']?>, '<?= __ ('Delete') ?> <?= $supplier ['supplier_name'] ?>')"><?= __ ('Delete') ?></button>
		  </div>
		  
	 </div>

</form>

<?= $this->Html->script ("Suppliers/edit") ?>

<script>

 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 $(".postal-code-format").mask ("<?= __ ('postal_code_format') ?>", {});
 $('#email').on ('input', validate);
 $('#email').focusout (function () { $('#email_result').html (''); });

</script>

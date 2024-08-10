<?= $this->Html->css ("Customers/edit") ?>

<script>
	 
 var customer = <?php echo json_encode ($customer, true); ?>;

</script>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="closeForm ()"></i><?= $customer ['email']?>
</div>

<form id="customer_edit" name ="customer_edit">
	 
	 <input type="hidden" name="id" value="<?= $customer ['id'] ?>"/>
	 
	 <div class="form-grid customer-edit-grid">

		  <div class="form-cell form-desc-cell"><?= __('First name') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('fname', ['class' => 'form-control',
																		'label' => false,
																		'required' => true,
																		'placeholder' => __ ('First name'),
																		'value' => $customer ['fname'], 
																		'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Last name') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('lname', ['class' => 'form-control',
																		'label' => false,
																		'required' => true,
																		'placeholder' => __ ('Last name'),
																		'value' => $customer ['lname'], 
																		'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Email') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('email', ['class' => 'form-control',
																		 'label' => false,
																		 'required' => true,
																		 'placeholder' => __ ('user@email.com'),
																		 'value' => $customer ['email'], 
																		 'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Phone') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('phone', ['id' => 'phone',
																		 'class' => 'form-control phone-format',
																		 'label' => false,
																		 'required' => true,
																		 'value' => $customer ['phone'], 
																		 'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Address 1') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('addr_1', ['class' => 'form-control',
																		  'label' => false,
																		  'placeholder' => __ (''),
																		  'value' => $customer ['addr_1'], 
																		  'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('Address 2') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('addr_2', ['class' => 'form-control',
																		  'label' => false,
																		  'placeholder' => __ (''),
																		  'value' => $customer ['addr_2'], 
																		  'onclick' => "this.select ()"]); ?>
		  </div>
		  
		  <div class="form-cell form-desc-cell"><?= __('City') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('city', ['class' => 'form-control',
																		'label' => false,
																		'placeholder' => __ (''),
																		'value' => $customer ['city'], 
																		'onclick' => "this.select ()"]); ?>
		  </div>
		 
		  <div class="form-cell form-desc-cell"><?= __('State') ?></div>
		  <div class="select">
				<?php echo $this->Form->select ('state', $states, ['label' => false,
																					'value' => $customer ['state'],
																					'class' => 'custom-dropdown',
																					'onclick' => "this.select ()"]); ?>
		  </div>

		  <div class="form-cell form-desc-cell"><?= __('Postal Code') ?></div>
		  <div class="form-cell form-control-cell">
				<?php echo $this->Form->control ('postal_code', ['class' => 'form-control postal-code-format',
																				 'label' => false,
																				 'placeholder' => __ ('######-###'),
																				 'value' => $customer ['postal_code'], 
																				 'onclick' => "this.select ()"]); ?>
		  </div>
	 </div>
	 
	 <div class="form-submit-grid">
		  
		  <div>
				<button type="submit" id="customer_update" class="btn btn-success btn-block control-button"><?= __ ('Save') ?></button>
		  </div>
		  
		  <div>
				<div class="btn btn-warning btn-block control-button" onclick="javascript:window.close ()"><?= __ ('Cancel') ?></div>
		  </div>
		  
	 </div>

</form>

<?= $this->Html->script ("Customers/edit") ?>

<script>
 
 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 $(".postal-code-format").mask ("<?= __ ('postal code format') ?>", {});
 
</script>

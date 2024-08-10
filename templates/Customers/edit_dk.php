
<?= $this->Form->create ($customer) ?>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __ ('Customer') ?></legend>
	 <div class="container edit">
		  
		  <div class="row top10">
				<div class="col-sm-4 text-right"><?= __('Contact') ?></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->control ('contact', ['class' => 'form-control', 'label' => false, 'required' => true, 'placeholder' => __ ('Contact')]); ?>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-4 text-right"><?= __('Email') ?></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->control ('email', ['class' => 'form-control', 'label' => false, 'required' => true, 'placeholder' => __ ('user@email.com')]); ?>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-4 text-right"><?= __('Phone') ?></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->control ('phone', ['class' => 'form-control phone', 'label' => false, 'required' => true, 'placeholder' => __ ('nnnnnnnn')]); ?>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-4 text-right"><?= __('Address 1') ?></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->control ('addr_1', ['class' => 'form-control', 'label' => false, 'placeholder' => __ ('')]); ?>
				</div>
		  </div>
	
		  <div class="row top10">
				<div class="col-sm-4 text-right"><?= __('Postal Code') ?></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->control ('postal_code', ['class' => 'form-control', 'label' => false, 'placeholder' => __ ('')]); ?>
				</div>
		  </div>
		  
		  <div class="row top10">
				<div class="col-sm-4 text-right"><?= __('City') ?></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->control ('city', ['class' => 'form-control', 'label' => false, 'placeholder' => __ ('')]); ?>
				</div>
		  </div>
		  		  
		  <div class="row top10">
				<div class="col-sm-4 text-right"></div>
				<div class="col-sm-4">
					 <div class="checkbox checkbox-primary">
						  <input type="checkbox" class="styled" name="blocked" id="blocked" type="checkbox"<?php echo $customer ['blocked'] ? ' checked' : ''; ?>>
						  <label for="blocked"><?= __ ('Blocked') ?></label>
					 </div>
				</div>
		  </div>
		  		  
		  <div class="row top15">
				<div class="col-sm-4 text-right"><?= __('Blocked Reason') ?></div>
				<div class="col-sm-4">
					 
					 <?php echo $this->Form->select ('reason_code', $reasonCodes, ['class' => 'custom-dropdown', 'label' => false]); ?>
					 
				</div>
		  </div>

		  <div class="row top10">
				<div class="col-sm-4 text-right top5"><?= __('Discount %') ?></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->control ('discount_percent', ['class' => 'form-control', 'label' => false, 'placeholder' => __ ('')]); ?>
				</div>
		  </div>

		  <div class="row top10">
				<div class="col-sm-4 text-right top5"><?= __('EAN') ?></div>
				<div class="col-sm-4">
					 <?php echo $this->Form->control ('ean', ['class' => 'form-control', 'label' => false, 'placeholder' => __ ('EAN')]); ?>
				</div>
		  </div>

		  <div class="row top10">
				<div class="col-sm-4 text-right"><?= __('Sales') ?></div>
				<div class="col-sm-2">
					<?php echo __('Month') .': ' . money_format ('%!i', $totals [0]); ?>
				</div>
				<div class="col-sm-2">
					<?php echo __('Year') .': ' . money_format ('%!i', $totals [1]); ?>
				</div>
		  </div>

		  <div class="row top30">
				<div class="col-sm-4 text-right"></div>
				<div class="col-sm-2">
					 <?php echo $this->Form->submit (__ ('Save'), ['class' => 'btn btn-success btn-block']); ?>
				</div>
				<div class="col-sm-2">
					 <?php echo $this->Form->submit (__ ('Cancel'), ['class' => 'btn btn-warning btn-block']); ?>
				</div>
		  </div>
	 </div>
</fieldset>

<?= $this->Html->script ('number-format.js') ?>

<script>
 
 $(document).ready (function () {

	  $('#phone').on ('keydown touchend', function (e) {

			numberFormat (this, e, 8);
	  });
 });
 
</script>

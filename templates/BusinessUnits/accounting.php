<?php

?>

<?php 
echo $this->Form->create ('Accounting');
?>
<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __ ('External Accounting Setup') ?></legend>
	 <div class="container edit">

		  <div class="row top15">
				
				<div class="col-sm-4">
					 
					 <?php echo $this->Form->select ('accounting_provider', $accounting, ['class' => 'custom-dropdown', 'label' => false]); ?>
					 
				</div>

		  </div>
		  
		  <div class="row top15">
				<div class="col-sm-4 text-center">
					 <?php echo $this->Form->submit (__ ('Continue'), ['class' => 'btn btn-primary btn-block']); ?>
				</div>
		  </div>
		  
	 </div>
	 
</fieldset>

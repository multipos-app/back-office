 <?php

$this->debug ('buttons sale discount...');

?>

<input type="hidden" name="class" value="SaleDiscount">

<div class="row mb-3">
    <label for="params[receipt_text]" class="col-sm-4 col-form-label"><?= __ ('Discount description') ?></label>
    <div class="col-sm-8">
		  
		  <?php 
		  echo $this->input ('receipt_text',
									['name' =>'params[receipt_text]',
									 'value' => $button ['params'] ['receipt_text'],
									 'class' => 'form-control',
									 'placeholder' => '',
									 'data-bs-toggle' => 'tooltip',
									 'data-bs-placement' => 'top',
									 'title' => __ ('Receipt text')]);
		  
		  ?>
		  
	 </div>
</div>

<div class="row mb-3">
    <label for="params[percent]" class="col-sm-4 col-form-label"><?= __ ('Discount percent') ?></label>
    <div class="col-sm-8">
		  
		  <?php 
		  echo $this->input ('percent',
									['name' => 'params[percent]',
									 'value' => $button ['params'] ['percent'],
									 'class' => 'form-control percent-format',
									 'placeholder' => '0',
									 'data-bs-toggle' => 'tooltip',
									 'data-bs-placement' => 'top',
									 'title' => __ ('Amount of discount as percent')]);
		  
		  ?>
	 </div>
</div>

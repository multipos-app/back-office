<?php include ('button_header.php');

$this->debug ('sale discount... ');
$this->debug ($button);

?>
<style>

 .discount-grid {

	  display: grid !important;
     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 1fr;
	  grid-row-gap: 15px;
 }

 .visibility-off {

	  display:none;
 }
 
</style>

<input type="hidden" name="class" value="SaleDiscount">

<div class="form-grid discount-grid">
	 
	 <div class="form-cell form-desc-cell">
		  <?= __ ('Discount description') ?>
	 </div>
	 
	 <div class="grid-cell grid-cell-left">
		  
		  <?php 
		  echo $this->input ('fa-text-size',
									['name' =>'params[receipt_text]',
									 'value' => $button ['params'] ['receipt_text'],
									 'class' => 'form-control',
									 'placeholder' => '']);
		  
		  ?>
		  
	 </div>
	 
</div>

<div class="form-grid discount-grid">
	 
	 <div class="form-cell form-desc-cell">
		  <?= __ ('Enter percent') ?>
	 </div>
	 
	 <div class="grid-cell grid-cell-left">
		  
		  <?php 
		  echo $this->input ('fa-hashtag',
									['id' => 'discount_percent_input',
									 'name' =>'params[percent]',
									 'value' => $button ['params'] ['percent'],
									 'class' => 'form-control percent-format',
									 'placeholder' => '0']);
		  
		  ?>
		  
	 </div>
</div>

<?php include ('button_footer.php'); ?>

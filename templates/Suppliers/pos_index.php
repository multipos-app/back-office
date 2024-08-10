<?= $this->Html->css ("pos-styles.css") ?>
<style>

 .main-grid {
	  
     display: grid;
     width: *;
     grid-template-rows: auto;
     grid-template-columns: 1fr;
	  grid-gap: 10px;
 	  font-size: 24px !important;
	  margin-left:150px;
	  margin-right: 150px;
 }
 
</style>

<fieldset class="maintenance-border">
	 <legend class="maintenance-border"><?= __('Suppliers') ?></legend>

	 <div class="main-grid top30">

		  <?php
		  foreach ($suppliers as $supplier) {
	
		  ?>
				<div class="grid-cell grid-cell-center keypad-btn pos-app-btn" onclick="javascript:orders (<?= $supplier ['id'] ?>);">
					 <?= $supplier ['supplier_name'] ?>
				</div>
	 
		  <?php
		  }
		  ?>
	 
	 </div>
	 
</fieldset>

<script>

 function orders (supplierID) {

	  	  window.location = '/suppliers/orders/' + supplierID;
 }

 
 $(document).ready (function () {

	  session ();			
	  home = window.location.href;
 });
 
</script>

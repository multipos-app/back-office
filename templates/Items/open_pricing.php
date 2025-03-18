
<form class="row g-1" id="<?= $item ['template']?>_edit">

	 <?php include ('item_header.php')  ?>

 	 <div class="row g-1">
 		  <div class="col-sm-12">
				<?=
				$this->Form->select ('item_price[tax_group_id]',
											$taxGroups,
											['value' => $item ['item_price'] ['tax_group_id'],
											 'class' => 'form-select',
											 'label' => false,
											 'required' => 'required'])
				?>
		  </div>
	 </div>
	 <div class="row g-1 mt-3">
		  <div class="col-sm-12 text-center">
				<h5><?= __ ('Inventory') ?></h5>
		  </div>
	 </div>
	 
 	 <div class="row g-1">
  		  <div class="col-sm-12">
				<?=
				$this->Form->select ('inv_item[supplier_id]',
											$suppliers,
											['name' => 'inv_item[supplier_id]',
											 'value' => $item ['inv_item'] ['supplier_id'],
											 'class' => 'form-select',
											 'label' => false,
											 'required' => 'required'])
				?>
		  </div>
		  
	 </div>

	 <div class="row g-1">
		  <label for="inv_item[package_size]" class="col-sm-4 form-label"><?= __ ('Package size') ?></label>
  		  <div class="col-sm-8">
				<input type="text" dir="rtl" class="form-control integer-format" name="inv_item[package_quantity]" value="<?= $item ['inv_item'] ['package_quantity'] ?>">
		  </div>
	 </div>
	 
	 <div class="row g-1">
		  <label for="inv_item[desired_on_hand]" class="col-sm-4 form-label"><?= __ ('Desired on hand') ?></label>
  		  <div class="col-sm-8">
				<input type="text" dir="rtl" class="form-control integer-format" name="inv_item[on_hand_req]" value="<?= $item ['inv_item'] ['on_hand_req'] ?>">
		  </div>
	 </div>
	 
	 <div class="row g-1">
		  <label for="inv_item[on_hand_count]" class="col-sm-4 form-label"><?= __ ('On hand count') ?></label>
  		  <div class="col-sm-8">
				<input type="text" dir="rtl" class="form-control integer-format" name="inv_item[on_hand_count]" value="<?= $item ['inv_item'] ['on_hand_count'] ?>">
		  </div>
	 </div>
	 
	 <?php include ('item_footer.php'); ?>
	 
</form>

<script>

 /* setup for save */
	 
 pricing = 'open_pricing_edit';
 itemID = <?= $item ['id'] ?>;
	 
</script>

<!-- save -->

<script src="/assets/js/item_submit.js"></script>



<form class="row g-1" id="<?= $item ['template']?>_edit">
	 
	 <input type="hidden" name="id" value="<?= $item ['id'] ?>">
	 <input type="hidden" name="item_price[class]" value="metric">
	 <input type="hidden" name="inv_item[package_quantity]" value="0">
	 <input type="hidden" name="inv_item[on_hand_req]" value="0">
	 <input type="hidden" name="inv_item[on_hand_count]" value="0">

	 <?php include ('item_header.php')  ?>
	 
	 <div class="row g-1">
		  <label for="price" class="col-sm-4 form-label">Price</label>
		  <div class="col-sm-8">
				<input type="text" dir="rtl" class="form-control currency-format" name="item_price[price]" value="<?= $item ['item_price'] ['price']?>" placeholder="<?= __ ('0.00') ?>">
		  </div>
	 </div>
	 
	 <div class="row g-1">
		  <label for="cost" class="col-sm-4 form-label">Cost</label>
		  <div class="col-sm-8">
				<input type="text" dir="rtl" class="form-control currency-format" name="item_price[cost]" value="<?= $item ['item_price'] ['cost']?>" placeholder="<?= __ ('0.00') ?>">
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
		  <label for="cost" class="col-sm-4 form-label"><?= __ ('Measure') ?></label>
		  <div class="col-sm-8">

				<?= $this->Form->select ('item_price[metric]',
												 $measures, 
												 ['value' => isset ($item ['item_price'] ['pricing'] ['metric']) ? $item ['item_price'] ['pricing'] ['metric'] : '',
												  'class' => 'form-select',
												  'label' => false]);
				?>
		  </div>
	 </div>
	 
	 <div class="row g-1">
		  <label for="cost" class="col-sm-4 form-label"><?= __ ('Decimal places') ?></label>
		  <div class="col-sm-8">
				<?= $this->Form->select ('item_price[decimal_places]',
												 $decimalPlaces, 
												 ['value' => isset ($item ['item_price'] ['pricing'] ['decimal_places']) ? $item ['item_price'] ['pricing'] ['decimal_places'] : 0,
												  'class' => 'form-select',
												  'label' => false]);
				?>
		  </div>
	 </div>
	 
	 <?php include ('item_footer.php'); ?>

</form>

<script>
 
 $(".currency-format").mask ("#####0.00", {reverse: true});
 $(".integer-format").mask ("#######0");
 
  /* setup for save */
 
 pricing = 'metric_pricing_edit';
 itemID = <?= $item ['id'] ?>;
 
</script>

<!-- save -->

<script src="/assets/js/item_submit.js"></script>

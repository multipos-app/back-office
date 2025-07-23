
<form class="row g-1" id="inventory_edit" name="inventory_edit" needs-validation">

	 <?php 

	 $this->debug ($editTitle);
	 $this->debug ($item);

	 ?>
	 <input type="hidden" name="item_id" value="<?= $item ['id'] ?>">
	 <input type="hidden" name="inv_item_id" value="<?= $item ['inv_item'] ['id'] ?>">
	 <input type="hidden" name="business_unit_id" value="<?= $item ['inv_item'] ['business_unit_id'] ?>">

	 <div class="row g-1 mt-3">
  		  <div class="col-sm-12">
				<?=
				$this->Form->select ('inv_item[supplier_id]',
											$suppliers,
											['name' => 'inv_item[supplier_id]',
											 'value' => $item ['inv_item'] ['supplier_id'],
											 'class' => 'form-select',
											 'label' => false])
				?>
		  </div>
	 </div>

	 <div class="row g-1 mt-3">
		  <label for="inv_item[package_size]" class="col-sm-4 form-label"><?= __ ('Package size') ?></label>
  		  <div class="col-sm-8">
				<input type="text"
								 dir="rtl"
								 class="form-control integer-format"
								 name="inv_item[package_quantity]"
								 value="<?= $item ['inv_item'] ['package_quantity'] ?>"
								 required>
		  </div>
	 </div>
	 
	 <div class="row g-1 mt-3">
		  <label for="inv_item[desired_on_hand]" class="col-sm-4 form-label"><?= __ ('Desired on hand') ?></label>
  		  <div class="col-sm-8">
				<input type="text"
								 dir="rtl"
								 class="form-control integer-format"
								 name="inv_item[on_hand_req]"
								 value="<?= $item ['inv_item'] ['on_hand_req'] ?>">
		  </div>
	 </div>
	 
	 <div class="row g-1 mt-3">
		  <label for="inv_item[on_hand_count]" class="col-sm-4 form-label"><?= __ ('On hand count') ?></label>
  		  <div class="col-sm-8">
				<input type="text"
								 dir="rtl"
								 class="form-control integer-format"
								 name="inv_item[on_hand_count]"
								 value="<?= $item ['inv_item'] ['on_hand_count'] ?>">
		  </div>
	 </div>

	 <div class="row g-1 mt-3">
		  <label for="inv_item[on_hand_quantity]" class="col-sm-4 form-label  font-weight-bold"><?= __ ('Inventory quantity') ?></label>
  		  <div class="col-sm-8 text-end px-3"><?= $item ['inv_item'] ['on_hand_quantity'] ?></div>
	 </div>

	 <div class="row g-3 mt-3">
		  <div class="col-sm-9 d-grid text-center"></div>
  		  <div class="col-sm-3 d-grid text-center">
				<button type="submit" class="btn btn-success"><?= __ ('Save') ?></button>
		  </div>
	 </div>

</form>

<script> 

 itemID = <?= $item ['id'] ?>;
 
 $('#modal_title').html ('<?= $editTitle ?>');
 
 $('#inventory_edit').submit (function (e) {
	  
	  e.preventDefault ();
	  let data = new FormData (this);
	  
	  $.ajax ({url: `/inventory/edit/${itemID}`,
				  type: 'POST',
				  data: data,
				  processData: false,
				  contentType: false,
				  success: function (data) {
						
						window.location = multipos.pathname;  // return to items list
				  }
	  });
 });

</script>

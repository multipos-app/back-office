
<form class="row g-1" id="<?= $item ['template']?>_edit">
 
	 <input type="hidden" name="id" value="<?= $item ['id'] ?>">
	 <input type="hidden" name="item_price[class]" value="variant">
	 <input type="hidden" name="item_price[price]" value="0">
	 <input type="hidden" name="item_price[cost]" value="0">
	 <input type="hidden" name="inv_item[supplier_id]" value="0">
	 <input type="hidden" name="inv_item[package_quantity]" value="0">
	 <input type="hidden" name="inv_item[on_hand_req]" value="0">
	 <input type="hidden" name="inv_item[on_hand_count]" value="0">

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

	 <div class="row g-1" id="variants_table"></div>
	 
	 <div class="row g-1">
		  <div class="col-sm-3 d-grid g-1 text-center">
				<button onclick="addVariant ()" class="btn btn-primary btn-block"><?= __ ('Add variant') ?></button>
		  </div>
	 </div>
	 
	 <?php include ('item_footer.php'); ?>
	 
</form>

<script>
 
 $(".currency-format").mask ("#####0.00", {reverse: true});
 $(".integer-format").mask ("#######0");

 variants = <?= json_encode ($item ['item_price'] ['pricing'] ['variants']) ?>;
 drawVariants ();
 
 function drawVariants () {
	  
	  let html = '<table class="table">' +
					 '<thead align="right">' +
					 '<th style="text-align:left;"><?= __ ('Description') ?></th>' +
					 '<th align="right"><?= __ ('Price') ?></th>' +
					 '<th align="right"><?= __ ('Cost') ?></th>' +
					 '<th align="right"></th>' +
					 '</thead>';
	  
	  $(variants).each (function (i, variant) {

			let desc = `item_price[pricing][variants][${i}][desc]`;
			let price = `item_price[pricing][variants][${i}][price]`;
			let cost = `item_price[pricing][variants][${i}][cost]`;
			
			html += `<tbody>` +
					  `<tr>` +
					  `<td align="left">` +
					  `<input type="input" id="desc_${i}" name="${desc}" class="form-control" required="required" value="${variant.desc}"/></td>` +
					  `<td align="right">` +
					  `<input type="input" id="price_${i}" name="${price}" id="rate" class="form-control currency-format" required="required" dir="rtl" value="${variant.price}"/></td>` +
					  `<td align="right">` +
					  `<input type="input" id="cost_${i}" name="${cost}" id="alt_rate" class="form-control currency-format" required="required" dir="rtl" value="${variant.cost}"/></td>`;

			if (variant.new_variant) {
				 
				 html += `<td>` +
							`<i onclick="updateVariant (${i})" class="bx bxs-plus-circle icon-lg"></i>` +
							`</td>` +
							`</tr>`;
			}
			else {
				 
				 html += `<td>` +
							`<i onclick="delVariant (${i})" class="bx bxs-minus-circle icon-lg"></i>` +
							`</td>` +
							`</tr>`;
			}
	  });

	  html += '</tbody>' +
				 '</table>';
	  
	  $('#variants_table').html (html);
	  
	  $(".currency-format").mask ("#####0.00", {reverse: true});
	  $(".integer-format").mask ("#######0");
 }
 
 function addVariant () {
	  
	  $(variants).each (function (i, variant) {

			variant.new_variant = false;
	  });
	  
	  variants.push ({desc: "", price: "", cost: "", new_variant: true});
	  drawVariants ();
 }
 
 function updateVariant (i) {

	  let desc = $(`#desc_${i}`).val ().toUpperCase ();
	  
	  variants [i] = {desc: desc,
							price: $(`#price_${i}`).val (),
							cost: $(`#cost_${i}`).val (),
							new_variant: false};

	  drawVariants ();
 }

 function delVariant (index) {
	  
	  variants.splice (index, 1);
 	  drawVariants ()
 }
 
 /* setup for save */
 
 pricing = 'variant_pricing_edit';
 itemID = <?= $item ['id'] ?>;

</script>

<!-- save -->

<script src="/assets/js/item_submit.js"></script>

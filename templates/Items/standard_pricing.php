
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
	 
	 <div class="row g-1">
		  <label for="price" class="col-sm-4 form-label"><?= __ ('Price') ?></label>
		  <div class="col-sm-8">
				<input type="text" dir="rtl" class="form-control currency-format" name="item_price[price]" value="<?= $item ['item_price'] ['price']?>" placeholder="0.00">
		  </div>
	 </div>
	 
	 <div class="row g-1">
		  <label for="cost" class="col-sm-4 form-label"><?= __ ('Cost') ?></label>
		  <div class="col-sm-8">
				<input type="text" dir="rtl" class="form-control currency-format" name="item_price[cost]" value="<?= $item ['item_price'] ['cost']?>" placeholder="0.00">
		  </div>
	 </div>
	 
	 <div class="row g-1 mt-3">
		  <div class="col-sm-12 text-center">
				<h5><?= __ ('Linked items') ?></h5>
		  </div>
	 </div>
	 
	 <div class="row g-1">
		  		  
		  <div class="col-sm-8">
				<input type="text" class="form-control" name="link_desc" id="link_desc" value="" placeholder="<?= __ ('Linked item') ?>">
		  </div>
		  
		  <div class="col-sm-3">
				<?=
				$this->Form->select ('link_type',
											$linkTypes,
											['id' => 'link_type',
											 'class' => 'form-select',
											 'label' => false])
				?>
		  </div>

		  
		  <div class="col-sm-1 text-end">
				<i onclick="addLink ()" class="bx bxs-plus-circle icon-lg"></i>
		  </div>

		  <div id="linked_items"></div>

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
 
 $(".currency-format").mask ("#####0.00", {reverse: true});
 $(".integer-format").mask ("#######0");

 
 item = <?= json_encode ($item) ?>;
 linkItem = null;
 itemLinks = <?= json_encode ($item ['item_links']) ?>;
 links ();
 	  
 $('#link_desc').typeahead ({
	  
	  source: function (query, result) {
			
			$.ajax ({
				 url: "/search/items/sku_and_desc/" + query + "/sku_and_desc",
				 type: "GET",
				 success: function (data) {
					  
					  data = JSON.parse (data);
					  result ($.map (data, function (item) {
							
							return item;
					  }));
				 }
			});
	  },
	  updater: function (link) {

			if (item.id == link.id) {

				 alert ('Linking an item to itself is a bad idea...');
				 return;
			}

			$('#link_id').val (link.id);
			linkItem = link;
			return link.name;
	  }
 });

 function links () {
	  
	  console.log (itemLinks);

	  let html = '';
	  let i = 0;
	  
	  $(itemLinks).each (function (i, link) {
			
			html += `<input type="hidden" name="item_links[${i}][item_link_id]" value="${link.id}">` +
					  `<input type="hidden" name="item_links[${i}][link_type]" value="${$('#link_type').val ()}">` +
					  `<div class="row g-1 mt-1 ps-3">` +
					  `<div class="col-sm-11">${link.name}</div>` + 
					  `<div class="col-sm-1 text-end"><i onclick="unLink (${i})" class="bx bxs-minus-circle icon-lg"></i></div>` + 
					  `</div>`;
			
			i ++;
	  });
	  
	  $('#linked_items').html (html);
 }
 
function addLink () {

	  itemLinks.push (linkItem);	  
	  $('#link_desc').val ("");
	  links ();
}

 function unLink (index) {

	  itemLinks.splice (index, 1);
	  links ();
 }

 /* setup for save */
 
 pricing = 'standard_pricing_edit';
 itemID = <?= $item ['id'] ?>;

</script>

<!-- save -->

<script src="/assets/js/item_submit.js"></script>


<form class="row g-1" id="<?= $item ['template']?>_edit" name="<?= $item ['template']?>_edit" needs-validation">

	 <input type="hidden" name="id" value="<?= $item ['id'] ?>">
	 <input type="hidden" name="item_price[class]" value="standard">
	 <input type="hidden" name="inv_item[package_quantity]" value="0">
	 <input type="hidden" name="inv_item[on_hand_req]" value="0">
	 <input type="hidden" name="inv_item[on_hand_count]" value="0">

	 <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="item_tab" role="tablist">

		  <li class="nav-item flex-fill" role="presentation">
				<button class="nav-link active w-100"
									id="general_tab"
									data-bs-toggle="tab"
									data-bs-target="#general"
									type="button"
									role="tab"
									aria-controls="general"
									aria-selected="false"><?= __ ('General') ?></button>
		  </li>

		  <li class="nav-item flex-fill" role="presentation">
				<button class="nav-link w-100"
									id="inventory_tab"
									data-bs-toggle="tab"
									data-bs-target="#inventory"
									type="button"
									role="tab"
									aria-controls="inventory"
									aria-selected="true"><?= __ ('Inventory') ?></button>
		  </li>
		  
		  <li class="nav-item flex-fill" role="presentation">
				<button class="nav-link w-100"
									id="links_tab"
									data-bs-toggle="tab"
									data-bs-target="#links"
									type="button"
									role="tab"
									aria-controls="links"
									aria-selected="true"><?= __ ('Item links') ?></button>
		  </li>
		  
	 </ul>

	 <div class="tab-content pt-2" id="item_tab_content">
		  
		  <div class="tab-pane fade active show"
				 id="general"
				 role="tabpanel"
				 aria-labelledby="general_tab">
				
				<?php include ('item_header.php')  ?>
				
				<div class="row g-1 mt-3">
					 <label for="price" class="col-sm-4 form-label"><?= __ ('Price') ?></label>
					 <div class="col-sm-8">
						  <input type="text" dir="rtl" class="form-control currency-format" name="item_price[price]" value="<?= $item ['item_price'] ['price']?>" required placeholder="0.00">
					 </div>
				</div>
				
				<div class="row g-1 mt-3">
					 <label for="cost" class="col-sm-4 form-label"><?= __ ('Cost') ?></label>
					 <div class="col-sm-8">
						  <input type="text" dir="rtl" class="form-control currency-format" name="item_price[cost]" value="<?= $item ['item_price'] ['cost']?>" placeholder="0.00">
					 </div>
				</div>
		  </div> <!-- end general tab -->
		  
		  <div class="tab-pane fade"
				 id="inventory"
				 role="tabpanel"
				 aria-labelledby="inventory_tab">
				
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
		  </div> <!-- end inventory tab -->
		  
		  <div class="tab-pane fade"
				 id="links"
				 role="tabpanel"
				 aria-labelledby="links_tab">
				
				<div class="row g-1 mt-3">
		  			 
					 <div class="col-sm-8">
						  <input type="text"
											class="form-control"
											name="link_desc"
											id="link_desc"
											value=""
											placeholder="<?= __ ('Linked item') ?>">
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
		  </div> <!-- end links tab -->
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
					  `<div class="row g-1 mt-3 mt-1 ps-3">` +
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

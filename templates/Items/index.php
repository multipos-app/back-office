

<style>

 .search-grid {
	  
	  display: grid;
	  width: 100%;
	  grid-template-columns: 1fr .1fr 1fr 1fr 1fr;
	  margin-top: 25px;
	  margin-bottom: 25px;
 }
 
</style>

<div class="row search-grid">
	 
	 <div>
		  <input type="text" class="form-control" id="item_search" value="" placeholder="<?= __ ('Search, SKU, description')?>">
	 </div>
	 
	 <div>
		  <i class="bx bx-search-alt icon-lg" onclick="search ()"></i>
	 </div>
	 
	 <div>
		  <?=
		  $this->Form->select ('department_search',
									  $departments,
									  ['id' => 'department_search',
										'value' => '',
										'class' => 'form-select',
										'label' => false,
										'onchange' => 'departmentSearch ()'])
		  ?>
	 </div>
	 
	 <div>
		  <?=
		  $this->Form->select ('pricing_search',
									  [null => __ ('Pricing search'), 
										'standard' => __ ('Standard'), 
										'variant' => __ ('Variant'), 
										'open' => __ ('Open'), 
										'metric' => __ ('Metric')],
									  ['id' => 'pricing_search',
										'value' => '',
										'class' => 'form-select',
										'label' => false,
										'onchange' => 'pricingSearch ()'])
		  ?>
	 </div>
	 
	 <div>
		  <?=
		  $this->Form->select ('add_item',
									  $pricingOptions,
									  ['id' => 'add_item',
										'data-bs-toggle' => 'modal',
										'data-bs-target' => '#item_modal',
										'value' => '',
										'class' => 'form-select',
										'label' => false,
										'required' => 'required'])
		  ?>
	 </div>
</div>

<table class="table table-hover table_sm">
	 <thead>
		  <tr>

				<th><?= $this->Paginator->sort ('sku', __ ('SKU')) ?></th>
				<th><?= $this->Paginator->sort ('item_desc', __ ('Item description')) ?></th>
				<th><?= __ ('Supplier'); ?></th>
				<th style="text-align: end;"><?= __ ('Price'); ?></th>
				<th style="text-align: end;"><?= __ ('Cost'); ?></th>
				<th style="text-align: end;"><?= __ ('Inventory'); ?></th>
				<th style="text-align: end;"><?= __ ('Disabled'); ?></th>
				<th></th>
		  </tr>
	 </thead>
	 
	 <?php
	 
	 foreach ($items as $item) {
		  
		  $supplierID = 0;

		  if (!isset ($item ['inv_items'] [0])) {

				// set up a dummy
				
				$item ['inv_items'] [0] = ['supplier_id' => 0,
													'on_hand_req' => 0,
													'package_quantity' => 0,
													'on_hand_count' => 0];
		  }

		  $template = '';
		  if (isset ($item ['item_prices'] [0])) {

				$template = $item ['item_prices'] [0] ['class'];
		  }
		  
	 ?>
	 <tbody>

		  <script>
			item = <?= json_encode ($item) ?>;			
		  </script>
		  
		  <tr role="button" data-bs-toggle="modal" data-bs-target="#item_modal" onclick="edit (<?= $item ['id'] ?>,'<?= $template ?>')">
				
				<td><?= $item ['sku'] ?></td>
				<td><?= $item ['item_desc'] ?></td>

				<?php

				if (isset ($item ['item_prices'] [0])) {

					 $pricing = ["class" => "standard",
									 "price" => $item ['item_prices'] [0] ['price'],
									 "amount"=> $item ['item_prices'] [0] ['price'],
									 "cost" => 0];

					 if (isset ($item ['item_prices'] [0] ['pricing'])) {
						  
						  $pricing = json_decode ($item ['item_prices'] [0] ['pricing'], true);
					 }

					 $price = 0;
					 $cost = 0;
					 $profit = 0;
					 $itemDesc = $item ['item_desc'];
					 
					 switch ($item ['item_prices'] [0] ['class']) {

						  case 'standard':
						  case 'metric':
						  case 'group':

								$cost = floatval ($item ['item_prices'] [0] ['cost']);
								
								if (isset ($item ['item_prices'] [0] ['price'])) {

									 $price = floatval ($item ['item_prices'] [0] ['price']);
								}
								else if (isset ($item ['item_prices'] [0] ['amount'])) {  // legacy

									 $price = floatval ($item ['item_prices'] [0] ['amount']);
								}
								

								if (($price > 0) && ($cost > 0)) {
									 
									 $profit = 100 - (($cost / $price) * 100.0);
								}
								else if (($price > 0) && ($item ['item_prices'] [0] ['cost'] == 0)) {
									 
									 $profit = 100.0;
								}

								$price = $this->moneyFormat ($price);
								$cost = $this->moneyFormat ($cost);
								$onHandCount = $item ['inv_items'] [0] ['on_hand_count'] < 0 ? 0 : $item ['inv_items'] [0] ['on_hand_count'];
								$disabled = $item ['enabled'] ? '' : '<i class="bx bxs-error-circle icon-warn"></i>'; 

								$supplierID = 0;
								if ($item ['inv_items'] [0] ['supplier_id'] > 0) {

									 $supplierID = $item ['inv_items'] [0] ['supplier_id'];
								}
								
								$html =
									 '<td>' . $suppliers [$supplierID] . '</td>' .
									 '<td style="text-align: end;">'.$price.'</td>' .
									 '<td style="text-align: end;">' . $cost . '</td>' .
									 '<td style="text-align: end;">' . $onHandCount . '</td>' .
									 '<td style="text-align: end;">' . $disabled . '</td>';

								echo $html;
								break;
								
						  case 'size':
								
								$html =
									 '<td></td>' . 
									 '<td style="text-align: end;"></td>' . 
									 '<td style="text-align: end;"></td>' . 
									 '<td style="text-align: end;"></td>';

								echo $html;
								break;
								
						  default:
								
								echo '<td></td>';
								echo '<td style="text-align: end;"></td>';
								echo '<td style="text-align: end;"></td>';
								echo '<td style="text-align: end;"></td>';
								break;
					 }
				}
				else {
					 echo '<td></td>';
					 echo '<td style="text-align: end;">0.00</td>';
					 echo '<td style="text-align: end;">0.00</td>';
				}
				?>
				
	 <?php
	 }
	 ?>
		  </tr>
	 </tbody>
</table>

<div class="row g-1 mt-3">
	 <div class="col-12 text-center">
		  <div id="pages" class="pagination"></div>
		  <nav class="pagination">

				<nav class="pagination">
					 
 					 <ul class="pagination">
						  <?= $this->Paginator->numbers () ?>
					 </ul>
				</nav>
 		  </nav>
	 </div>
</div>

<div class="modal fade" id="item_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h5 id="modal_title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modal_content" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script>
 
 function edit (id, template) {
	  
	  $.ajax ({
         url: '/items/edit/' + id + '/' + template,
         type: 'GET',
         success: function (data) {

				 data = JSON.parse (data);				 
				 $('#modal_content').html (data.html);
         }
     });
 }
 
 function search () {
	  
	  window.location = '/items/index/item_desc/' + $('#item_search').val ()
 }
 
 $('#add_item').change (function () {

	  console.log (`pricing option... ${$('#add_item').val ()}`);
	  
	  switch ($('#add_item').val ()) {

			case 'standard':
			case 'variant':
			case 'open':
			case 'metric':
				 
				 $.ajax ({
					  url: '/items/edit/0/' + $('#add_item').val (),
					  type: "GET",
					  success: function (data) {
							
							data = JSON.parse (data);
							$('#modal_content').html (data.html);
					  }
				 });
	  }
 });
 
 $('#item_search').typeahead ({
	  
     source: function (query, result) {
			
			$.ajax ({
             url: "/search/items/sku_and_desc/" + query,
             type: "GET",
             success: function (data) {

					  data = JSON.parse (data);
					  
					  result ($.map (data, function (item) {
							
							return item
                 }));
             }
			});
     },
	  updater: function (item) {
			
			window.location = '/items/index/id/' + item.id;
	  }
 });

 function departmentSearch () {
	  
	  window.location = '/items/index/department_id/' + $('#department_search').val ();
 }
 
function pricingSearch () {
	  
	  window.location = '/items/index/pricing/' + $('#pricing_search').val ();
 }
 
</script>

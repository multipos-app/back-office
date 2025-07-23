


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
</div>	 

<table class="table table-hover table_sm">
	 <thead>
		  <tr>

				<th><?= $this->Paginator->sort ('sku', __ ('SKU')) ?></th>
				<th><?= $this->Paginator->sort ('item_desc', __ ('Item description')) ?></th>
				<th><?= __ ('Supplier'); ?></th>
				<th><?= __ ('Last count'); ?></th>
				<th style="text-align: end;"><?= __ ('On hand'); ?></th>
				<th style="text-align: end;"><?= __ ('Count'); ?></th>
				<th></th>
		  </tr>
	 </thead>
	 
	 <?php
	 
	 foreach ($items as $item) {
		  
		  $supplierID = 0;
	 ?>

		  <tr role="button" data-bs-toggle="modal" data-bs-target="#inventory_modal" onclick="edit (<?= $item ['id'] ?>)">
				<td><?= $item ['sku'] ?></td>
				<td><?= $item ['item_desc'] ?></td>
				<td></td>
				<td><?= $item ['inv_items'] [0] ['update_time'] ?></td>
				<td align="right"><?= $item ['inv_items'] [0] ['on_hand_quantity'] ?></td>
				<td align="right"><?= $item ['inv_items'] [0] ['on_hand_count'] ?></td>
		  </tr>
	 <?php }
	 ?>
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

<div class="modal fade" id="inventory_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
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

 function edit (id) {
	  
	  $.ajax ({
         url: '/inventory/edit/' + id,
         type: 'GET',
         success: function (data) {

				 data = JSON.parse (data);

				 console.log (data);
				 
				 $('#modal_content').html (data.html);
         }
     });
 }

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
			
			window.location = '/inventory/index/id/' + item.id;
	  }
 });

 function departmentSearch () {
	  
	  window.location = '/inventory/index/department_id/' + $('#department_search').val ();
	  
 }
</script>

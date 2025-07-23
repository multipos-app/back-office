
<div class="row g-1 mt-3 mb-3">

	 <div class="col-3 mt-3">
		  <input type="text" class="form-control" id="customer_search" value="" placeholder="<?= __ ('Enter name, phone, email')?>">
	 </div>
	 
	 <div class="col-1 mt-3 text-center">
		  <i class="bx bx-search-alt icon-lg" onclick="search ()"></i>
	 </div>

	 <div class="col-6"></div>
	 
	 <div class="col-2 mt-3 d-grid text-center">
		  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customer_modal" onclick="edit (0)"><?= __ ('Add customer') ?></button>
	 </div>
</div>

<table class="table table-hover">
	 <thead>
		  <tr>
				<th><?= $this->Paginator->sort ('email', __ ('Email')) ?></th>
				<th><?= $this->Paginator->sort ('lname', __ ('Name')) ?></th>
				<th><?= __ ('Phone') ?></th>
				<th><?= __ ('Last visit') ?></th>
				<th><?= __ ('Total visits') ?></th>
				<th><?= __ ('Total sales') ?></th>
				<th><?= __ ('Avg sale') ?></th>
				<th><?= __ ('Points') ?></th>
				<th><?= __ ('Transactions') ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>

	 <?php
	 
	 foreach  ($customers as $customer) {

	 ?>
		  <tr role="button" data-bs-toggle="modal" data-bs-target="#customer_modal" onclick="edit (<?= $customer ['id'] ?>)">
				
				<td><?= $customer ['email'] ?></td>
				<td><?= $customer ['fname'] . ' ' . $customer ['lname'] ?></td>
				<td><span class="phone-format"><?= $customer ['phone'] ?></span></td>
				<td><?= $customer ['last_update'] ?></td>
				<td align="right"><?= $customer ['total_visits'] ?></td>
				<td align="right"><?= $customer ['total_sales'] ?></td>
				<td align="right"><?php

					 if ($customer ['total_visits'] > 0) {
						  echo $customer ['total_sales'] / $customer ['total_visits'];
					 }
					 else {
						  echo '0.00';
					 }
					 ?>
				</td>
				<td align="right"><?= $customer ['loyalty_points'] ?></td>

				<td align="right">
					 <a href="/tickets/index/customer_id/<?= $customer ['id'] ?>" target="_blank?"<i class="bx bx-link-external icon-lg"></i></a>
				</td>
		  </tr>

	 <?php
	 }
	 ?>
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

<div class="modal fade" id="customer_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="customer_desc" class="modal-title"><?= __ ('Customer edit') ?></h5>
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
         url: "/customers/edit/" + id,
         type: "GET",
         success: function (data) {

				 data = JSON.parse (data);
				 
				 $('#modal_content').html (data.html)
            }
        });
 }
 
 function search () {
	  
	  window.location = '/customers/index/search/' + $('#customer_search').val ()
 }

 $('#customer_search').typeahead ({
	  
     source: function (query, result) {

			console.log (`cust search... ${query}`);
			
			$.ajax ({
             url: "/search/customers/" + query,
             type: "GET",
             success: function (data) {

					  data = JSON.parse (data);
					  
					  result ($.map (data, function (customer) {
							
							return customer
                 }));
             }
			});
     },
	  updater: function (customer) {
			
			window.location = '/customers/index/id/' + customer.id;
	  }
 });
 
 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 $(".postal-code-format").mask ("<?= __ ('postal_code_format') ?>", {});

</script>

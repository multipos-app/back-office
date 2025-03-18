
<table class="table table-hover">
	 <thead>
		  <tr>
				<th></th>
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
				<td id="tag_<?= $customer ['id'] ?>"></td>	
				<td><?= $customer ['email'] ?></td>
				<td><?= $customer ['fname'] . ' ' . $customer ['lname'] ?></td>
				<td><?= $customer ['phone'] ?></td>
				<td><?= $customer ['last_update'] ?></td>
				<td><?= $customer ['total_visits'] ?></td>
				<td><?= $customer ['total_sales'] ?></td>
				<td><?php

					 if ($customer ['total_visits'] > 0) {
						  echo $customer ['total_sales'] / $customer ['total_visits'];
					 }
					 else {
						  echo '0.00';
					 }
					 ?>
				</td>
				<td><?= $customer ['loyalty_points'] ?></td>

				<td align="center">
					 <i class="bx bx-link-external icon-lg" data-bs-toggle="modal" data-bs-target="#modal_search"></i>
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
                <h5 id="item_desc" class="modal-title"><?= __ ('Customer edit') ?></h5>
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
 
 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});
 $(".postal-code-format").mask ("<?= __ ('postal_code_format') ?>", {});

</script>

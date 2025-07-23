
<div class="row g-1 mt-3 mb-3">
	 <div class="col-10"></div>
	 <div class="col-2 d-grid text-center">
		  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#department_modal" onclick="edit (0)"><?= __ ('Add department') ?></button>
	 </div>
</div>

<table class="table table-hover">
	 <thead>
		  <tr>
				<th><?= $this->Paginator->sort ('department_desc', __ ('Department')) ?></th>
				<th><?= __ ('Type'); ?></th>
				<th class="text-center"><?= __ ('Negative/Deposits'); ?></th>
				<th class="text-center"><?= __ ('Inventory'); ?></th>
				<th></th>
		  </tr>
	 </thead>
	 <tbody>
		  <?php

		  foreach ($departments as $department) {
				
				$departmentType = $departmentTypes [$department ['department_type']];
				$isNegative = $department ['is_negative'] ? __('Yes') : __ ('No');
				$inventory = $department ['inventory'] ? __('Yes') : __ ('No');
				$action = '';
				$locked = '';
				if ($department ['locked']) {
					 
					 $locked = '<i class="bx bxs-lock icon-lg"></i>';
				}
		  ?>
		  <tr role="button" data-bs-toggle="modal" data-bs-target="#department_modal" onclick="edit (<?= $department ['id'] ?>)">
				<td><?= $department ['department_desc'] ?></td>
				<td><?= $departmentType ?></td>
				<td align="center"><?= $isNegative ?></td?>
				<td align="center"><?= $inventory ?></td?>
				<td align="right"><?= $locked ?></td>
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

<div class="modal fade" id="department_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="item_desc" class="modal-title"><?= __ ('Department edit') ?></h5>
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
         url: "/departments/edit/" + id,
         type: "GET",
         success: function (data) {

				 data = JSON.parse (data);
				 
				 $('#modal_content').html (data.html)
            }
        });
	 }

</script>

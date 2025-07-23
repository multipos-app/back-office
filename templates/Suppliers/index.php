

<div class="row g-1 mt-3 mb-3">
	 <div class="col-10"></div>
	 <div class="col-2 d-grid text-center">
		  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supplier_modal" onclick="edit (0)"><?= __ ('Add supplier') ?></button>
	 </div>
</div>

<table class="table table-hover">
	 <thead>
		  <tr>
				<th></th>
				<th><?= __ ('Supplier name') ?></th>
				<th><?= __ ('Contact') ?></th>
				<th><?= __ ('Phone') ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>

		  <?php

		  foreach  ($suppliers as $supplier) {
		  ?>
				
				<tr role="button" data-bs-toggle="modal" data-bs-target="#supplier_modal" onclick="edit (<?= $supplier ['id'] ?>)">				
					 <td></td>
					 <td><?= $supplier ['supplier_name'] ?></td>
					 <td><?= $supplier ['contact'] ?></td>
					 <td><span class="phone-format"><?php echo $supplier ['phone_1']  ?></span></td>
				</tr>
		  <?php
		  }
		  ?>
	 </tbody>
</table>

<div class="modal fade" id="supplier_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="item_desc" class="modal-title"><?= __ ('Supplier edit') ?></h5>
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
         url: "/suppliers/edit/" + id,
         type: "GET",
         success: function (data) {

				 data = JSON.parse (data);
				 
				 $('#modal_content').html (data.html)
            }
        });
 }
 
 $(".phone-format").mask ("<?= __ ('phone_format') ?>", {});

</script>

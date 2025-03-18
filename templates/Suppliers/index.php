
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
					 <td><?= $supplier ['contact1'] ?></td>
					 <td><?php echo $supplier ['phone1']  ?></td>
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

</script>

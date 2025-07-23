

<div class="row g-1 mt-3 mb-3">
	 <div class="col-10"></div>
	 <div class="col-2 d-grid text-center">
		  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bu_modal" onclick="edit (0)"><?= __ ('Add location') ?></button>
	 </div>
</div>

<table class="table table-hover">
	 <thead>
		  <tr>
				
				<th><?= __ ('Name'); ?></th>
				<th><?= __ ('Address'); ?></th>
				<th><?= __ ('City'); ?></th>
				<th><?= __ ('Email'); ?></th>
				<th><?= __ ('Phone'); ?></th>
				<th><?= __ ('Type'); ?></th>
		  </tr>
	 </thead>
	 <tbody>
		  		  
		  <?php

		  foreach ($businessUnits as $businessUnit) {
		  ?>
				<tr role="button" data-bs-toggle="modal" data-bs-target="#bu_modal" onclick="edit (<?= $businessUnit ['id'] ?>)">
					 <td><?= $businessUnit ['business_name'] ?></td>
					 <td><?= $businessUnit ['addr_1'] ?></td>
					 <td><?= $businessUnit ['city'] ?></td?>
					 <td><?= $businessUnit ['email'] ?></td>
					 <td><?= $businessUnit ['phone_1'] ?></td>
					 <td><?= $businessUnit ['business_type'] ?></td>
				</tr>
		  <?php } ?>


	 </tbody>
</table>

<div class="modal fade" id="bu_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="item_desc" class="modal-title"><?= __ ('Account edit') ?> </h5>
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
         url: "/business-units/edit/" + id,
         type: "GET",
         success: function (data) {

				 data = JSON.parse (data);

				 console.log (data);
				 
				 $('#modal_content').html (data.html)
            }
        });
	 }

</script>

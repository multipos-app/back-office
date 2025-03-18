<style>

 .modal-content {

	  width: 900px !important;
 }
 
</style>

<div class="row g-1 mt-3 mb-3">
	 <button class="btn btn-success col-sm-2" data-bs-toggle="modal" data-bs-target="#tax_modal" onclick="edit (0)"><?= __ ('Add tax group') ?></button>
</div>

<table class="table table-hover g-1 m-3">
	 <thead>
		  <tr>
				<th><?= __ ('Description') ?></th>
				<th><?= __ ('Rate', true) ?></th>
				<th><?= __ ('Alt rate', true) ?></th>
				<th><?= __ ('Fixed rate', true) ?></th>
				<th><?= __ ('Fixed alt rate', true) ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>
			  
	 <?php
	 
	 foreach  ($taxGroups as $taxGroup) {
	 	  
		  $rate = 0;
		  $altRate = 0;
		  $fixedRate = 0;
		  $fixedAltRate = 0;
		  		  
		  foreach ($taxGroup ['taxes'] as $tax) {  // sum the rates

				switch ($tax ['type']) {

					 case 'percent':
						  
						  $rate += $tax ['rate'];
						  $altRate += $tax ['alt_rate'];
						  break;

					 case 'fixed':
						  
						  $fixedRate += $tax ['rate'];
						  $fixedAltRate += $tax ['alt_rate'];
						  break;
				}
		  } 
		  
	 ?>
	 
		  <tr role="button" data-bs-toggle="modal" data-bs-target="#tax_modal" onclick="edit (<?= $taxGroup ['id'] ?>)">
				<td><?=$taxGroup ['short_desc'] ?></td>
				<td><?= $rate.'%' ?></td>
				<td><?= $altRate.'%' ?></td>
				<td><?= $fixedRate ?></td>
				<td><?= $fixedAltRate ?></td>
		  </tr>
		  
	 <?php
	 }
	 ?>
	 </tbody>
</table>

<div class="modal fade" id="tax_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="item_desc" class="modal-title"><?= __ ('Tax group edit') ?></h5>
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
         url: "/tax-groups/edit/" + id,
         type: "GET",
         success: function (data) {

				 data = JSON.parse (data);
				 
				 $('#modal_content').html (data.html)
            }
        });
	 }

</script>

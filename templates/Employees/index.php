

<div class="row g-1 mt-3 mb-3">
	 <div class="col-10"></div>
	 <div class="col-2 d-grid text-center">
		  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#employee_modal" onclick="edit (0)"><?= __ ('Add employee') ?></button>
	 </div>
</div>

<table class="table table-hover">
	 <thead>
		  <tr>
				<th><?= __ ('Name') ?></th>
				<th><?= __ ('Employee Number') ?></th>
				<th><?= __ ('Profile') ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>

	 <?php 
	 
	 foreach ($employees as $employee) {
		  

		  if ($employee ['profile_id']) {

				$profileDesc = $profiles [$employee ['profile_id']] ['profile_desc'];
		  }
		  
	 ?>
		  
		  <tr role="button" data-bs-toggle="modal" data-bs-target="#employee_modal" onclick="edit (<?= $employee ['id'] ?>)">
				<td><?= $employee ['fname'].' '.$employee ['lname'];?></td>
				<td><?= $employee ['username'] ?></td>
				<td><?= $profileDesc ?></td>
				
		  </tr>
		  
	 <?php
	 }
	 ?>
	 </tbody>

</table>

<div class="modal fade" id="employee_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="employee_desc" class="modal-title"><?= __ ('Employee edit') ?></h5>
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
         url: "/employees/edit/" + id,
         type: "GET",
         success: function (data) {

				 data = JSON.parse (data);
				 
				 $('#modal_content').html (data.html)
            }
        });
	 }

</script>

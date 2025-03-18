
<div class="row g-1 mt-3 mb-3">
	 <div class="col-10"></div>
	 <div class="col-2 d-grid text-center">
		  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#profile_modal" onclick="edit (0)"><?= __ ('Add profile') ?></button>
	 </div>
</div>

<table class="table table-hover">
	 <thead>
		  <tr>
				<th><?= __ ('Description') ?></th>
				<th><?= __ ('Employees') ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>

	 <?php 
	 foreach ($profiles as $profile) {
		  
	 ?>
		  <tr role="button" onclick="edit (<?= $profile ['id'] ?>)">
				<td><?= $profile ['profile_desc'] ?></td>
				<td></td>
		  </tr>
		  
	 <?php
	 }
	 ?>
	 </tbody>
</table>

<script>
 
 function edit (id) {
	  
	  window.location = "/profiles/edit/" + id;
 }

</script>

<div class="row mt-3 mb-3">
	 
	 <div class="col-sm-10 text-left">
		  <h3><?=  __ ('VideoRegister users')  ?></h3>
	 </div>
	 
	 <div class="d-grid col-sm-2 text-start">
		  <button type="button"
					 id="user_add"
					 class="btn btn-primary"
					 data-bs-toggle="modal"
					 data-bs-target="#merchant_users_modal"
					 onclick="edit (0, <?= $merchantID ?>)"><?= __ ('Add User') ?>
		  </button>
	 </div>
</div>

<table class="table table-hover mt-3">
	 <thead>
		  <tr>
				<td class="grid-cell grid-cell-left grid-cell-separator "><?= __ ('User Name') ?></td>
				<td class="grid-cell grid-cell-left grid-cell-separator "><?= __ ('First Name') ?></td>
				<td class="grid-cell grid-cell-left grid-cell-separator"><?= __ ('Last Name') ?></td>
				<td class="grid-cell grid-cell-center grid-cell-separator "><?= __ ('Profile') ?></td>
		  </tr>
		  
		  <?php foreach ($users as $user ) {

				if ($user ['role'] == 'vradmin') {
					 
					 continue;
				}
		  ?>
	 </thead>
	 <tbody>
		  <tr role="button" data-bs-toggle="modal" data-bs-target="#merchant_users_modal" onclick="edit (<?= $user ['id'] ?>, <?= $merchantID ?>)">
				<td class="grid-cell grid-cell-left"><?= $user ['uname'] ?></td>
				<td class="grid-cell grid-cell-left"><?= $user ['fname'] ?></td>
				<td class="grid-cell grid-cell-left"><?= $user ['lname'] ?></td>
				<td class="grid-cell grid-cell-center"><?= $user ['role'] ?></td>
		  </tr>
				<?php  } ?>

	 </tbody>
</table>

<div class="modal fade" id="merchant_users_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="item_desc" class="modal-title"><?= __ ('Edit user') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modal_content" class="modal-body">
            </div>
        </div>
    </div>
</div>

<script>
 
 function edit (id, merchantID) {
	  
	  let url = '/merchant-users/edit/' + id + '/' + merchantID;

	  console.log (url);
	  
	  $.ajax ({
         url: url,
         type: "GET",
         success: function (data) {
				 
				 data = JSON.parse (data);
				 
				 $('#modal_content').html (data.html)
         }
     });
 }
 
</script>



<div class="row g-1 mt-3 mb-3">
	 <div class="col-10"></div>
	 <div class="col-2 d-grid text-center">
		  <button id="templates" class="btn btn-primary"
					 data-bs-toggle="modal" data-bs-target="#pos_config_modal"
					 onclick="modal (0, 'templates')"><?= __ ('Add POS configuration') ?></button>
	 </div>
</div>

<table class="table table-hover">
	 <thead align="center">
		  <tr>
				<th style="text-align:left"><?= __ ('Description') ?></th>
				<th><?= __ ('Menus') ?></th>
				<th><?= __ ('Settings') ?></th>
				<th><?= __ ('Download') ?></th>
				<th><?= __ ('Upload') ?></th>
				<th><?= __ ('Clone') ?></th>
				<th><?= __ ('Delete') ?></th>
		  </tr>
	 </thead>
	 
	 <tbody>

		  <?php

		  $i = 0;
		  foreach ($configs as $config) {

		  ?>

				<tr>
					 <td align="left">
						  <?= $config ['config_desc'] ?>
					 </td>
					 <td align="center">
						  <a href="/menus/menu/<?= $config ['id'] ?>">
								<i class="bx bx-menu icon-lg"></i>
						  </a>
					 </td>
					 </td>
					 <td align="center">
						  <div class="icon">
								<a data-bs-toggle="modal" data-bs-target="#pos_config_modal" onclick="modal (<?= $config ['id']?>, 'settings')"><i class="bx bxs-wrench icon-lg"></i></a>
						  </div>
					 </td>
					 <td align="center">
						  <div class="icon">
								<a href="/pos-configs/download/<?= $config ['id'] ?>"><i class="bx bx-cloud-download icon-lg"></i></a>
						  </div>
					 </td>
					 <td align="center">
						  <div class="icon" data-bs-toggle="modal" data-bs-target="#pos_config_modal">
								<i class="bx bx-cloud-upload icon-lg" onclick="modal (<?= $config ['id']?>, 'upload')"></i>
						  </div>
					 </td>
					 <td align="center">
						  <div class="icon">
						  <div class="icon" data-bs-toggle="modal" data-bs-target="#pos_config_modal">
								<i class="bx bx-copy icon-lg" onclick="modal (<?= $config ['id']?>, 'clone')"></i>
						  </div>
					 </td>
					 <td align="center">
						  <div class="icon">
								<i class="bx bxs-trash icon-lg" onclick="deleteConfig (<?= $config ['id']?>, '<?= $config ['config_desc']?>')"></i>
						  </div>
					 </td>
				</tr>
		  <?php
		  }
		  ?>
	 </tbody>
</table>

<div class="modal fade" id="pos_config_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
					 <div id="modal_header"></div>
					 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				
				<div id="pos_config_content" class="modal-body"></div>
				
		  </div>
    </div>
</div>

<script>

 /**
  *
  * set hidden input pos_config_id so it will be included in the form
  *
  **/
 
 function setConfig (action, id) {

	  let configID = '#' + action + '_pos_config_id';
	  
	  console.log ('set config id.. ' + configID);
	  
	  $(configID).val (id);
 }
 
  function getFile (file) {
	  
     var file = file.files [0];  
     $('#file_name').val (file.name);
 	  $('#import_button').removeClass ('btn-secondary');
 	  $('#import_button').addClass ('btn-success');
 }
 
  function modal (configID, action) {

	  console.log (`modal... ${configID} ${action}`);
	  
	  $.ajax ({
         url: `/pos-configs/${action}/` + configID,
         type: 'GET',
         success: function (data) {

				 data = JSON.parse (data);				 
				 $('#pos_config_content').html (data.html);
         }
     });
  }

 $('#templates').click (function () {

	  $.ajax ({
         url: '/pos-configs/templates',
         type: 'GET',
         success: function (data) {

				 data = JSON.parse (data);				 
				 $('#templates_content').html (data.html);
         }
     });
 });

function deleteConfig (id, desc) {

	  if (confirm ('<?= __ ('Delete configuration ') ?>"' + desc + '"?')) {

			$.ajax ({url: '/pos-configs/delete-config/' + id,
						type: 'GET',
						success: function (data) {
							 
							 data = JSON.parse (data);
							 window.location = '/pos-configs';
						}
			});	
	  }
}
 
</script>

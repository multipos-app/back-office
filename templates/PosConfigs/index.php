
<nav>
	 <ol class="breadcrumb">
		  <li class="breadcrumb-item"><a href="index.html">Home</a></li>
		  <li class="breadcrumb-item active">POS Configs</li>
	 </ol>
</nav>
<table class="table table-hover">
	 <thead align="center">
		  <tr>
				<th align="left"><?= __ ('Description') ?></th>
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
								<a href="#"><i class="bx bxs-wrench icon-lg"></i></a>
								<!-- <a href="/pos-configs/settings"><i class="bx bxs-wrench icon-lg"></i></a> -->
						  </div>
					 </td>
					 <td align="center">
						  <div class="icon">
								<a href="/pos-configs/download/<?= $config ['id']?>"><i class="bx bx-cloud-download icon-lg"></i></a>
						  </div>
					 </td>
					 <td align="center">
						  <div class="icon" data-bs-toggle="modal" data-bs-target="#upload_modal">
								<!-- <i class="bx bx-cloud-upload icon-lg"></i> -->
								<i class="bx bx-cloud-upload icon-lg" onclick="setConfig ('upload', <?= $config ['id']?>)"></i>
						  </div>
					 </td>
					 <td align="center">
						  <div class="icon">
						  <div class="icon" data-bs-toggle="modal" data-bs-target="#clone_modal">
								<i class="bx bx-copy icon-lg" onclick="setConfig ('clone', <?= $config ['id']?>)"></i>
						  </div>
					 </td>
					 <td align="center">
						  <div class="icon">
								<i class="bx bxs-trash icon-lg"></i>
						  </div>
					 </td>
				</tr>
		  <?php
		  }
		  ?>
	 </tbody>
</table>

<div class="modal fade" id="upload_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
					 <div id="modal_header"><?= __ ('Upload file') ?></div>
					 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div id="modal_content" class="modal-body">
					 
					 <form id="upload_form" enctype="multipart/form-data" method="post" accept-charset="utf-8" class="row g-3" method="post" action="/pos-configs/upload">
						  
						  <input type="hidden" id="upload_pos_config_id" name="upload_pos_config_id" value="0">
						  	  
						  <div class="row mb-3">
								<label for="inputNumber" class="col-sm-4 col-form-label">File Upload</label>
								<div class="col-sm-8">
									 <input type="file" id="upload_file" name="upload_file">
								</div>
						  </div>
						  
						  <div class="row g-3">
								<div class="col-12 text-center">
									 <button type="submit" id="import_button" class="btn btn-primary" onclick="upload ()"><?= __ ('Upload/Import') ?></button>
								</div>
						  </div>
						  
					 </form>

				</div>
		  </div>
    </div>
</div>

<div class="modal fade" id="clone_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
					 <div id="modal_header"><?= __ ('Clone configuration') ?></div>
					 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div id="modal_content" class="modal-body">
					 
					 <form id="clone_form" enctype="multipart/form-data" method="post" accept-charset="utf-8" class="row g-3" method="post" action="/pos-configs/upload">
						  
						  <input type="hidden" id="clone_pos_config_id" name="clone_pos_config_id" value="0">

						  <div class="row mb-3">

								<div class="col-12">
									 <label for="sku" class="form-label">Configuration description</label>
									 <?= $this->input ('fa-text-size',
															 ['id' => 'config_desc',
															  'name' =>'config_desc',
															  'value' => '',
															  'class' => 'form-control'])
									 ?>
								</div>
						  </div>
						  
						  <div class="row g-3">
								<div class="col-12 text-center">
									 <button type="submit" id="import_button" class="btn btn-primary" onclick="clone ()"><?= __ ('Clone') ?></button>
								</div>
						  </div>
						  
					 </form>

				</div>
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

 function upload () {
	  
	  $('#upload_form').submit (function (e) {
			
			$.ajax ({url: '/pos-configs/upload/',
						type: 'POST',
						data: new FormData (this),
						processData: false,
						contentType: false,
						success: function (data) {

							 data = JSON.parse (data);
							 if (data.status != 0) {

								  alert (data.status_text);
							 }
							 
							 window.location = '/pos-configs/index';
						}
			});
			
			e.preventDefault ();
	  });
 }
 
  function clone () {
	  
	  $('#clone_form').submit (function (e) {
			
			$.ajax ({url: '/pos-configs/clone/',
						type: 'POST',
						data: new FormData (this),
						processData: false,
						contentType: false,
						success: function (data) {

							 data = JSON.parse (data);
							 if (data.status != 0) {

								  alert (data.status_text);
							 }
							 
							 window.location = '/pos-configs/index';
						}
			});
			
			e.preventDefault ();
	  });
 }
 
</script>

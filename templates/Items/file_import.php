<style>
 
 input[type="file"] {
     display: none;
 }

 .custom-file-import {
     /* border: 1px solid #ccc; */
	  width: 100%;
     display: inline-block;
     /* padding: 10px; */
     cursor: pointer;
 }
 
 .import-grid {
	  
     width: 30%;
     grid-template-columns: 1fr;
	  grid-row-gap: 10px !important;
	  margin-top: 25px;
 }

</style>

<div class="form-section">
	 <?= __ ('Import items') ?>
</div>

<div class="form-grid import-grid">
	 
	 <form id="import_form" enctype="multipart/form-data" method="post" accept-charset="utf-8" action="/items/file-import">			

		  <div class="form-cell form-control-cell form-center">
				
				<label class="custom-file-import">
					 					 
					 <input type="file" id="import_file" class="btn btn-primary btn-block" name="import_file" onchange="getFile (this)">
					 
					 <?= __ ('Click to select file') ?>&nbsp;<i class="far fa-cloud-import fa-large action_icons"></i>
					 
				</label>
				
		  </div>
		  
		  <div class="form-cell"></div>
		  
		  <div class="form-cell form-desc-cell form-center" id="file_name"><?= __ ('File name') ?></div>
		  
		  <div class="form-cell"></div>
		  <div class="form-cell form-center">
				
			<button id="import_button" class="btn btn-secondary btn-block text-center"><?= __ ('Import') ?></button>

		  </div>
		  
	 </form>
</div>

<script>

 function getFile (file) {
	  
 	  console.log (file.files [0]);
 	  
     var file = file.files [0];  
     $('#file_name').html (file.name);
  	  $('#import_button').removeClass ('btn-secondary');
  	  $('#import_button').addClass ('btn-success');
 }
	  
 $('#import_form').submit (function (e) {

	  console.log (e);
	  
	  $.ajax ({url: '/items/file-import',
				  type: 'POST',
				  data: new FormData (this),
				  processData: false,
				  contentType: false,
				  success: function (data) {
						
						/* controller ('items', false);*/
				  }
	  });
	  
	  e.preventDefault ();
 });
 
</script>

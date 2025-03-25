<form id="clone_form" enctype="multipart/form-data" method="post" accept-charset="utf-8" class="row g-3" method="post" action="/pos-configs/upload">
	 
	 <div class="row mb-3">

		  <div class="col-12">
				<label for="config_desc" class="form-label">Configuration description</label>
				<?=
				$this->input ('fa-text-size',
								  ['id' => 'config_desc',
									'name' =>'config_desc',
									'value' => '',
									'class' => 'form-control'])
				?>
		  </div>
	 </div>
	 
	 <div class="row g-3">
		  <div class="col-12 text-center">
				<button type="submit" id="import_button" class="btn btn-primary" data-bs-dismiss="modal" onclick="clone ()"><?= __ ('Clone') ?></button>
		  </div>
	 </div>
	 
</form>

<script>
 
 function clone () {

	  $('#clone_form').submit (function (e) {
			
			e.preventDefault ();
			$.ajax ({url: '/pos-configs/clone/<?= $posConfigID ?>',
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
	  });
 }
 
</script>

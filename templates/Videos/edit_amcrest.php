<style>

 .status-text {

	  color: red;
	  font-size: 150%;
 }
 
</style>

<script>

 var camera = <?php echo json_encode ($camera, true); ?>;
 console.log (camera);
 
</script>

<form id="camera_edit" name="camera_edit" autocomplete="off">
	 
	 <input type="hidden" name="camera[id]" value="<?= $camera ['id'] ?>">
	 <input type="hidden" name="camera[business_unit_id]" value="<?=  $camera ['business_unit_id']?>">
	 <input type="hidden" name="camera[alt_device_id]" value="<?= $camera ['alt_device_id'] ?>">
	 <input type="hidden" name="camera[protocol]" value="amcrest">
	 <input type="hidden" name="camera[params][type]" value="camcapi">
	 <input type="hidden" name="camera[params][camera_type]" value="amcrest">
	 <input type="hidden" name="camera[params][username]" value="admin">
	 
	 <div class="row g-1 mt-3">
		  <label for="item_desc" class="col-sm-4 form-label"><?= __ ('Device name') ?></label>
		  <div class="col-sm-8">
				<?=
				$this->Form->control ('camera[camera_name]', ['value' => $camera ['device_name'],
																			 'class' => 'form-control',
																			 'label' => false,
																			 'required' => 'required',
																			 'autocomplete' => "off"])
				?>
		  </div>
	 </div>
	 
	 <div class="row g-1 mt-3">
		  <label for="item_desc" class="col-sm-4 form-label"><?= __ ('Serial number') ?></label>
		  <div class="col-sm-8">
				<?=
				$this->Form->control ('camera[params][serial_number]', ['value' => $camera ['params'] ['serial_number'],
																						  'class' => 'form-control',
																						  'label' => false,
																						  'required' => 'required',
																						  'autocomplete' => "off"])
				?>
		  </div>
	 </div>
	 
	 <div class="row g-1 mt-3">
		  <label for="item_desc" class="col-sm-4 form-label"><?= __ ('Camera username') ?></label>
		  <div class="col-sm-2 d-flex align-items-center">
				<h6><?= __ ('admin') ?></h6>
		  </div>
		  <div class="col-sm-6 d-flex align-items-center">
				<?= __ ('Camera sername must be admin') ?>
		  </div>
	 </div>
	 
	 <div class="row g-1 mt-3">
		  <label for="item_desc" class="col-sm-4 form-label"><?= __ ('Camera password') ?></label>
		  <div class="col-sm-8">
				<?=
				$this->Form->control ('camera[params][password]', ['value' => $camera ['params'] ['password'],
																					'type' => 'password',
																					'class' => 'form-control',
																					'label' => false,
																					'required' => 'required',
																					'autocomplete' => "off"])
				?>
		  </div>
	 </div>

	 <div class="row g-1 mt-3">
		  <label for="item_desc" class="col-sm-4 form-label"><?= __ ('Timezone') ?></label>
		  <div class="col-sm-8">
				<?=
				$this->Form->select ('timezone',
											$timezones,
											['class' => 'form-select',
											 'name' => 'camera[timezone]', 
											 'id' => 'timezone',
											 'value' => $timezone,
											 'label' => false])
				?>
		  </div>
	 </div>

	 

	 <div class="row g-3 mt-3">
		  <?php if ($camera ['id'] == 0) { ?>
				
				<div class="col-sm-9"></div>
 				<div class="col-sm-3 d-grid text-center">
					 <button type="submit" id="camera_update" class="btn btn-success"><?= __ ('Save') ?></button>
				</div>
				
		  <?php } else { ?>

				<div class="col-sm-3"></div>
				<div class="col-sm-3 text-center">
					 <button name="camera_update" id="camera_update" class="btn btn-success w-100"><?= __ ('Save') ?></button>
				</div>
				<div class="col-sm-3 text-center">
					 <button name="camera_restart" id="camera_restart" class="btn btn-primary w-100"><?= __ ('Restart') ?></button>
				</div>
				<div class="col-sm-3 text-center">
					 <button name="camera_delete" id="camera_delete" class="btn btn-danger w-100"><?= __ ('Delete') ?></button>
				</div>
				
		  <?php } ?>
	 </div>
	 
	 <div id="alert_status" class="row mt-3 d-none">
		  <div class="col-12">
				<div id="alert_text" class="alert alert-warning mt-3" role="alert"></div>
		  </div>
	 </div>

</form>

<script>
 
 $('#camera_update').on ('click', function (e) {
	  
     e.preventDefault ();

	  var form = document.querySelector ('form');
	  if (!form.checkValidity()) {

			form.reportValidity ();
			return;
	  }

	  let url = '/videos/update';

	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#camera_edit').serialize (),
				  success: function (data) {

						data = JSON.parse (data);
						console.log (data);
						
						if (data.status == 0) {

							 window.location = multipos.pathname;
						}
						else {
							 
							 $('#alert_text').html ('');
							 $('#alert_text').html (data.status_text);
							 $('#alert_status').show ();
						}
				  },
				  fail: function () {

						console.log ('fail...');
				  },
				  always: function () {

						console.log ('always...');
				  }
	  });
 });

 $('#camera_restart').on ('click', function (e) {
	  
     e.preventDefault ();

	  if (confirm ('Restart camera, ' + camera.device_name + '?')) {

		 	$.ajax ({type: "GET",
						url: '/videos/restart/' + camera.alt_device_id,
						success: function (data) {

							 data = JSON.parse (data);
							 window.location = multipos.pathname;
						},
						fail: function () {

							 console.log ('fail...');
						},
						always: function () {

							 console.log ('always...');
						}
			});
	  }
 });

 $('#camera_delete').on ('click', function (e) {
	  
	  if (confirm ('Delete camera, ' + camera.device_name + '?')) {

		 	$.ajax ({type: "GET",
						url: '/videos/delete/' + camera.alt_device_id,
						success: function (data) {

							 data = JSON.parse (data);							 
							 window.location = multipos.pathname;
						},
						fail: function () {

							 console.log ('fail...');
						},
						always: function () {

							 console.log ('always...');
						}
			});
	  }
 });
 
</script>

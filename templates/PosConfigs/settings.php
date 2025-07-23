
<script>

 let pos = <?= json_encode ($pos) ?>;
 console.log (pos);
 
</script>

<form id="settings_form">

	 <input type="hidden" id="pos_config_id" name="pos_config_id" value="<?= $posConfigID ?>"/>
	 
	 <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="settingsTab" role="tablist">

		  <li class="nav-item flex-fill" role="presentation">
				<button class="nav-link active w-100"
									id="options_tab"
									data-bs-toggle="tab"
									data-bs-target="#options"
									type="button"
									role="tab"
									aria-controls="options"
									aria-selected="false"><?= __ ('POS options') ?></button>
		  </li>

		  <li class="nav-item flex-fill" role="presentation">
				<button class="nav-link w-100"
									id="hardware_tab"
									data-bs-toggle="tab"
									data-bs-target="#hardware"
									type="button"
									role="tab"
									aria-controls="hardware"
									aria-selected="true"><?= __ ('POS hardware') ?></button>
		  </li>
		  

	 </ul>
	 
	 <div class="tab-content pt-2" id="settingsTabContent">

		  <div class="tab-pane fade active show"
				 id="options"
				 role="tabpanel"
				 aria-labelledby="options_tab">

				<?php
				
				foreach ($settings ['options'] as $option) {

					 $checked = $option ['on'] == true ? ' checked' : '';
				?>
					 <div class="row g-1 mt-3">
						  <div class="col-sm-4 form-check form-switch">
								<input type="hidden" name="options[<?= $option ['key'] ?>]" id="_<?= $option ['key'] ?>" value="off">
								<input type="checkbox" class="form-check-input" name="options[<?= $option ['key'] ?>]" id="<?= $option ['key'] ?>"<?= $checked ?>>
								<label class="grid-label" for="<?= $option ['key'] ?>" ?><?= $option ['name'] ?></label>	 
						  </div>
						  <div class="col-sm-8"><?= $option ['description'] ?></div>
					 </div>
				<?php
				}
				?>
		  </div>

		  <div class="tab-pane fade"
				 id="hardware"
				 role="tabpanel"
				 aria-labelledby="hardware_tab">
				
				<?php
				
				foreach ($settings ['devices'] as $name => $device) {

					 $this->debug ($device);
					 
				?>
					 
					 <div class="row g-1 mt-4">
						  
						  <label for="" class="col-sm-4 form-label"><?= $device ['desc'] ?></label>
						  <div class="col-sm-6">
								
								<?=
								$this->Form->select ('devices[' . $name . ']',
															$device ['options'],
															['id' => $name,
															 'value' => $device ['selected'],
															 'label' => false,
															 'class' => 'form-select']);
								?>
						  </div>
					 </div>
				<?php
				}
				?>
		  </div>
		  
	 </div>

	 <div class="row g-3 mt-3">
		  <div class="col-sm-9"></div>
 		  <div class="col-sm-3 d-grid text-center">
				<button type="submit" class="btn btn-success" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
		  </div>
	 </div>
	 
</form>

<script>
	  
 $('#pos').change (function (e) {

	  // device = settings.devices.pos.options [$('#pos_type').val ()];
	  
	  console.log (pos);

	  $.each (pos [$('#pos').val ()].devices, function (devType, devValue) {
	
			console.log (`pos... ${devType} ${devValue}`);
			$(`#${devType}`).val (devValue);
	  });
 });
 
 $('#settings_form').submit (function (e) {

	  e.preventDefault ();
	  let url = "/pos-configs/settings/" + $('#pos_config_id').val ();
	  console.log (url);

 	  $.ajax ({type: "POST",
				  url: url,
				  data: $('#settings_form').serialize (),
				  success: function (data) {

						data = JSON.parse (data);
						console.log (data);
						window.location = '/pos-configs';
				  }});
 });

</script>

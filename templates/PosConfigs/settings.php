
<form id="settings_form">

	 <input type="hidden" id="pos_config_id" name="pos_config_id" value="<?= $posConfigID ?>"/>

	 <div class="row g-1 mt-3">
		  <div class="col-sm-12 text-center"><h4><?= __ ('POS hardware') ?></h4></div>
	 </div>
	 
	 <?php
	 
	 foreach ($settings ['devices'] as $name => $device) {

		  $this->debug ("device name... $name");
		  $this->debug ($device);
		  
	 ?>					 
		  
		  <div class="row g-1 mt-4">
				
				<label for="price" class="col-sm-4 form-label"><?= $device ['desc'] ?></label>
				<div class="col-sm-6">
					 
					 <?=
					 $this->Form->select ('devices[' . $name . ']',
												 $device ['options'],
												 ['value' => $device ['selected'],
												  'label' => false,
												  'class' => 'form-select']);
					 ?>
				</div>
		  </div>
	 <?php
	 }
	 ?>
	 </div>
	 
	 <div class="row g-1 mt-3">
		  <div class="col-sm-12 text-center"><h4><?= __ ('POS Options') ?></h4></div>
	 </div>
	 
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

	 <div class="row g-3 mt-3">
		  <div class="col-sm-9"></div>
 		  <div class="col-sm-3 d-grid text-center">
				<button type="submit" class="btn btn-success" data-bs-dismiss="modal"><?= __ ('Save') ?></button>
		  </div>
	 </div>
	 
</form>

<script>


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

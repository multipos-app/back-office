<style>
 
  .controls-grid {
	  
     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 120px 3fr .1fr 2fr 2fr 3fr;
	  grid-column-gap: 10px;
	  margin-top: 25px;
 }

.main-grid {

     display: grid;
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 1fr;
	  grid-column-gap: 0px;
	  margin-top: 25px;
 }

 .device-grid {

     width: 50%;
     grid-template-rows: auto;
     grid-template-columns: 1fr 1fr;
 }
 
 .option-grid {
	  
     width: 100%;
     grid-template-rows: auto;
     grid-template-columns: 2fr 2fr;
     grid-column-gap: 15px;
 	  grid-row-gap: 25px;
 	  margin-top: 25px;
 }

 label {

	  font-size: 1.2em !important;
 }
 
</style>

<div class="form-grid controls-grid">
	 <div class="form-cell">
		  <button id="multipos_back" class="btn btn-white multipos-back-button" onclick="controllerBack ()">
				<?= __ ('Back') ?>
		  </button>
	 </div>
</div>
	 
<form id="settings" name ="settings">

	 <input type="hidden" name="pos_config_id" value="<?= $posConfigID ?>"/>
	 
	 <div class="form-section">
		  <?= __ ('POS Peripherals') ?>
	 </div>
	 
	 <div class="main-grid">
		  
		  <div class="form-grid device-grid">
				<?php
								
				foreach ($settings ['devices'] as $name => $device) {
	 
				?>					 
					 <div class="form-cell form-desc-cell">
						  <?= $device ['desc'] ?>
					 </div>
					 
					 <div class="select">
						  
						  <?= $this->Form->select ('devices[' . $name . ']',
															$device ['options'],
															['value' => $device ['selected'],
															 'label' => false,
															 'class' => 'custom-dropdown',
															 'required' => 'required']);
						  ?>
					 </div>
				<?php
				}
				?>
		  </div>
		  
		  <div class="form-section">
				<?= __ ('POS Options') ?>
		  </div>
		  
		  <div class="form-grid option-grid">
				
				<?php
				
				foreach ($settings ['options'] as $option) {

				?>						  
					 <div class="grid-cell grid-cell-left">
						  <div class="checkbox checkbox-primary">
								<input type="checkbox" class="styled" name="options[<?= $option ['key']?>]" id="options[<?= $option ['key']?>]" type="checkbox"<?php if ($option ['on']) echo ' checked'; ?>>
								<label class="grid-label" for="<?= $option ['key'] ?>"><?= $option ['description'] ?></label>
						  </div>
					 </div>
				<?php
				}
				?>
				
		  </div>
	 </div>
</form>

<div class="form-submit-grid">
	 
	 <div class="grid-cell">
		  <button class="btn btn-success btn-block control-button" onclick="javascript:save ()"><?= __ ('Save') ?></button>
	 </div>
	 
	 <div class="grid-cell">
		  <button class="btn btn-warning btn-block control-button" onclick="javascript:save ()"><?= __ ('Cancel') ?></button>
	 </div>
	 
</div>

<script>

 function save () {

	  console.log ($('#settings').serialize ());
 	  $.ajax ({type: "POST",
				  url: "/pos-configs/update-settings",
				  data: $('#settings').serialize (),
				  success: function (data) {

						console.log (data);
						controller ('pos-configs', false);
				  }});
 }
 
 function cancel () {

	  controller ('pos-configs', false);
 }

</script>

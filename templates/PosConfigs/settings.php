<style>
 
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
 
</style>

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
					 
					 <div class="form-cell select">
						  
						  <?= $this->Form->select ('devices[' . $name . ']',
															$device ['options'],
															['value' => $device ['selected'],
															 'label' => false,
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

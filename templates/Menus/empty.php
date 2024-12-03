<style>

 .button-edit-grid {

     width: 100%;
     grid-template-rows: 1fr;
     grid-template-columns: 1fr 2fr;
 }

 .select-separator {

	  color: #fff !important;
	  font-weight: 600 !important;
 }
 
</style>

<div class="form-section">
	 <i class="fa fa-square-xmark fa-large" onclick="buttonClose ()"></i>
</div>

<div id="button_edit" class="form-grid button-edit-grid">

	 <input type="hidden" id="pos_config_id" value="<?= $button ['pos_config_id'] ?>">
	 <input type="hidden" id="container" value="<?= $button ['container'] ?>">
	 <input type="hidden" id="menu" value="<?= $button ['menu'] ?>">
	 <input type="hidden" id="pos" value="<?= $button ['pos'] ?>">
	 
	 <div class="form-cell form-desc-cell"><?= __ ('Add button') ?></div>
	 <div id="controls" class="select"></div>  <!-- controller picker -->
	 
</div>

<script>

 
 b = <?php echo json_encode ($button); ?>;
 controllers = <?= json_encode ($button ['controls']) ?>;
 
 html =
     '<select id="select_control" name="select_control" class="custom-dropdown" onchange="control ();">' +
     '<option selected value="">Select a button action</option>';
 
 for (var className in controllers) {
	  
	  if (className.startsWith ('separator')) {
         html += '<option disabled class="select-separator">' + controllers [className] ['desc'] + '</option>';
	  }
	  else if (controllers [className] ['desc'].length > 0) {
         html += '<option value="' + className + '">' + controllers [className] ['desc'] + '</option>';
	  }
 }
 
 html += '</select>';
 
 function control () {
	  
     let control = controllers [$('#select_control').val ()];
	  
	  control ['pos_config_id'] = $('#pos_config_id').val ();
	  control ['container'] = $('#container').val ();
	  control ['menu'] = $('#menu').val ();
	  control ['pos'] = $('#pos').val ();
	  $.ajax ({type: "POST",
	  	  
				  url: '/menus/button/',
				  data: control,
				  success: function (data) {
						
						data = JSON.parse (data);

						if (data.status == 0) {
							 
							 $('#button_container').html (data.html);
						}
				  }
	  });
 }
 
 $('#controls').html (html);
 
</script>

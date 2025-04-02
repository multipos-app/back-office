
<?php

$this->debug ('buttons null...');

?>

<div class="row g-3">
	 
	 <div class="col-sm-12">
		  <select name="class" id="button_type" class="form-select" required="required">
				
				<option value="null"><?= __ ('Select type of button') ?></option>
				
				<?php foreach ($controls as $controlCategory) {
				?>
					 
					 <option disabled><?= $controlCategory ['name']?></option>
					 
					 <?php foreach ($controlCategory ['pos_controls'] as  $posControl) { ?>
						  
						  <option value="<?= $posControl ['class'] ?>"><?= $posControl ['description']?></option>
						  
					 <?php } ?>
				<?php } ?>
				
		  </select>
	 </div>
	 
</div>

<div class="row g-3 mt-3">
 	 <div class="col-sm-12 d-grid text-center">
		  <button class="btn btn-primary" id="button_continue"><?= __ ('Continue') ?></button>
	 </div>
</div>

<script>
 
 $('#button_continue').click (function (e) {

	  data = {config_id: <?= $configID ?>,
				 menu_name: '<?= $menuName ?>',
				 menu_index: <?= $menuIndex ?>,
				 pos: <?= $pos ?>,
				 button_type: $('#button_type').val ()};
	  
	  $.ajax ({url: '/buttons/select-control/',
				  type: 'POST',
				  data: data,
				  success: function (data) {
						
						data = JSON.parse (data);
						$('#modal_content').html (data.html);
				  }
	  });
 });

</script>

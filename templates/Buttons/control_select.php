

<div class="row g-3">
	 
	 <div class="col-sm-12">
		  <select name="class" id="class" class="form-select" required="required">
				
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
 
 $('#modal_title').html ('<?= __ ('Select a button action') ?>');
 $('#button_continue').click (function (e) {

	  data = {config_id: <?= $configID ?>,
				 menu_name: '<?= $menuName ?>',
				 menu_index: <?= $menuIndex ?>,
				 pos: <?= $pos ?>,
				 class: $('#class').val (),
				 button_text: $("#class option:selected").text ()};
			
	  $.ajax ({url: '/buttons/select-control-template/',
				  type: 'POST',
				  data: data,x
				  success: function (data) {

						data = JSON.parse (data);
						menus.setModal (data.text, data.html);
				  }
	  });
 });

</script>

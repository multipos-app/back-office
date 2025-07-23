

<!-- menu action form -->

<form id="menu_actions" method="post" action="/menus/action/menu_action_settings/<?= $configID ?>/<?= $menuName ?>/<?= $menuIndex ?>">

	 <input type="hidden" name="action" value="menu_action_settings">

	 <div class="row mt-3">
 		  <label for="menu_name" class="col-sm-3 form-label"><?= __ ('Menu Name') ?></label>	 
		  <div class="col-sm-9">

				<?= $this->input ('menu_name',
										['name' => 'menu_name',
										 'value' => $name,
										 'class' => 'form-control'])
				?>
		  </div>
	 </div>

	 <div class="row m-3">
		  
 		  <div class="col-sm-10 form-check form-switch" style="cursor: pointer;">
				
				<input type="hidden" name="menu_tabs" id="menu_tabs" value="off">
				<input type="hidden" name="tab_color" value="">
				<input type="checkbox" class="form-check-input profile-modify" name="menu_tabs" id="menu_tabs">
				<label for="menu_tabs" class="form-label"><?= __ ('Auto generate menu tabs') ?></label>	 
				
		  </div>
		  
	 </div>

	 <label for="colors" form-label"><?= __ ('Tab color') ?></label>	 
	 <div id="colors" class="row">
		  
	  	  <div class="col-sm-2">
				<input id="tab_color" class="form-control form-control-color" style="background-color:<?= $defaultColor ?>;">
		  </div>
		  
		  <div class="col-sm-10">
				
				<div class="color-grid">
					 
					 <?php
					 for ($i = 0; $i < 16; $i ++) {
					 ?>
						  <div id="color_<?= $i ?>" class="form-control form-control-color" style="background: <?= $colors [$i] ?>;" onclick="selectTabColor (<?= $i ?>)"></div>
					 <?php
					 } ?>
				</div>
		  </div>		  
	 </div>

	 <?php include ('menu_actions_footer.php'); ?>

</form>

<script>

 let colors = <?= json_encode ($colors) ?>;
 
 function selectTabColor (i) {

	  console.log (`select color... ${colors [i]}`);
	  $('#tab_color').css ({'background-color': colors [i]});
	  $('[name=tab_color]').val (colors [i]);
 }

</script>

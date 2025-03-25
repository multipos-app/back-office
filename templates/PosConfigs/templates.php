
<link href="/assets/css/menus.css" rel="stylesheet">

<div class="row mb-3">
	 
	 <div class="row g-1">
		  <label for="price" class="col-sm-4 form-label"><?= __ ('Configuration description') ?></label>
		  <div class="col-sm-8">
				<input type="text" class="form-control" id="pos_config_desc" value="" placeholder="<?= __ ('Enter description') ?>">
		  </div>
	 </div>

	 <div class="row g-3 mt-3 templates-grid">

		  <?php
		  $index = 0;
		  foreach ($templates as $template) {

				echo '<div id="menu_' . $index . '" class="menu-layout ' . 
					  $template ['layout'] . '" onclick="selectLayout (' . $index ++ . ')">' .
					  $this->element ($template ['layout']) . '</div>';
		  }	  
		  ?>
		  
	 </div>
	 
	 <div class="row g-3 mt-3">
		  <div class="col-sm-9 d-grid text-center"></div>
 		  <div class="col-sm-3 d-grid text-center">
				<button id="add_config" class="btn btn-primary" data-bs-dismiss="modal"><?= __ ('Create menu') ?></button>
		  </div>
	 </div>
</div>

<script>

 var index = -1;
 var templates = <?= json_encode ($templates) ?>;
 
 $('#add_config').click (function () {

	  if (index < 0) {

			alert ('<?= __ ('Please select a configuration template') ?>');
			return;
	  }

	  let data = templates [index];
	  data.desc = $('#pos_config_desc').val ();
			
	  $.ajax ({
         url: "/pos-configs/templates/",
         type: "POST",
			data: data,
         success: function (data) {
				 
				 data = JSON.parse (data);
				 if (data.status == 0) {

					  window.location = '/pos-configs';
				 }
			}
	  });
 });
			

 function selectLayout (i) {

	  if (index >= 0) {
			
			$(`#menu_${index}`).removeClass ("select-button");
	  }
	  
	  index = i;
	  $(`#menu_${i}`).addClass ("select-button");
 }

 menus = { menu: function (menu) { }};
 
</script>


